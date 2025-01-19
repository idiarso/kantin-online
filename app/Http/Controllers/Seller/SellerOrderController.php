<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::whereHas('items.product', function($query) {
            $query->where('seller_id', Auth::id());
        })->with(['user', 'items.product'])
          ->latest()
          ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Verify seller owns products in this order
        $hasProducts = $order->items()->whereHas('product', function($query) {
            $query->where('seller_id', Auth::id());
        })->exists();

        if (!$hasProducts) {
            abort(403);
        }

        $order->load(['user', 'items.product']);
        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,ready,completed,cancelled'
        ]);

        // Verify seller owns products in this order
        $hasProducts = $order->items()->whereHas('product', function($query) {
            $query->where('seller_id', Auth::id());
        })->exists();

        if (!$hasProducts) {
            abort(403);
        }

        $order->update($validated);

        return redirect()->route('seller.orders.show', $order)
            ->with('success', 'Order status updated successfully');
    }
} 