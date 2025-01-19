<?php

namespace App\Http\Controllers\KantinAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\OrderStatusChanged;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('kantin-admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('kantin-admin.orders.show', compact('order'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status
        ]);

        // Broadcast event for real-time updates
        event(new OrderStatusChanged($order));

        // Send notification to user
        $order->user->notify(new OrderStatusUpdated($order));

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui',
            'order' => $order
        ]);
    }

    public function kitchen()
    {
        $pendingOrders = Order::with(['user', 'items.product'])
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('created_at')
            ->get();

        $readyOrders = Order::with(['user', 'items.product'])
            ->where('status', 'ready')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('kantin-admin.kitchen.index', compact('pendingOrders', 'readyOrders'));
    }
} 