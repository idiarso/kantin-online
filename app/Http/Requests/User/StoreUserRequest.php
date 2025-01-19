<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,seller,student,teacher',
            'phone' => 'required|string|max:15',
            'class' => 'nullable|string|max:20|required_if:role,student',
            'balance' => 'nullable|numeric|min:0',
        ];
    }
} 