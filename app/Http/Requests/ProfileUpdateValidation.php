<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateValidation extends FormRequest
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
            'id' => 'required|numeric|min:1',
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|max:191|unique:users,email,' . $this->id,
        ];
    }

    /**
     * Get the validation messages that apply to validation rules of the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name should\'nt exceed 191 characters limit.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name should\'nt exceed 191 characters limit.',
            'email.required' => 'Email is required.',
            'email.max' => 'Email should\'nt exceed 191 characters limit.',
            'email.unique' => 'This email address is already taken by another user.',
        ];
    }
}
