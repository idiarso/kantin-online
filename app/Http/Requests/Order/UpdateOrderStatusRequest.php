<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'seller' || $this->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'status' => 'required|in:processing,ready,completed,cancelled'
        ];
    }
} 