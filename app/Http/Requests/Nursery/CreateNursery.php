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
        $nursery_id = auth()->user()->nurery->id ?? null;
        return [
            'media' => 'nullable|image',
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email:filter|unique:users,email|unique:nurseries,email,' . $nursery_id,
            'phone' => 'required|string|unique:users,phone|unique:nurseries,phone',
            'country' => 'sometimes|string',
            'city' => 'sometimes|string',
            'province' => 'sometimes|string',
            'address' => 'required|string',
            'branches_number' => 'required|integer',
            'start_fees' => 'nullable',
            'classes_number' => 'required|integer',
            'children_number' => 'required|integer',
            'employees_number' => 'required|integer',
            'services' => 'nullable|string',
            'about' => 'nullable|string',
            'password' => 'sometimes|string'
        ];
    }
}
