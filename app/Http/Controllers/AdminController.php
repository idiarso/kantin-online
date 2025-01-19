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
            'orders' => Order::count(),
            'products' => Product::count(),
            'users' => User::count(),
            'revenue' => Order::where('status', 'completed')->sum('total_amount')
        ];

        $recent_orders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
} 