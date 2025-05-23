<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'manufacturer' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'product_category_id' => 'required|exists:product_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ];
    }
}
