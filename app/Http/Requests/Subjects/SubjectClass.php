<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectClass extends FormRequest
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
            'subject_id' => ['required', 'integer'],
            'class_id' => [
                'required',
                'integer',
                Rule::unique('subjects_classes')->where(function ($query) {
                    return $query->where('subject_id', $this->input('subject_id'))
                                 ->where('class_id', $this->input('class_id'));
                })
            ],
        ];
    }
}
