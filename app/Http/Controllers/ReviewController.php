<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10'
        ]);

        $review = new Review([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'content' => $validated['content']
        ]);

        $review->save();
        $review->load('user');

        return response()->json([
            'success' => true,
            'review' => [
                'user' => [
                    'name' => $review->user->name
                ],
                'rating' => $review->rating,
                'content' => $review->content,
                'created_at' => $review->created_at->format('d.m.Y')
            ],
            'average_rating' => $review->product->reviews()->avg('rating')
        ]);
    }
} 