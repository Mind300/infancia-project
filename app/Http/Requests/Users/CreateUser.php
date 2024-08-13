<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
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
            // 'image' => 'required|image',
            'name' => 'required|string|min:3|max:25',
            'email' => 'required|email:filter|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string'
        ];
    }
}
