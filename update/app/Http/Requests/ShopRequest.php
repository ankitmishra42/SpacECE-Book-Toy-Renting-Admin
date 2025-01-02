<?php

namespace App\Http\Requests;

use App\Rules\EmailValidateRule;
use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
        $userId = null;
        $required = 'required';
        if (\request()->isMethod('put')) {
            $userId = $this->userId;
            $required = 'nullable';
        }

        $commitionRequired = 'required';
        if(route('seller.register')){
            $commitionRequired = 'nullable';
        }

        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => ['required', new EmailValidateRule(), "unique:users,email,$userId,id"],
            'mobile' => ['required', "unique:users,mobile,$userId,id"],
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'gender' => 'nullable',
            'password' => [$required, 'min:6', 'confirmed'],
            'date_of_birth' => 'required|date',
            'name' => 'required|string',
            'logo' => [$required, 'image', 'mimes:jpg,jpeg,png'],
            'banner' => 'nullable|image|mimes:jpg,jpeg,png',
            'commission' => [$commitionRequired, 'numeric', 'min:0', 'max:100'],
            'prefix' => ['nullable', 'string'],
            'description' => 'required|string',
            'min_order_amount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|nullable|min:0',
        ];
    }
}
