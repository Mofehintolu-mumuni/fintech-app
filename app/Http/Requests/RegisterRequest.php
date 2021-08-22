<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'string|required',
            'email' => ['email', 'required', 'unique:users'],
            'password' => 'required'
        ];
    }


    /**
     * Get the validation messages that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.string' => 'Please provide your fullname',
            'name.required' => 'Please provide your fullname',
            'email.email' => 'Please provide a valid email address',
            'email.required' => 'Please provide a valid email address',
            'email.unique' => 'Please provide a valid email address',
            'password.string' => 'Please provide a valid password',
            'password.required' => 'Please provide a valid password',
        ];
    }


}
