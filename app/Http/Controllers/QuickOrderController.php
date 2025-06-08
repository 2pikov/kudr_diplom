<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class QuickOrderController extends Controller
{
    public function show(Request $request)
    {
        $cartItems = DB::table('cart')
            ->where('user_id', $request->user()->id)
            ->get();
            
        $products = [];
        $total = 0;
        
        foreach ($cartItems as $item) {
            $product = DB::table('products')
                ->select('id', 'title', 'price', 'img')
                ->where('id', $item->product_id)
                ->first();
                
            if ($product) {
                $product->quantity = $item->quantity;
                $total += $product->price * $item->quantity;
                $products[] = $product;
            }
        }
        
        if (empty($products)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        return view('quick-order', [
            'products' => $products,
            'total' => $total
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $user = $request->user();
            $cartItems = DB::table('cart')
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Корзина пуста');
            }

            // Вычисляем общую сумму заказа
            $total = 0;
            foreach ($cartItems as $item) {
                $product = DB::table('products')->where('id', $item->product_id)->first();
                if ($product) {
                    $total += $product->price * $item->quantity;
                }
            }

            // Вычисляем финальную сумму с учетом бонусов
            $bonusToUse = min(
                (float)$request->input('use_bonus', 0),
                $user->bonus_balance ?? 0,
                $total * 0.3 // Максимум 30% от суммы заказа
            );
            $finalAmount = max(0, $total - $bonusToUse);
            $bonusEarned = floor($finalAmount * 0.03); // 3% от финальной суммы

            // Создаем данные заказа
            $orderData = [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'bonus_used' => $bonusToUse,
                'bonus_earned' => $bonusEarned,
                'items' => $cartItems->map(function($item) {
                    $product = DB::table('products')->where('id', $item->product_id)->first();
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $product->price
                    ];
                })->toArray()
            ];

            // Генерируем код подтверждения
            $confirmationCode = Str::random(6);

            // Сохраняем данные заказа и код в сессии
            session([
                'order_data' => $orderData,
                'confirmation_code' => $confirmationCode
            ]);

            // Отправляем email с кодом подтверждения
            Mail::send('emails.order-confirmation', ['code' => $confirmationCode], function($message) use ($validated) {
                $message->to($validated['email'])
                    ->subject('Подтверждение заказа')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            DB::commit();
            return redirect()->route('quick-order.confirm')->with('success', 'Код подтверждения отправлен на ваш email');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при оформлении заказа: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

    public function confirm(Request $request)
    {
        return view('quick-order-confirm');
    }

    public function verify(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|size:6'
            ]);

            $storedCode = session('confirmation_code');
            $orderData = session('order_data');

            if (!$storedCode || !$orderData) {
                return redirect()->route('cart')->with('error', 'Данные заказа не найдены');
            }

            if ($validated['code'] !== $storedCode) {
                return back()->with('error', 'Неверный код подтверждения');
            }

            DB::beginTransaction();

            $user = $request->user();
            $orderNumber = Str::random(8);
            $orderUid = Str::uuid()->toString();

            // Создаем заказ
            DB::table('orders')->insert([
                'uid' => $orderUid,
                'user_id' => $user->id,
                'number' => $orderNumber,
                'status' => 'Новый',
                'name' => $orderData['name'],
                'phone' => $orderData['phone'],
                'email' => $orderData['email'],
                'address' => $orderData['address'],
                'bonus_used' => $orderData['bonus_used'],
                'bonus_earned' => $orderData['bonus_earned'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Создаем записи о товарах
            foreach ($orderData['items'] as $item) {
                DB::table('order_items')->insert([
                    'order_uid' => $orderUid,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Обновляем бонусный баланс пользователя
            if (isset($user->bonus_balance)) {
                $newBalance = $user->bonus_balance - $orderData['bonus_used'];
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['bonus_balance' => $newBalance]);
            }

            // Очищаем корзину
            DB::table('cart')->where('user_id', $user->id)->delete();

            // Очищаем данные заказа из сессии
            session()->forget(['order_data', 'confirmation_code']);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Заказ успешно оформлен');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при подтверждении заказа: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при подтверждении заказа: ' . $e->getMessage());
        }
    }

    public function createOrder(Request $request, $productId)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $product = Product::findOrFail($productId);

            // Вычисляем финальную сумму с учетом бонусов
            $bonusToUse = min(
                (float)$request->input('use_bonus', 0),
                $user->bonus_balance ?? 0,
                $product->price * 0.3 // Максимум 30% от суммы заказа
            );
            $finalAmount = max(0, $product->price - $bonusToUse);
            $bonusEarned = floor($finalAmount * 0.03); // 3% от финальной суммы

            $orderNumber = Str::random(8);
            $orderUid = Str::uuid()->toString();

            // Создаем заказ
            DB::table('orders')->insert([
                'uid' => $orderUid,
                'user_id' => $user->id,
                'number' => $orderNumber,
                'status' => 'Новый',
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'bonus_used' => $bonusToUse,
                'bonus_earned' => $bonusEarned,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Создаем запись о товаре
            DB::table('order_items')->insert([
                'order_uid' => $orderUid,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Обновляем бонусный баланс пользователя
            if (isset($user->bonus_balance)) {
                $newBalance = $user->bonus_balance - $bonusToUse;
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['bonus_balance' => $newBalance]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Заказ успешно создан');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при создании заказа: ' . $e->getMessage());
        }
    }
} 