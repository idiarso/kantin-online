<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => Order::completed()->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_users' => User::count(),
            
            'recent_orders' => Order::with(['user', 'items.product'])
                ->latest()
                ->take(5)
                ->get(),
                
            'top_products' => Product::withCount(['orderItems'])
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get(),
                
            'sales_by_category' => Product::with('category')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.status', 'completed')
                ->selectRaw('categories.name, sum(order_items.subtotal) as total')
                ->groupBy('categories.name')
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 