<?php

namespace App\Http\Requests;

use App\Rules\EmailValidateRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = auth()->id();
        if (\request()->routeIs('admin.update')) {
            $userId = $this->userId;
        }
        $hasMail = $this->email ? 'nullable' : 'required';
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => ['nullable', new EmailValidateRule(), "unique:users,email,$userId,id"],
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'gender' => 'nullable',
            'mobile' => ["$hasMail", "unique:users,mobile,$userId,id"],
            'alternative_phone' => 'nullable',
            'driving_lience' => 'nullable',
            'date_of_birth' => 'nullable',
        ];

        return $rules;

    }
}
