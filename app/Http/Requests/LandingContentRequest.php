<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LandingContentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'section' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'content.text' => 'nullable|string',
            'content.button_text' => 'nullable|string|max:50',
            'content.button_link' => 'nullable|string|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|boolean'
        ];
    }
} 