<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class ReorderRequest extends FormRequest
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
            'order_id' => 'required|exists:'.(new Order())->getTable().',id',
            'pick_date' => ['required', 'date'],
            'pick_hour' => ['required'],
            'delivery_date' => ['required', 'date'],
            'delivery_hour' => ['required'],
        ];
    }
}
