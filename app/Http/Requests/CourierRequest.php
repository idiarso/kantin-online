<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('couriers')->ignore($this->courier)
            ],
            'address' => ['required', 'string', 'max:500'],
            'vehicle_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('couriers')->ignore($this->courier)
            ],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'license_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('couriers')->ignore($this->courier)
            ],
            'photo' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            'status' => ['boolean']
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The courier name is required.',
            'phone.required' => 'The phone number is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'address.required' => 'The address is required.',
            'vehicle_number.required' => 'The vehicle number is required.',
            'vehicle_number.unique' => 'This vehicle number is already registered.',
            'vehicle_type.required' => 'The vehicle type is required.',
            'license_number.required' => 'The license number is required.',
            'license_number.unique' => 'This license number is already registered.',
            'photo.required' => 'Please upload a photo.',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The photo must be a JPEG, PNG, or JPG file.',
            'photo.max' => 'The photo size must not exceed 2MB.'
        ];
    }
} 