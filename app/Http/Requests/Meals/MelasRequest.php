<?php

namespace App\Http\Requests\Meals;

use Illuminate\Foundation\Http\FormRequest;

class MelasRequest extends FormRequest
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
            'meals' => 'sometimes|array',
            'meals.*.days' => 'sometimes|string',
            'meals.*.type' => 'sometimes|string',
            'meals.*.description' => 'sometimes|string',
            'meals.*.class_id' => 'required|integer',
            'class_id' => 'required|integer',
        ];
    }
}
