<?php

namespace App\Http\Requests;

use App\Rules\EmailValidateRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,gif'],
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif',
            'description' => 'required|string',
            'min_order_amount' => 'required|numeric|min:0',
            'prefix' => 'required|string|max:2',
            'delivery_charge' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'delivery_charge.required' => 'Delivery charge should be at least 0',
            'min_order_amount.required' => 'Minimum order amount should be at least 0',
        ];
    }
}
