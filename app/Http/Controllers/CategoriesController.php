<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function getCategories()
    {
        $categories = DB::table('categories')->get();
        return view('admin.categories', ['categories' => $categories]);
    }

    public function createCategoryView()
    {
        $categories = DB::table('categories')->get();
        return view('admin.category-create', ['categoties' => $categories]);
    }

    public function createCategory(Request $request)
    {
        DB::table('categories')->insert([
            'product_type' => $request->input('product_type')
        ]);
        return redirect()->route('admin.categories');
    }

    public function getCategoryById(Request $request)
    {
        $category = DB::table('categories')->where('id', $request->id)->first();

        if (!is_null($category)) {
            return view('admin.category-edit', ['category' => $category]);
        } else {
            return abort(404);
        }
    }

    public function editCategory(Request $request)
    {
        $category = DB::table('categories')->where('id', $request->id);

        $category->update([
            'product_type' => $request->input('title'),
        ]);
        return redirect()->route('admin.categories');
    }

    public function deleteCategory(Request $request)
    {
        $category = DB::table('categories')->where('id', $request->id);
        $productsForDelete = DB::table('products')->where('product_type', $request->id);
        foreach ($productsForDelete->select('id')->get() as $value) {
            DB::table('cart')->where('product_id', $value->id)->delete();
            DB::table('order_items')->where('product_id', $value->id)->delete();
        }
        $productsForDelete->delete();
        $category->delete();

        return redirect()->route('admin.categories');
    }
}
