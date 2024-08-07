<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'id'            =>  'required|string|max:255',
            'first_name'    =>  'string|max:255',
            'last_name'     =>  'string|max:255',
            'phone_one'     =>  'numeric|min:99999|max:999999999999999',
            'phone_two'     =>  'numeric|min:99999|max:999999999999999',
            'address'       =>  'string|max:255',
            'status'        =>  'boolean|numeric|min:0|max:1',
            'email'         =>  'string|email|max:255|unique:users,email',
            'photo'         =>  'mimes:jpg,png|image',
            'is_admin'      =>  'boolean|numeric|min:0|max:1',
        ];
    }
}
