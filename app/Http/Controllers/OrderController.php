<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = DB::table('orders')
            ->select('orders.*')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $ordersData = $orders->map(function($order) {
            $items = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('order_items.order_uid', $order->uid)
                ->select('products.title', 'order_items.quantity', 'order_items.price')
                ->get();

            $totalItems = $items->sum('quantity');
            $totalSum = $items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            return [
                'number' => $order->number,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'items' => $items->map(function($item) {
                    return [
                        'title' => $item->title,
                        'quantity' => $item->quantity,
                        'total' => $item->price * $item->quantity
                    ];
                }),
                'total_items' => $totalItems,
                'total_sum' => $totalSum
            ];
        });

        return view('orders.index', ['orders' => $ordersData]);
    }

    public function show(Request $request, $number)
    {
        $order = DB::table('orders')
            ->where('number', $number)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Заказ не найден');
        }

        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_uid', $order->uid)
            ->select('products.title', 'order_items.quantity', 'order_items.price')
            ->get();

        $totalItems = $items->sum('quantity');
        $totalSum = $items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $orderData = [
            'number' => $order->number,
            'status' => $order->status,
            'created_at' => $order->created_at,
            'name' => $order->name,
            'phone' => $order->phone,
            'email' => $order->email,
            'address' => $order->address,
            'items' => $items->map(function($item) {
                return [
                    'title' => $item->title,
                    'quantity' => $item->quantity,
                    'total' => $item->price * $item->quantity
                ];
            }),
            'total_items' => $totalItems,
            'total_sum' => $totalSum
        ];

        return view('orders.show', ['order' => $orderData]);
    }

    public function createOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $cartItems = DB::table('cart')->where('user_id', $user->id)->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Корзина пуста');
            }

            $orderNumber = Str::random(8);
            $orderUid = Str::uuid()->toString();
            
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                $totalAmount += $product->price * $item->quantity;
            }

            // Вычисляем финальную сумму с учетом бонусов
            $bonusToUse = min(
                (float)$request->input('use_bonus', 0),
                $user->bonus_balance ?? 0,
                $totalAmount * 0.3 // Максимум 30% от суммы заказа
            );
            $finalAmount = max(0, $totalAmount - $bonusToUse);
            $bonusEarned = floor($finalAmount * 0.03); // 3% от финальной суммы

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

            // Создаем записи о товарах
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                DB::table('order_items')->insert([
                    'order_uid' => $orderUid,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Обновляем бонусный баланс пользователя
            if (isset($user->bonus_balance)) {
                $newBalance = $user->bonus_balance - $bonusToUse;
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['bonus_balance' => $newBalance]);
            }

            // Очищаем корзину
            DB::table('cart')->where('user_id', $user->id)->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Заказ успешно создан');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при создании заказа: ' . $e->getMessage());
        }
    }

    public function getOrders(Request $request)
    {
        $query = DB::table('orders')
            ->select('orders.*')
            ->orderBy('orders.created_at', 'desc');

        // Фильтрация по статусу
        if ($request->has('filter')) {
            $status = match($request->filter) {
                'new' => 'Новый',
                'confirmed' => 'Подтвержден',
                'canceled' => 'Отменен',
                default => null
            };

            if ($status) {
                $query->where('orders.status', $status);
            }
        }

        $orders = $query->get();

        // Получаем товары для каждого заказа
        $ordersData = $orders->map(function($order) {
            $items = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('order_items.order_uid', $order->uid)
                ->select('products.title', 'order_items.quantity', 'order_items.price')
                ->get();

            $totalItems = $items->sum('quantity');
            $totalSum = $items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            return [
                'number' => $order->number,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'customer' => $order->name,
                'phone' => $order->phone,
                'email' => $order->email,
                'address' => $order->address,
                'items' => $items->map(function($item) {
                    return [
                        'title' => $item->title,
                        'quantity' => $item->quantity,
                        'total' => $item->price * $item->quantity
                    ];
                }),
                'total_items' => $totalItems,
                'total_sum' => $totalSum
            ];
        });

        return view('admin.orders', ['orders' => $ordersData]);
    }

    public function editOrderStatus(Request $request, $number, $action)
    {
        try {
            DB::beginTransaction();

            $order = DB::table('orders')
                ->where('number', $number)
                ->first();

            if (!$order) {
                throw new \Exception('Заказ не найден');
            }

            $status = match($action) {
                'confirm' => 'Подтвержден',
                'cancel' => 'Отменен',
                default => throw new \Exception('Неверное действие')
            };

            // Если заказ подтверждается, начисляем бонусы
            if ($action === 'confirm' && $order->status === 'Новый') {
                // Получаем сумму заказа
                $orderSum = DB::table('order_items')
                    ->where('order_uid', $order->uid)
                    ->sum(DB::raw('price * quantity'));

                // Вычисляем бонусы (3% от суммы заказа)
                $bonusEarned = floor($orderSum * 0.03);

                // Обновляем бонусный баланс пользователя
                $user = DB::table('users')->where('id', $order->user_id)->first();
                $newBalance = ($user->bonus_balance ?? 0) + $bonusEarned;

                DB::table('users')
                    ->where('id', $order->user_id)
                    ->update(['bonus_balance' => $newBalance]);

                // Обновляем информацию о бонусах в заказе
                DB::table('orders')
                    ->where('number', $number)
                    ->update([
                        'status' => $status,
                        'bonus_earned' => $bonusEarned,
                        'updated_at' => now()
                    ]);
            } else {
                // Просто обновляем статус
                DB::table('orders')
                    ->where('number', $number)
                    ->update([
                        'status' => $status,
                        'updated_at' => now()
                    ]);
            }

            DB::commit();
            return back()->with('success', 'Статус заказа успешно обновлен');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при обновлении статуса заказа: ' . $e->getMessage());
        }
    }

    public function deleteOrder($number)
    {
        try {
            DB::beginTransaction();

            // Получаем заказ
            $order = DB::table('orders')
                ->where('number', $number)
                ->where('user_id', auth()->id())
                ->first();

            if (!$order) {
                return redirect()->route('orders.index')->with('error', 'Заказ не найден');
            }

            // Проверяем статус заказа
            if ($order->status !== 'Новый') {
                return redirect()->route('orders.index')->with('error', 'Можно отменить только новый заказ');
            }

            // Возвращаем бонусы пользователю
            if ($order->bonus_used > 0) {
                $user = DB::table('users')->where('id', auth()->id())->first();
                $newBalance = ($user->bonus_balance ?? 0) + $order->bonus_used;
                
                DB::table('users')
                    ->where('id', auth()->id())
                    ->update(['bonus_balance' => $newBalance]);
            }

            // Удаляем записи о товарах заказа
            DB::table('order_items')
                ->where('order_uid', $order->uid)
                ->delete();

            // Удаляем сам заказ
            DB::table('orders')
                ->where('number', $number)
                ->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Заказ успешно отменен');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при отмене заказа: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('error', 'Произошла ошибка при отмене заказа');
        }
    }
}
