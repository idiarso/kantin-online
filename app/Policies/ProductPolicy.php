<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user)
    {
        return true; // Semua user bisa melihat daftar produk
    }

    public function view(User $user, Product $product)
    {
        return true; // Semua user bisa melihat detail produk
    }

    public function create(User $user)
    {
        return $user->role === 'seller'; // Hanya seller yang bisa membuat produk
    }

    public function update(User $user, Product $product)
    {
        return $user->id === $product->seller_id; // Seller hanya bisa update produknya sendiri
    }

    public function delete(User $user, Product $product)
    {
        return $user->id === $product->seller_id; // Seller hanya bisa hapus produknya sendiri
    }
} 