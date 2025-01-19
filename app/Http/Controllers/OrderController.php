<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('status', 'available')
            ->where('stock', '>', 0)
            ->get();
        return view('orders.create', compact('products'));
    }

    public function store(OrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => 0,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'pickup_time' => $request->pickup_time,
                'note' => $request->note
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity']
                ]);

                $product->decrement('stock', $item['quantity']);
                $total += $product->price * $item['quantity'];
            }

            $order->update(['total_amount' => $total]);

            DB::commit();
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}
