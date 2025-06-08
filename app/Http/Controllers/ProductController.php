<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductController extends Controller
{

    
    public function index($id)
    {
        $product = Product::with(['reviews' => function($query) {
            $query->with('user')->latest();
        }])->findOrFail($id);
        
        return view('product', compact('product'));
    }

    public function getProducts(Request $request)
    {
        $products = DB::table('products')->join(
            'categories',
            'categories.id',
            '=',
            'products.product_type'
        )->select(
            'products.id as id',
            'products.*',
            'categories.product_type as product_type'
        )->get();
        return view('admin.products', ['products' => $products]);
    }

    public function getProductById(Request $request)
    {
        $id = $request->id;
        $categories = DB::table('categories')->get();
        $product = DB::table('products')->join(
            'categories',
            'categories.id',
            '=',
            'products.product_type'
        )->select(
            'products.id as id',
            'products.*',
            'categories.product_type as product_type'
        )->where('products.id', $id)->first();

        if (!is_null($product)) {
            return view('admin.product-edit', ['categories' => $categories, 'product' => $product]);
        } else {
            return abort(404);
        }
    }

    public function editProduct(Request $request)
    {
        $product = DB::table('products')->where('id', $request->id);
        $product->update([
            'title' => $request->input('title'),
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'product_type' => $request->input('category'),
            'img' => $request->input('img'),
            'country' => $request->input('country'),
            'color' => $request->input('color'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        return redirect()->route('admin.products');
    }

    public function createProductView()
    {
        $categories = DB::table('categories')->get();
        return view('admin.product-create', ['categories' => $categories]);
    }

    public function createProduct(Request $request)
    {
        DB::table('products')->insert([
            'title' => $request->input('title'),
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'product_type' => $request->input('category'),
            'img' => $request->input('img'),
            'weight' => $request->input('weight'),
            'obiem' => $request->input('obiem'),
            'osnova' => $request->input('osnova'),
            'time' => $request->input('time'),
            'tempa' => $request->input('tempa'),
            'srok_godnosti' => $request->input('srok_godnosti'),
            'dop_info' => $request->input('dop_info'),
            'color' => $request->input('color'),
            'country' => $request->input('country'),
            'rashod' => $request->input('rashod'),
            'created_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        return redirect()->route('admin.products');
    }

    public function deleteProduct(Request $request)
    {
        $product = DB::table('products')->where('id', $request->id);
        $product->delete();
        return redirect()->route('admin.products');
    }
}
