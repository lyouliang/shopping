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
        $products = [
            [
                "name" => "Black Spiral T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "blackSpiralT"
            ],
            [
                "name" => "Blue T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "blueT"
            ],
            [
                "name" => "Red dress",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "dress"
            ],
            [
                "name" => "Green T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "greenT"
            ],
            [
                "name" => "Green Hoodie",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "hoodie"
            ],
            [
                "name" => "Black Jacket",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "jacket"
            ],
            [
                "name" => "Long Jeans",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "jeans"
            ],
            [
                "name" => "Purple T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "purpleT"
            ],
            [
                "name" => "Rainbow Birthday T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "rainbowBirthdayT"
            ],
            [
                "name" => "Rainbow T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "rainbowT"
            ],
            [
                "name" => "Red T",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "redT"
            ],
            [
                "name" => "Jogging Shoes",
                "price" => rand(20, 150),
                "stock" => rand(100, 500),
                "created_at" => now(),
                "updated_at" => now(),
                "image_path" => "shoes"
            ],
        ];

        foreach($products as $product) {
            DB::table('products')->insert(
                [
                    "name" => $product["name"],
                    "price" => $product["price"],
                    "stock" => $product["stock"],
                    "created_at" => $product["created_at"],
                    "updated_at" => $product["updated_at"],
                    "image_path" => $product["image_path"],
                ]
                );
        }
    }
}
