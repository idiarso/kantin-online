<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Order::class);
    }

    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string', 'max:500'],
            'pickup_time' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.max' => 'Note cannot exceed 500 characters.',
            'pickup_time.date' => 'Invalid pickup time format.',
            'pickup_time.after' => 'Pickup time must be in the future.',
        ];
    }

    protected function prepareForValidation()
    {
        if (empty($this->pickup_time)) {
            $this->merge([
                'pickup_time' => now()->addMinutes(30),
            ]);
        }
    }
} 