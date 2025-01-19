<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return in_array($this->user()->role, ['student', 'teacher']);
    }

    public function rules()
    {
        return [
            'pickup_time' => 'required|date|after:now',
            'note' => 'nullable|string|max:500',
        ];
    }
} 