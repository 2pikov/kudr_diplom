<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WelcomeController extends Controller
{
    public function index()
    {
        // Получаем 3 случайных товара для хитов продаж
        $hits = Product::inRandomOrder()
            ->limit(3)
            ->get();

        return view('welcome', compact('hits'));
    }
}
