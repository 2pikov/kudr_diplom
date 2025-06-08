<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartTable = DB::table('cart')
            ->where('user_id', $request->user()->id)
            ->get();
        $goodCart = [];
        foreach ($cartTable as $cartItem) {
            $product = DB::table('products')
                ->select('title', 'price', 'qty')
                ->where('id', $cartItem->product_id)
                ->first();
            array_push(
                $goodCart,
                (object)[
                    'id' => $cartItem->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'qty' => $cartItem->quantity,
                    'limit' => $product->qty
                ]
            );
        }
        return view('cart', ['cart' => $goodCart]);
    }

    public function addToCart($id, Request $request)
    {
        try {
            $product = Product::findOrFail($id);
            $cartTable = DB::table('cart');
            $itemInCart = $cartTable
                ->where('user_id', $request->user()->id)
                ->where('product_id', $id)
                ->first();

            if (!$itemInCart) {
                // Если товара нет в корзине, проверяем доступное количество
                if ($product->qty > 0) {
                    // Добавляем товар в корзину
                    $cartTable->insert([
                        'user_id' => $request->user()->id,
                        'product_id' => $id,
                        'quantity' => 1
                    ]);
                    
                    // Уменьшаем количество товара в БД
                    DB::table('products')
                        ->where('id', $id)
                        ->update(['qty' => $product->qty - 1]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Товар добавлен в корзину'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Товар закончился'
                    ]);
                }
            } else {
                // Если товар уже есть и его количество не превышает доступное
                if ($product->qty > 0) {
                    $cartTable
                        ->where('user_id', $request->user()->id)
                        ->where('product_id', $id)
                        ->update(['quantity' => $itemInCart->quantity + 1]);
                    
                    // Уменьшаем количество товара в БД
                    DB::table('products')
                        ->where('id', $id)
                        ->update(['qty' => $product->qty - 1]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Количество товара в корзине увеличено'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Достигнуто максимальное количество товара'
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при добавлении товара'
            ]);
        }
    }

    public function changeQty(Request $request)
    {
        try {
            $cartItem = DB::table('cart')
                ->where('user_id', $request->user()->id)
                ->where('id', $request->id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Товар не найден в корзине'
                ]);
            }

            $product = Product::find($cartItem->product_id);

            if ($request->param == "incr") {
                // Проверяем, есть ли товар в наличии
                if ($product->qty > 0) {
                    // Увеличиваем количество в корзине
                    DB::table('cart')
                        ->where('id', $request->id)
                        ->update(['quantity' => $cartItem->quantity + 1]);
                    
                    // Уменьшаем количество в БД
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['qty' => $product->qty - 1]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Товар закончился'
                    ]);
                }
            } else if ($request->param == "decr") {
                if ($cartItem->quantity > 1) {
                    // Уменьшаем количество в корзине
                    DB::table('cart')
                        ->where('id', $request->id)
                        ->update(['quantity' => $cartItem->quantity - 1]);
                    
                    // Возвращаем товар в БД
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['qty' => $product->qty + 1]);
                } else {
                    // Удаляем товар из корзины
                    DB::table('cart')->where('id', $request->id)->delete();
                    
                    // Возвращаем товар в БД
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['qty' => $product->qty + 1]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Количество обновлено'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении количества'
            ]);
        }
    }

    public function removeFromCart($id)
    {
        try {
            $cartItem = DB::table('cart')->where('id', $id)->first();
            
            if ($cartItem) {
                // Возвращаем товар в БД
                DB::table('products')
                    ->where('id', $cartItem->product_id)
                    ->increment('qty', $cartItem->quantity);
                
                // Удаляем из корзины
                DB::table('cart')->where('id', $id)->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Товар удален из корзины'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден в корзине'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении товара'
            ]);
        }
    }
}
