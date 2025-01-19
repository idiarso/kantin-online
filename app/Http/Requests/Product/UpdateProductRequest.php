<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'seller' && 
               $this->user()->id === $this->product->seller_id;
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
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:available,unavailable',
        ];
    }
} 