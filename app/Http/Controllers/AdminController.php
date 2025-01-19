<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_sales' => Order::where('status', 'completed')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_users' => User::count(),
        ];

        $recent_orders = Order::with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        $popular_products = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'popular_products'));
    }
} 