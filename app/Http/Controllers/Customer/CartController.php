<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $products = [];
        $total = 0;

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)
                ->where('status', 'available')
                ->get();

            foreach ($products as $product) {
                $total += $product->price * $cart[$product->id];
            }
        }

        return view('customer.cart.index', compact('products', 'cart', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        if ($product->status !== 'available' || $product->stock < $validated['quantity']) {
            return back()->with('error', 'Product is not available in requested quantity');
        }

        $cart = Session::get('cart', []);
        
        // If product exists in cart, add quantity
        if (isset($cart[$validated['product_id']])) {
            $cart[$validated['product_id']] += $validated['quantity'];
        } else {
            $cart[$validated['product_id']] = $validated['quantity'];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Product added to cart');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Session::get('cart', []);

        if ($validated['quantity'] > 0) {
            $cart[$validated['product_id']] = $validated['quantity'];
        } else {
            unset($cart[$validated['product_id']]);
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Cart updated successfully');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = Session::get('cart', []);
        unset($cart[$validated['product_id']]);
        Session::put('cart', $cart);

        return back()->with('success', 'Product removed from cart');
    }
} 