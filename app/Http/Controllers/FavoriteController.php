<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $query = Favorite::where('user_id', auth()->id())
            ->with(['product' => function($query) {
                $query->with('reviews');
            }]);

        // Применяем сортировку
        switch($request->get('sort')) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_asc':
                $query->join('products', 'favorites.product_id', '=', 'products.id')
                    ->orderBy('products.price', 'asc')
                    ->select('favorites.*');
                break;
            case 'price_desc':
                $query->join('products', 'favorites.product_id', '=', 'products.id')
                    ->orderBy('products.price', 'desc')
                    ->select('favorites.*');
                break;
            case 'rating_desc':
                $query->join('products', 'favorites.product_id', '=', 'products.id')
                    ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
                    ->groupBy('favorites.id')
                    ->orderByRaw('AVG(COALESCE(reviews.rating, 0)) DESC')
                    ->select('favorites.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $favorites = $query->get();

        return view('favorites', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = auth()->id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        }

        Favorite::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return response()->json(['status' => 'added']);
    }
} 