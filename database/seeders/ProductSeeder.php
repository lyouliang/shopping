<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => "Product 1",
            'price' => 10.00,
            'stock' => 1000,
        ]);
        DB::table('products')->insert([
            'name' => "Product 2",
            'price' => 15.00,
            'stock' => 1000,
        ]);
        DB::table('products')->insert([
            'name' => "Product 3",
            'price' => 100.00,
            'stock' => 1000,
        ]);
    }
}
