<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('product'));
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'preparation_time' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'], // max 2MB
            'status' => ['required', Rule::in(['available', 'unavailable'])],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'name.required' => 'Please enter the product name.',
            'price.required' => 'Please enter the product price.',
            'price.min' => 'Price cannot be negative.',
            'stock.required' => 'Please enter the stock amount.',
            'stock.min' => 'Stock cannot be negative.',
            'preparation_time.required' => 'Please enter the preparation time.',
            'preparation_time.min' => 'Preparation time cannot be negative.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image size cannot exceed 2MB.',
            'status.required' => 'Please select the product status.',
            'status.in' => 'Invalid status selected.',
        ];
    }
} 