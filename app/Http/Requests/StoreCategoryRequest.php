<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Category::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:1024'], // max 1MB
            'status' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the category name.',
            'name.max' => 'Category name cannot exceed 255 characters.',
            'icon.image' => 'The file must be an image.',
            'icon.max' => 'The icon size cannot exceed 1MB.',
            'status.required' => 'Please select the category status.',
            'status.boolean' => 'Invalid status value.',
        ];
    }
} 