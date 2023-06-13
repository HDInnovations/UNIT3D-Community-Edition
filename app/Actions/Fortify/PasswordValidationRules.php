<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password as Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(12)->mixedCase()->letters()->numbers()->uncompromised(),
        ];
    }
}
