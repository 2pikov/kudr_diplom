<?php

namespace App\Http\Controllers;

use App\Models\Comparison;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index()
    {
        $comparisons = auth()->user()->comparisons()->with('product')->get();
        return view('comparisons.index', compact('comparisons'));
    }

    public function toggle(Request $request)
    {
        $productId = $request->product_id;
        $userId = auth()->id();

        // Проверяем количество товаров в сравнении только при добавлении

        $comparison = Comparison::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($comparison) {
            // Если товар найден, удаляем его
            $comparison->delete();
            return response()->json(['status' => 'removed']);
        } else {
             // Если товар не найден и метод POST, добавляем его (ограничение в 2 товара)
            if ($request->isMethod('post')) {
                $comparisonCount = Comparison::where('user_id', $userId)->count();
                 if ($comparisonCount >= 2) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Можно сравнивать максимум 2 товара'
                    ], 400);
                }

                Comparison::create([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);

                return response()->json(['status' => 'added']);
            }
        }

        // Если товар не найден и метод не POST (например, DELETE), возвращаем ошибку
        return response()->json(['status' => 'error', 'message' => 'Товар для удаления не найден.'], 404);
    }
} 