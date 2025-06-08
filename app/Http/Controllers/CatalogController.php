<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function getProducts(Request $request)
    {
        // Получаем выбранную категорию (если есть)
        $selectedCategory = $request->get('category');

        // Получаем все уникальные страны из базы (исключаем пустые)
        $countries = Product::distinct()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->pluck('country')
            ->toArray();
        
        // Получаем все уникальные основы (исключаем пустые) и фильтруем по категории, если выбрана
        $basesQuery = Product::distinct()
            ->whereNotNull('osnova')
            ->where('osnova', '!=', '');
        if ($selectedCategory) {
            $basesQuery->where('product_type', $selectedCategory);
        }
        $bases = $basesQuery->pluck('osnova')->toArray();
        
        // Получаем все уникальные цвета (исключаем пустые) и фильтруем по категории, если выбрана
        $colorsQuery = Product::distinct()
            ->whereNotNull('color')
            ->where('color', '!=', '');
        if ($selectedCategory) {
            $colorsQuery->where('product_type', $selectedCategory);
        }
        $colors = $colorsQuery->pluck('color')->toArray();
        
        // Получаем все уникальные веса/объемы (исключаем пустые и сортируем) и фильтруем по категории, если выбрана
        $weightsQuery = Product::distinct()
            ->where(function($query) {
                $query->whereNotNull('weight')
                      ->where('weight', '!=', '')
                      ->where('weight', '!=', '0')
                      ->orWhere(function($q) {
                          $q->whereNotNull('obiem')
                            ->where('obiem', '!=', '')
                            ->where('obiem', '!=', '0');
                      });
            });
        if ($selectedCategory) {
            $weightsQuery->where('product_type', $selectedCategory);
        }

        $weights = $weightsQuery->select(DB::raw('COALESCE(NULLIF(weight, ""), NULLIF(obiem, "")) as measure'))
            ->pluck('measure')
            ->filter()
            ->unique()
            ->values()
            ->map(function($item) {
                // Извлекаем числовое значение и единицу измерения
                preg_match('/(\d+(?:\.\d+)?)(кг|г|мл)/u', $item, $matches);
                if (count($matches) >= 3) {
                    $value = floatval($matches[1]);
                    $unit = $matches[2];
                    
                    // Преобразуем все в граммы для сортировки
                    if ($unit === 'кг') {
                        $value *= 1000;
                    }
                    
                    return [
                        'original' => $item,
                        'value' => $value
                    ];
                }
                return null;
            })
            ->filter()
            ->sortBy('value')
            ->pluck('original')
            ->toArray();

        // Получаем все уникальные значения температуры применения и фильтруем по категории, если выбрана
        $tempasQuery = Product::distinct()
            ->whereNotNull('tempa')
            ->where('tempa', '!=', '');
        if ($selectedCategory) {
            $tempasQuery->where('product_type', $selectedCategory);
        }
        $tempas = $tempasQuery->pluck('tempa')->toArray();

        // Получаем все уникальные значения времени застывания и фильтруем по категории, если выбрана
        $timesQuery = Product::distinct()
             ->whereNotNull('time')
             ->where('time', '!=', '');
         if ($selectedCategory) {
             $timesQuery->where('product_type', $selectedCategory);
         }
        $times = $timesQuery->pluck('time')->toArray();

        // ID категорий инструментов
        $toolCategories = [3]; // ID категории "Инструменты"

        // Применяем фильтры
        $query = Product::query();

        if ($category = $request->get('category')) {
            $query->where('product_type', $category);
        }

        if ($country = $request->get('country')) {
            $query->where('country', $country);
        }

        if ($priceFrom = $request->get('price_from')) {
            $query->where('price', '>=', $priceFrom);
        }

        if ($priceTo = $request->get('price_to')) {
            $query->where('price', '<=', $priceTo);
        }

        if ($base = $request->get('base')) {
            $query->where('osnova', $base);
        }

        if ($color = $request->get('color')) {
            $query->where('color', $color);
        }

        if ($weight = $request->get('weight')) {
            $query->where(function($q) use ($weight) {
                $q->where('weight', $weight)
                  ->orWhere('obiem', $weight);
            });
        }

        // Поиск по производителю
        if ($manufacturer = $request->get('manufacturer')) {
            $query->where('title', 'LIKE', '%' . $manufacturer . '%');
        }

        // Сортировка
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'title':
                    $query->orderBy('title');
                    break;
                case 'price':
                    $query->orderBy('price');
                    break;
                case 'rashod':
                    $query->orderBy('rashod');
                    break;
            }
        }

        $products = $query->paginate(9);
        
        // Добавляем все параметры запроса к пагинации
        $products->appends($request->except('page'));
        
        $categories = Category::all();

        return view('catalog', compact(
            'products',
            'categories',
            'countries',
            'bases',
            'colors',
            'weights',
            'toolCategories',
            'tempas',
            'times'
        ));
    }
}

