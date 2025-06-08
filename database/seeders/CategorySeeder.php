<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'product_type' => 'Сухие смеси'],
            ['id' => 2, 'product_type' => 'Клеевая продукция'],
            ['id' => 3, 'product_type' => 'Лакокрасочные материалы'],
            ['id' => 4, 'product_type' => 'Герметики'],
            ['id' => 5, 'product_type' => 'Пропитки'],
        ]);
    }
}
