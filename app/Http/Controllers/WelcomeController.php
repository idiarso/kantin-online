<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
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

        $banners = Setting::get('landing_banners', []);
        $content = [
            'title' => Setting::get('landing_title', 'E-Kantin - Kantin Digital Sekolah'),
            'description' => Setting::get('landing_description', 'Nikmati berbagai pilihan makanan dan minuman segar dengan pemesanan yang mudah dan cepat'),
            'opening_hours' => Setting::get('landing_opening_hours', 'Senin - Jumat: 07:00 - 16:00'),
            'contact_email' => Setting::get('landing_contact_email', 'kantin@sekolah.com'),
            'contact_phone' => Setting::get('landing_contact_phone', '(021) 123-4567'),
            'address' => Setting::get('landing_address', 'Gedung Sekolah, Lantai 1'),
        ];

        return view('welcome', compact('featuredProducts', 'categories', 'banners', 'content'));
    }
} 