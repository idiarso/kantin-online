<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isKantinAdmin();
    }

    public function rules()
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'preparation_time' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable'
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Kategori harus dipilih',
            'name.required' => 'Nama menu harus diisi',
            'price.required' => 'Harga harus diisi',
            'price.min' => 'Harga tidak boleh negatif',
            'stock.required' => 'Stok harus diisi',
            'stock.min' => 'Stok tidak boleh negatif',
            'preparation_time.required' => 'Waktu persiapan harus diisi',
            'image.required' => 'Gambar harus diunggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }
} 