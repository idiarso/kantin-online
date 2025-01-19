<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            return;
        }

        // Get admin user as seller
        $seller = User::where('role', 'admin')->first();
        if (!$seller) {
            return;
        }

        $makananBerat = $categories->where('name', 'Makanan Berat')->first();
        if ($makananBerat) {
            $products = [
                [
                    'name' => 'Nasi Goreng',
                    'description' => 'Nasi goreng spesial dengan telur dan sayuran',
                    'price' => 15000,
                    'stock' => rand(10, 50),
                    'preparation_time' => 10,
                    'status' => true,
                ],
                [
                    'name' => 'Mie Goreng',
                    'description' => 'Mie goreng dengan telur dan sayuran',
                    'price' => 12000,
                    'stock' => rand(10, 50),
                    'preparation_time' => 8,
                    'status' => true,
                ],
                [
                    'name' => 'Nasi Uduk',
                    'description' => 'Nasi uduk dengan telur dan tempe',
                    'price' => 10000,
                    'stock' => rand(10, 50),
                    'preparation_time' => 5,
                    'status' => true,
                ],
            ];

            foreach ($products as $product) {
                if (!Product::where('name', $product['name'])->exists()) {
                    Product::create(array_merge($product, [
                        'category_id' => $makananBerat->id,
                        'seller_id' => $seller->id,
                    ]));
                }
            }
        }

        $snack = $categories->where('name', 'Snack')->first();
        if ($snack) {
            $products = [
                [
                    'name' => 'Kentang Goreng',
                    'description' => 'Kentang goreng crispy',
                    'price' => 8000,
                    'stock' => rand(20, 100),
                    'preparation_time' => 5,
                    'status' => true,
                ],
                [
                    'name' => 'Pisang Goreng',
                    'description' => 'Pisang goreng crispy',
                    'price' => 5000,
                    'stock' => rand(20, 100),
                    'preparation_time' => 3,
                    'status' => true,
                ],
                [
                    'name' => 'Roti Bakar',
                    'description' => 'Roti bakar dengan berbagai topping',
                    'price' => 7000,
                    'stock' => rand(20, 100),
                    'preparation_time' => 5,
                    'status' => true,
                ],
            ];

            foreach ($products as $product) {
                if (!Product::where('name', $product['name'])->exists()) {
                    Product::create(array_merge($product, [
                        'category_id' => $snack->id,
                        'seller_id' => $seller->id,
                    ]));
                }
            }
        }

        $minuman = $categories->where('name', 'Minuman')->first();
        if ($minuman) {
            $products = [
                [
                    'name' => 'Es Teh',
                    'description' => 'Es teh manis segar',
                    'price' => 3000,
                    'stock' => rand(50, 200),
                    'preparation_time' => 2,
                    'status' => true,
                ],
                [
                    'name' => 'Es Jeruk',
                    'description' => 'Es jeruk segar',
                    'price' => 4000,
                    'stock' => rand(50, 200),
                    'preparation_time' => 2,
                    'status' => true,
                ],
                [
                    'name' => 'Kopi',
                    'description' => 'Kopi hitam/kopi susu',
                    'price' => 5000,
                    'stock' => rand(50, 200),
                    'preparation_time' => 3,
                    'status' => true,
                ],
            ];

            foreach ($products as $product) {
                if (!Product::where('name', $product['name'])->exists()) {
                    Product::create(array_merge($product, [
                        'category_id' => $minuman->id,
                        'seller_id' => $seller->id,
                    ]));
                }
            }
        }

        $dessert = $categories->where('name', 'Dessert')->first();
        if ($dessert) {
            $products = [
                [
                    'name' => 'Es Krim',
                    'description' => 'Es krim aneka rasa',
                    'price' => 5000,
                    'stock' => rand(30, 150),
                    'preparation_time' => 1,
                    'status' => true,
                ],
                [
                    'name' => 'Puding',
                    'description' => 'Puding susu aneka rasa',
                    'price' => 4000,
                    'stock' => rand(30, 150),
                    'preparation_time' => 1,
                    'status' => true,
                ],
                [
                    'name' => 'Pancake',
                    'description' => 'Pancake dengan sirup maple',
                    'price' => 8000,
                    'stock' => rand(30, 150),
                    'preparation_time' => 5,
                    'status' => true,
                ],
            ];

            foreach ($products as $product) {
                if (!Product::where('name', $product['name'])->exists()) {
                    Product::create(array_merge($product, [
                        'category_id' => $dessert->id,
                        'seller_id' => $seller->id,
                    ]));
                }
            }
        }
    }
} 