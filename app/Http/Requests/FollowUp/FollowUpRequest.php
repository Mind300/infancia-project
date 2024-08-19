<?php

namespace App\Http\Requests\FollowUp;

use Illuminate\Foundation\Http\FormRequest;

class FollowUpRequest extends FormRequest
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
            'meals.*.meal_id' => 'sometimes|integer',
            'meals.*.type' => 'sometimes|string',
            'meals.*.amount' => 'sometimes|numeric',
            'meals.*.kid_id' => 'required|integer',

            'mood' => 'sometimes|integer', 
            'napping' => 'sometimes',
            'comment' => 'nullable|string',
            'diaper' => 'nullable|integer',
            'potty' => 'nullable|integer',
            'toilet' => 'nullable|integer',
            'kid_id' => 'required|integer',
        ];
    }
}
