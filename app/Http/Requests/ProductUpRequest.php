<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpRequest extends FormRequest
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
            'id'                  => 'required|numeric',
            'name'                  => 'nullable|string',
            'code'                  => 'nullable|numeric',
            'brand'                 => 'nullable|string',
            'current_purchase_cost' => 'nullable',
            'current_sale_price'    => 'nullable',
            'available_quantity'    => 'nullable|numeric',
            'description'           => 'nullable|min:20|string',
            'is_popular'            => 'nullable|numeric|min:0|max:1',
            'is_trending'           => 'nullable|numeric|min:0|max:1',
            'status'                => 'nullable|numeric|min:0|max:1',
            'category_id'           => 'nullable',
            'images.*'              => 'nullable|mimes:jpg,jpeg,png,gif',
            'tags.*'                => 'nullable',
        ];
    }
}
