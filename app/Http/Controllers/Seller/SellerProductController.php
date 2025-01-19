<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends Controller
{
    public function index()
    {
        $products = Product::where('seller_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);
            
        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'preparation_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048', // max 2MB
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['seller_id'] = Auth::id();

        Product::create($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::where('status', 'active')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'preparation_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully');
    }
} 