<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('seller_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('status', 'active')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        $data['seller_id'] = auth()->id();
        Product::create($data);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = \App\Models\Category::where('status', 'active')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully');
    }
} 