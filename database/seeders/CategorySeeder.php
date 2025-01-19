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
                'description' => 'Nasi dan lauk pauk'
            ],
            [
                'name' => 'Makanan Ringan',
                'description' => 'Snack dan cemilan'
            ],
            [
                'name' => 'Minuman',
                'description' => 'Aneka minuman segar'
            ],
            [
                'name' => 'Dessert',
                'description' => 'Makanan penutup'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description']
            ]);
        }
    }
} 