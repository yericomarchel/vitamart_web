<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
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
            // Define your validation rules for each item in the array
            'items.*.cart_id' => 'required|numeric',
            'items.*.product_id' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',

        ];

    }

}

