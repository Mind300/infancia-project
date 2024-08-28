<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Laratrust\Models\Team;

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
        $user_name = auth()->user()->name;
        $team = Team::firstWhere('name', $user_name.'Team');
        $team_id = $team->id;

        return [
            'name' => 'required|string|unique:roles,name,' . $team_id,
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*.name' => 'required|string'
        ];
    }
}
