<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateUser extends FormRequest
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
        // Retrieve the user ID directly from the route parameter
        $userId = $this->route('user');

        return [
            'image' => 'required|image',
            'name' => 'required|string|min:3|max:25',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'required|unique:users,phone,' . $userId,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ];
    }
}
