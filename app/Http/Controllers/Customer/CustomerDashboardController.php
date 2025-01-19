<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        $stats = [
            'balance' => Auth::user()->balance,
            'total_orders' => Order::where('user_id', $user_id)->count(),
            'pending_orders' => Order::where('user_id', $user_id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'recent_orders' => Order::where('user_id', $user_id)
                ->with(['items.product'])
                ->latest()
                ->take(5)
                ->get(),
            'recent_transactions' => Transaction::where('user_id', $user_id)
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('customer.dashboard', compact('stats'));
    }
} 