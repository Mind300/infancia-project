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
            'media' => 'sometimes|image',
            'name' =>  'required|string',
            'email' =>  'required|email:filter',
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
            'father_mobile' =>  'sometimes|string',
            'father_name' =>  'sometimes|string',
            'father_job' =>  'sometimes|string',
            'mother_name' =>  'sometimes|string',
            'mother_mobile' =>  'sometimes|string',
            'mother_job' =>  'sometimes|string',
            'emergency_phone' =>  'sometimes|string',
            'password' => 'sometimes|string'
        ];
    }
}
