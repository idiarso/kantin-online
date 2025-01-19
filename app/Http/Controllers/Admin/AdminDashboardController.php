<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get today's date
        $today = Carbon::today();

        // Main Statistics
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayRevenue = Transaction::whereDate('created_at', $today)->sum('total_amount');

        // Menu Categories Count
        $heavyMealsCount = Product::whereHas('category', function($q) {
            $q->where('name', 'Makanan Berat');
        })->count();

        $snacksCount = Product::whereHas('category', function($q) {
            $q->where('name', 'Snack');
        })->count();

        $beveragesCount = Product::whereHas('category', function($q) {
            $q->where('name', 'Minuman');
        })->count();

        $dessertsCount = Product::whereHas('category', function($q) {
            $q->where('name', 'Dessert');
        })->count();

        // Top Products
        $topProducts = Product::withCount(['transactionItems as total_sales' => function($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])
        ->orderByDesc('total_sales')
        ->take(5)
        ->get();

        // Stock Status
        $lowStockCount = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $inStockCount = Product::where('stock', '>=', 10)->count();
        $outOfStockCount = Product::where('stock', 0)->count();

        // Transaction Chart Data (Last 7 Days)
        $lastWeekTransactions = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $chartData = [
            'labels' => $lastWeekTransactions->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d M');
            }),
            'transactions' => $lastWeekTransactions->pluck('count'),
            'revenue' => $lastWeekTransactions->pluck('total')
        ];

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
            'chartData'
        ));
    }
} 