<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordOtpVerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'otp' => 'required',
            'type' => 'required',
        ];

        if ($this->type === 'mobile') {
            $rules['mobile'] = 'required|numeric|exists:users,mobile';
        }

        if ($this->type === 'email') {
            $rules['email'] = 'required|email|exists:users,email';
        }

        if ($this->type === 'contact') {
            $rules['contact'] = 'required';
        }

        return $rules;
    }
}
