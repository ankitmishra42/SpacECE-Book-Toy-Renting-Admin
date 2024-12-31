<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password' => 'required',
            'device_key' => 'nullable',
            'device_type' => 'nullable',
            'type' => 'required',
        ];

        if ($this->type === 'mobile') {
            $rules['mobile'] = 'required|numeric|exists:users,mobile';
        }

        if ($this->type === 'email') {
            $rules['email'] = 'required|email|exists:users,email';
        }

        if (! $this->type || $this->type === 'contact') {
            if (filter_var($this->contact, FILTER_VALIDATE_EMAIL)) {
                $rules['email'] = 'required|email|exists:users,email';
                $this['email'] = $this->contact;
            } else {
                $rules['mobile'] = 'required|numeric|exists:users,mobile';
                $this['mobile'] = $this->contact;
            }
        }

        return $rules;
    }
}
