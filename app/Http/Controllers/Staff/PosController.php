<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('status', 'available')
                  ->where('stock', '>', 0);
        }])->get();

        return view('staff.pos.index', compact('categories'));
    }

    public function scanQr(Request $request)
    {
        $qrCode = $request->qr_code;
        $user = User::where('qr_code', $qrCode)
                    ->whereIn('role', [User::ROLE_STUDENT, User::ROLE_TEACHER])
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak valid'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'balance' => $user->balance,
                'role' => $user->role
            ]
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:balance,cash'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $total = 0;

            // Calculate total and check stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }
                $total += $product->price * $item['quantity'];
            }

            // Check balance if payment method is balance
            if ($request->payment_method === 'balance' && $user->balance < $total) {
                throw new \Exception('Saldo tidak mencukupi');
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'processing',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'balance' ? 'paid' : 'pending',
                'pickup_time' => now(),
            ]);

            // Create order items and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity']
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // Process payment if using balance
            if ($request->payment_method === 'balance') {
                $user->decrement('balance', $total);
                
                // Create transaction record
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'payment',
                    'amount' => $total,
                    'description' => 'Pembayaran pesanan #' . $order->id,
                    'status' => 'completed'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Pesanan berhasil diproses'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function printReceipt(Order $order)
    {
        // Load relationships
        $order->load(['items.product', 'user']);

        return view('staff.pos.receipt', compact('order'));
    }
} 