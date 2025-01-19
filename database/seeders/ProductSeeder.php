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
        $makananBerat = Category::where('name', 'Makanan Berat')->first();
        $makananRingan = Category::where('name', 'Makanan Ringan')->first();
        $minuman = Category::where('name', 'Minuman')->first();
        $dessert = Category::where('name', 'Dessert')->first();

        // Dapatkan ID kasir untuk seller_id
        $kasir = User::where('role', 'kasir')->first();

        // Pastikan ada kasir sebelum membuat produk
        if (!$kasir) {
            throw new \Exception('Kasir user not found. Please run UserSeeder first.');
        }

        // Makanan Berat
        $products = [
            [
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng spesial dengan telur dan sayuran',
                'price' => 15000,
                'stock' => 50,
                'category_id' => $makananBerat->id,
                'seller_id' => $kasir->id
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Mie goreng dengan telur dan sayuran',
                'price' => 12000,
                'stock' => 50,
                'category_id' => $makananBerat->id,
                'seller_id' => $kasir->id
            ],
            // Makanan Ringan
            [
                'name' => 'Kentang Goreng',
                'description' => 'Kentang goreng crispy',
                'price' => 8000,
                'stock' => 30,
                'category_id' => $makananRingan->id,
                'seller_id' => $kasir->id
            ],
            [
                'name' => 'Pisang Goreng',
                'description' => 'Pisang goreng crispy',
                'price' => 5000,
                'stock' => 40,
                'category_id' => $makananRingan->id,
                'seller_id' => $kasir->id
            ],
            // Minuman
            [
                'name' => 'Es Teh',
                'description' => 'Es teh manis segar',
                'price' => 3000,
                'stock' => 100,
                'category_id' => $minuman->id,
                'seller_id' => $kasir->id
            ],
            [
                'name' => 'Es Jeruk',
                'description' => 'Es jeruk segar',
                'price' => 4000,
                'stock' => 100,
                'category_id' => $minuman->id,
                'seller_id' => $kasir->id
            ],
            // Dessert
            [
                'name' => 'Pudding',
                'description' => 'Pudding susu lembut',
                'price' => 5000,
                'stock' => 20,
                'category_id' => $dessert->id,
                'seller_id' => $kasir->id
            ],
            [
                'name' => 'Es Krim',
                'description' => 'Es krim aneka rasa',
                'price' => 6000,
                'stock' => 30,
                'category_id' => $dessert->id,
                'seller_id' => $kasir->id
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 