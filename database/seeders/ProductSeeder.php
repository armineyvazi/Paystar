<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'iphone 14',
            'price' => 50000,
            'description' => 'Iphone 14  colors: gray ,blue',
        ]);
    }
}
