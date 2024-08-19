<?php

namespace App\Http\Requests\Nursery;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
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
            'album_id' => 'required|integer',
            'media' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
            // 'media.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
