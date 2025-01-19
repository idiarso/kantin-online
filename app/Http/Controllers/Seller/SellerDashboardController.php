<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $seller_id = Auth::id();
        
        $stats = [
            'total_products' => Product::where('seller_id', $seller_id)->count(),
            'active_products' => Product::where('seller_id', $seller_id)
                ->where('status', 'available')
                ->count(),
            'total_orders' => Order::whereHas('items.product', function($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })->count(),
            'pending_orders' => Order::whereHas('items.product', function($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })->where('status', 'pending')->count(),
            'recent_orders' => Order::whereHas('items.product', function($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })->with(['user', 'items.product'])->latest()->take(5)->get(),
        ];

        return view('seller.dashboard', compact('stats'));
    }
} 