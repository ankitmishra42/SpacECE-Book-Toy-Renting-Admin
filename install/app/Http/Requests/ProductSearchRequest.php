<?php

namespace App\Http\Requests;

use App\Models\Service;
use App\Models\Variant;
use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
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
            'service_id' => 'required|exists:'.(new Service())->getTable().',id',
            'variant_id' => 'required|exists:'.(new Variant())->getTable().',id',
        ];
    }
}
