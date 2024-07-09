<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'product_id' => 1,
            'product_name' => '1829',
        ]);

        Product::create([
            'product_id' => 2,
            'product_name' => '1524',
        ]);

        Product::create([
            'product_id' => 3,
            'product_name' => '1219',
        ]);

        Product::create([
            'product_id' => 4,
            'product_name' => '914',
        ]);

        Product::create([
            'product_id' => 5,
            'product_name' => '610',
        ]);

        Product::create([
            'product_id' => 6,
            'product_name' => '角',
        ]);

        Product::create([
            'product_id' => 7,
            'product_name' => 'S(妻側斜材)',
        ]);
    }
}
