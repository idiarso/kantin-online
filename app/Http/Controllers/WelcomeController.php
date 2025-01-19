<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('status', 'available')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $categories = Category::withCount('products')
            ->where('status', 'active')
            ->get();

        return view('welcome', compact('featuredProducts', 'categories'));
    }
} 