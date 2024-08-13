<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class CreateRole extends FormRequest
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
        $userId = auth()->user()->id;

        return [
            // 'name' => 'required|string',
            // 'display_name' => 'nullable|string',
            // 'description' => 'nullable|string',

            'tags' => 'required|array',
            'tags.*' => 'required|string|distinct'
        ];
    }
}
