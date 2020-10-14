<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordValidation extends FormRequest
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
        return [
            'password' => 'required|min:8|max:50|confirmed',
            'password_confirmation' => 'required|min:8|max:50',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Password is required.',
            'password.min' => 'Password should be at least 8 characters long.',
            'password.max' => 'Password shoouldn\'t be more than 50 characters long.',
            'password.confirmed' => 'Password confirmation failed.',
            'password_confirmation.required' => 'You should confim password.',
            'password_confirmation.min' => 'Confirmed password should be at least 8 characters long.',
            'password_confirmation.max' => 'Confirmed password shoouldn\'t be more than 50 characters long.',
        ];
    }
}
