<?php

namespace App\Http\Requests;

use App\Rules\EmailValidateRule;
use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
        $required = 'required';
        $userId = null;
        if ($this->routeIs('riders.update')) {
            $required = 'nullable';
            $userId = $this->driver->user?->id;
        }
        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'mobile' => 'required|numeric|unique:users,mobile,' . $userId,
            'email' => ['nullable', new EmailValidateRule(), 'unique:users,email,' . $userId],
            'password' =>"$required|min:6|confirmed",
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'driving_lience' => 'nullable|string',
            'vehicle_type' => 'required|string',
        ];
    }
}
