<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::whereHas('items.product', function ($query) {
                $query->where('seller_id', auth()->id());
            })
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items.product']);
        
        // Filter items to only show products belonging to the current seller
        $sellerItems = $order->items->filter(function ($item) {
            return $item->product->seller_id === auth()->id();
        });

        return view('seller.orders.show', compact('order', 'sellerItems'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['processing', 'ready', 'completed'])],
        ]);

        // Only update if the new status is valid for the current status
        $validTransitions = [
            'pending' => ['processing'],
            'processing' => ['ready'],
            'ready' => ['completed'],
        ];

        if (!isset($validTransitions[$order->status]) || 
            !in_array($validated['status'], $validTransitions[$order->status])) {
            return back()->with('error', 'Invalid status transition.');
        }

        $order->update($validated);

        // If order is completed, update product stock
        if ($validated['status'] === 'completed') {
            foreach ($order->items as $item) {
                if ($item->product->seller_id === auth()->id()) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::whereHas('items.product', function ($query) {
                $query->where('seller_id', auth()->id());
            })->count(),

            'pending_orders' => Order::whereHas('items.product', function ($query) {
                $query->where('seller_id', auth()->id());
            })->pending()->count(),

            'completed_orders' => Order::whereHas('items.product', function ($query) {
                $query->where('seller_id', auth()->id());
            })->completed()->count(),

            'total_sales' => Order::whereHas('items.product', function ($query) {
                $query->where('seller_id', auth()->id());
            })->completed()->sum('total_amount'),

            'recent_orders' => Order::whereHas('items.product', function ($query) {
                $query->where('seller_id', auth()->id());
            })
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get(),

            'top_products' => Product::where('seller_id', auth()->id())
                ->withCount(['orderItems'])
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('seller.dashboard', compact('stats'));
    }
} 