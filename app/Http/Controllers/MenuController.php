<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('category_id')
            ->get();
            
        $categories = Category::where('status', 'active')->get();

        return view('menu.index', compact('products', 'categories'));
    }
} 