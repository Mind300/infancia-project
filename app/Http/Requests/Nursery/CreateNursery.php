<?php

namespace App\Http\Requests\Nursery;

use Illuminate\Foundation\Http\FormRequest;

class CreateNursery extends FormRequest
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
            'email' => 'required|email:filter|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string',
            'name' => 'required|string|min:3|max:50',
            'province' => 'required|string|unique:users,phone',
            'address' => 'required|string',
            'branches_number' => 'required|integer',
            'kids_number' => 'required|integer',
            'employees_number' => 'required|integer',
            'about' => 'nullable|string',
        ];
    }
}
