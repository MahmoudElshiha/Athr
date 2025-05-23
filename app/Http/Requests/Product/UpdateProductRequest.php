<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'quantity' => 'nullable|integer|min:0',
        ];
    }
}
