<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoRequest extends FormRequest
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
            'first_name'    =>  'string|max:255',
            'last_name'     =>  'string|max:255',
            'phone_one'     =>  'numeric|min:99999|max:999999999999999',
            'phone_two'     =>  'numeric|min:99999|max:999999999999999',
            'email'         =>  'string|email|max:255|unique:users,email',
            'address'       =>  'string|max:255',
            'photo'         =>  'mimes:jpg,png|image',
            // 'photo'         =>  ['required', File::image()->min(1024)->max(12 * 1024)->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500)),],
            'status'        =>  'boolean|numeric|min:0|max:1',
        ];
    }
}
