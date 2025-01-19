<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => User::where('role', 'seller')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_transactions' => Transaction::count(),
            'recent_orders' => Order::with(['user', 'items'])->latest()->take(5)->get(),
            'recent_transactions' => Transaction::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 