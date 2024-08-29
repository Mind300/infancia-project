<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassesRequest extends FormRequest
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
        $nursery_id = auth()->user()->nursery->id;

        // Check if the request is for an update or create
        $rules = [
            'name' => [
                'required',
                'string',
                Rule::unique('classes')
                    ->where('nursery_id', $nursery_id)
                    ->ignore($this->route('class'))
            ],
            'age_from' => 'required|integer',
            'age_to' => 'required|integer',
        ];

        return $rules;
    }
}
