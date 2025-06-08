<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        $products = Product::whereNotIn('product_type', [3])->get(); 
        return view('calculator', compact('products'));
    }

    public function calculate(Request $request)
    {
        $product = Product::find($request->product_id);
        $area = floatval($request->area);
        $unitType = $request->unit_type ?? 'm2';
        
        if (!$product || $area <= 0) {
            return response()->json(['error' => 'Неверные данные'], 400);
        }

      
        if ($product->product_type == 3) {
            return response()->json([
                'product_name' => $product->title,
                'quantity' => 1,
                'total_price' => $product->price,
                'unit' => 'шт.'
            ]);
        }

        
        if ($product->product_type == 4) {
            
            $lengthInMeters = $this->convertToMeters($area, $unitType);
            $quantity = ceil($lengthInMeters * floatval($product->rashod));
            
            return response()->json([
                'product_name' => $product->title,
                'quantity' => $quantity,
                'total_price' => $quantity * $product->price,
                'unit' => 'шт.'
            ]);
        }

       
        $areaInSquareMeters = $this->convertToSquareMeters($area, $unitType);
        $quantity = ceil($areaInSquareMeters * floatval($product->rashod));
        
        return response()->json([
            'product_name' => $product->title,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price,
            'unit' => 'кг'
        ]);
    }

    private function convertToMeters($value, $unitType)
    {
        switch($unitType) {
            case 'cm':
                return $value / 100;
            case 'm2':
                return sqrt($value); 
            case 'm':
            default:
                return $value; 
        }
    }

    private function convertToSquareMeters($value, $unitType)
    {
        switch($unitType) {
            case 'cm':
                return ($value / 100) * ($value / 100); 
            case 'm':
                return $value * $value; 
            case 'm2':
            default:
                return $value; 
        }
    }
} 