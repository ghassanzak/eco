<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        if (auth()->check()) {
            if (auth()->user()->is_admin()) {
                return [
                    'first_name'    =>  'required|string|max:255',
                    'last_name'     =>  'required|string|max:255',
                    'phone_one'     =>  'required|numeric|min:99999|max:9999999999',
                    'email'         =>  'required|string|email|max:255|unique:users,email',
                    'password'      =>  'required|string|min:6',
                    'address'       =>  'required|string|max:255',
                    'status'        =>  'required|boolean|numeric|min:0|max:1',
                    'is_admin'      =>  'required|boolean|numeric|min:0|max:1',
                    'phone_two'     =>  'numeric|min:99999|max:9999999999',
                    'photo'         =>  'mimes:jpg,png|image',
                    // 'photo'         =>  ['required', File::image()->min(1024)->max(12 * 1024)->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500)),],
                ];
                
            }
        }
        return [
            'first_name'    =>  'required|string|max:255',
            'last_name'     =>  'required|string|max:255',
            'phone_one'     =>  'required|numeric|min:99999|max:9999999999',
            'phone_two'     =>  'numeric|min:99999|max:9999999999',
            'email'         =>  'required|string|email|max:255|unique:users,email',
            'password'      =>  'required|string|min:6',
            'photo'         =>  'mimes:jpg,png|image',
            'address'       =>  'string|max:255',
            // 'photo'         =>  ['required', File::image()->min(1024)->max(12 * 1024)->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500)),],
            // 'status'        =>  'boolean|numeric|min:0|max:1',
            // 'is_admin'      =>  'boolean|numeric|min:0|max:1',
        ];
    }
}
