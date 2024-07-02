<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'                      => 'required|string',
            'code'                      => 'nullable|numeric',
            'brand'                     => 'nullable|string',
            'current_purchase_cost'     => 'required',
            'current_sale_price'        => 'required',
            'available_quantity'        => 'required|numeric',
            'description'               => 'required|min:20|string',
            'is_popular'                => 'nullable|numeric|min:0|max:1',
            'is_trending'               => 'nullable|numeric|min:0|max:1',
            'status'                    => 'required|numeric|min:0|max:1',
            'category_id'               => 'required',
            'images.*'                  => 'nullable|mimes:jpg,jpeg,png,gif',
            'tags.*'                    => 'required',
        ];
    }
}
