<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name'                  => 'required|string',
            'status'                => 'nullable|numeric|min:0|max:1',
            'note'                  => 'nullable|min:20|string',
            'image'                 => 'nullable|mimes:jpg,jpeg,png,gif',
        ];
    }
}
