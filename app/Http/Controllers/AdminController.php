<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get total products
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total_amount');

        // Get menu categories count
        $heavyMealsCount = Product::whereHas('category', function($query) {
            $query->where('name', 'Makanan Berat');
        })->count();

        $snacksCount = Product::whereHas('category', function($query) {
            $query->where('name', 'Makanan Ringan');
        })->count();

        $beveragesCount = Product::whereHas('category', function($query) {
            $query->where('name', 'Minuman');
        })->count();

        $dessertsCount = Product::whereHas('category', function($query) {
            $query->where('name', 'Dessert');
        })->count();

        // Get top products
        $topProducts = Product::withCount(['orderItems as total_sales' => function($query) {
            $query->whereHas('order', function($q) {
                $q->where('status', 'completed');
            });
        }])
        ->with('category')
        ->orderByDesc('total_sales')
        ->limit(5)
        ->get();

        // Get stock status counts
        $lowStockCount = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $inStockCount = Product::where('stock', '>=', 10)->count();
        $outOfStockCount = Product::where('stock', 0)->count();

        // Get last 7 days transaction data
        $lastSevenDays = collect(range(6, 0))->map(function($days) {
            return now()->subDays($days)->format('Y-m-d');
        });

        $transactionCounts = $lastSevenDays->map(function($date) {
            return Transaction::whereDate('created_at', $date)->count();
        });

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'todayTransactions',
            'todayRevenue',
            'heavyMealsCount',
            'snacksCount',
            'beveragesCount',
            'dessertsCount',
            'topProducts',
            'lowStockCount',
            'inStockCount',
            'outOfStockCount',
            'lastSevenDays',
            'transactionCounts'
        ));
    }
} 