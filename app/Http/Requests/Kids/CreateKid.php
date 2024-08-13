<?php

namespace App\Http\Requests\Kids;

use Illuminate\Foundation\Http\FormRequest;

class CreateKid extends FormRequest
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
            // Users
            'name' =>  'required|string',
            'email' =>  'required|email:filter|unique:users,email',
            'phone' =>  'required|string|unique:users,phone',
            'city' =>  'required|string',
            'address' =>  'required|string',

            // Kids
            'kid_name' =>  'required|string',
            'gender' =>  'required|string',
            'birthdate' =>  'required|string',
            'class_id' =>  'required|integer',
            'has_medical_case' =>  'required|integer',

            // Parents
            'father_mobile' =>  'required|string',
            'father_name' =>  'required|string',
            'father_job' =>  'required|string',
            'mother_name' =>  'required|string',
            'mother_mobile' =>  'required|string',
            'mother_job' =>  'required|string',
            'emergency_phone' =>  'required|string',
            'password' => 'sometimes|string'
        ];
    }
}
