<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
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
        $rules = [
            'title' => [
                'required',
                'string',
                Rule::unique('subjects')
                    ->where('nursery_id', $nursery_id)
                    ->ignore($this->route('subject'))
            ],
        ];

        return $rules;
    }
}
