<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50|unique:users,name',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'name is required.',
            'name.string'   => 'name must be a valid string.',
            'name.min'      => 'name must be at least 3 characters.',
            'name.max'      => 'name may not be greater than 50 characters.',
            'name.unique'   => 'This name is already taken.',

            'email.required'    => 'Email is required.',
            'email.email'       => 'Please provide a valid email.',
            'email.unique'      => 'This email is already registered.',

            'password.required' => 'Password is required.',
            'password.string'   => 'Password must be a valid string.',
            'password.min'      => 'Password must be at least 6 characters.'
        ];
    }
}
