<?php

namespace App\Http\Controllers\KantinAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\MenuRequest;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return view('kantin-admin.menu.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('kantin-admin.menu.create', compact('categories'));
    }

    public function store(MenuRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['seller_id'] = auth()->id();
        Product::create($data);

        return redirect()->route('kantin.admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::all();
        return view('kantin-admin.menu.edit', compact('product', 'categories'));
    }

    public function update(MenuRequest $request, Product $product)
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
        return redirect()->route('kantin.admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('kantin.admin.menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }

    public function updateStock(Product $product, Request $request)
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product->update(['stock' => $request->stock]);
        
        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui'
        ]);
    }

    public function updateStatus(Product $product)
    {
        $this->authorize('update', $product);
        
        $product->update([
            'status' => $product->status === 'available' ? 'unavailable' : 'available'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'status' => $product->status
        ]);
    }
} 