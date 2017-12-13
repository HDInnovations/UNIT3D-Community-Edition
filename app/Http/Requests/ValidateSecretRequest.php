<?php

namespace App\Http\Requests;

use Cache;
use Crypt;
use Google2FA;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidatonFactory;

class ValidateSecretRequest extends FormRequest
{
    /**
     *
     * @var \App\User
     */
    private $user;

    /**
     * Create a new FormRequest instance.
     *
     * @param \Illuminate\Validation\Factory $factory
     * @return void
     */
    public function __construct(ValidatonFactory $factory)
    {
        $factory->extend(
            'valid_token',
            function ($attribute, $value, $parameters, $validator) {
                $secret = Crypt::decrypt($this->user->google2fa_secret);
                return Google2FA::verifyKey($secret, $value);
            },
            'Not a valid token'
        );
        $factory->extend(
            'used_token',
            function ($attribute, $value, $parameters, $validator) {
                $key = $this->user->id . ':' . $value;
                return !Cache::has($key);
            },
            'Cannot reuse token'
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->user = User::findOrFail(
                session('2fa:user:id')
            );
        } catch (Exception $exc) {
            return false;
        }
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
            'totp' => 'bail|required|digits:6|valid_token|used_token',
        ];
    }
}
