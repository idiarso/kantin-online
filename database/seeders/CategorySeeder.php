<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Makanan Berat',
                'description' => 'Menu makanan berat seperti nasi dan mie',
                'status' => true,
            ],
            [
                'name' => 'Snack',
                'description' => 'Menu makanan ringan dan cemilan',
                'status' => true,
            ],
            [
                'name' => 'Minuman',
                'description' => 'Menu minuman dingin dan panas',
                'status' => true,
            ],
            [
                'name' => 'Dessert',
                'description' => 'Menu makanan penutup dan pencuci mulut',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            if (!Category::where('name', $category['name'])->exists()) {
                $category['slug'] = Str::slug($category['name']);
                Category::create($category);
            }
        }
    }
} 