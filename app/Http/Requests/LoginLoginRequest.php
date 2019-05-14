<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginLoginRequest extends FormRequest
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
                $this->username()      => 'required|string',
                'password'             => 'required|string',
                'g-recaptcha-response' => 'required|recaptcha',
               ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
