<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'seller';
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'preparation_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048', // max 2MB
            'status' => 'required|in:available,unavailable',
        ];
    }
} 