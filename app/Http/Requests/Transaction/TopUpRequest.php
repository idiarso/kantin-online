<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TopUpRequest extends FormRequest
{
    public function authorize()
    {
        return in_array($this->user()->role, ['student', 'teacher']);
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:10000|max:1000000',
            'payment_proof' => 'required|image|max:2048', // max 2MB
        ];
    }
} 