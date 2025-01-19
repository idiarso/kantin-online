<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty');
        }

        $validated = $request->validate([
            'pickup_time' => 'required|date|after:now',
            'note' => 'nullable|string|max:500'
        ]);

        $products = Product::whereIn('id', array_keys($cart))->get();
        $total = 0;

        // Calculate total and check stock
        foreach ($products as $product) {
            if ($product->stock < $cart[$product->id]) {
                return back()->with('error', "Not enough stock for {$product->name}");
            }
            $total += $product->price * $cart[$product->id];
        }

        // Check user balance
        if (Auth::user()->balance < $total) {
            return back()->with('error', 'Insufficient balance');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => 'balance',
                'payment_status' => 'paid',
                'pickup_time' => $validated['pickup_time'],
                'note' => $validated['note'],
            ]);

            // Create order items and update stock
            foreach ($products as $product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $cart[$product->id],
                    'price' => $product->price,
                    'subtotal' => $product->price * $cart[$product->id],
                ]);

                $product->decrement('stock', $cart[$product->id]);
            }

            // Create transaction and update user balance
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'payment',
                'amount' => $total,
                'description' => "Payment for order #{$order->id}",
                'status' => 'completed',
            ]);

            Auth::user()->decrement('balance', $total);

            DB::commit();

            // Clear cart
            Session::forget('cart');

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order placed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product']);
        return view('customer.orders.show', compact('order'));
    }
} 