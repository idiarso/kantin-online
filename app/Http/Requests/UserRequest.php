<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                $this->user ? Rule::unique('users')->ignore($this->user->id) : 'unique:users'
            ],
            'role' => ['required', Rule::in(['admin', 'teacher', 'student', 'parent'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];

        if ($this->isMethod('POST') || $this->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        // Additional fields based on role
        if ($this->input('role') === 'student') {
            $rules['class'] = ['required', 'string', 'max:50'];
            $rules['student_id'] = ['required', 'string', 'max:50', Rule::unique('users', 'student_id')->ignore($this->user)];
        }

        if ($this->input('role') === 'teacher') {
            $rules['employee_id'] = ['required', 'string', 'max:50', Rule::unique('users', 'employee_id')->ignore($this->user)];
            $rules['subject'] = ['required', 'string', 'max:100'];
        }

        if ($this->input('role') === 'parent') {
            $rules['phone'] = ['required', 'string', 'max:20'];
            $rules['address'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'role.required' => 'Role is required',
            'role.in' => 'Invalid role selected',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
            'class.required' => 'Class is required for students',
            'student_id.required' => 'Student ID is required',
            'student_id.unique' => 'This Student ID is already taken',
            'employee_id.required' => 'Employee ID is required for teachers',
            'employee_id.unique' => 'This Employee ID is already taken',
            'subject.required' => 'Subject is required for teachers',
            'phone.required' => 'Phone number is required for parents',
            'address.required' => 'Address is required for parents',
        ];
    }
} 