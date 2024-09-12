<?php

namespace App\Http\Requests\Kids;

use App\Models\User;
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
        $user = User::firstWhere('email', $this->input('email'));
        $user_id = $user->id ?? null;
        return [
            // Users
            'media' => 'nullable|image',
            'name' =>  'required|string',
            'email' =>  'required|email:filter',
            'phone' =>  'required|string|unique:users,phone,' . $user_id,
            'city' =>  'required|string',
            'address' =>  'required|string',

            // Kids
            'kid_name' =>  'required|string',
            'gender' =>  'required|string',
            'birthdate' =>  'required|string',
            'class_id' =>  'required|integer|exists:classes,id',
            'has_medical_case' =>  'nullable|integer',

            // Parents
            'father_mobile' =>  'nullable|string',
            'father_name' =>  'nullable|string',
            'father_job' =>  'nullable|string',
            'mother_name' =>  'nullable|string',
            'mother_mobile' =>  'nullable|string',
            'mother_job' =>  'nullable|string',
            'emergency_phone' =>  'nullable|string',
            'password' => 'nullable|string'
        ];
    }
}
