<?php
/*
 * Extends Authentificator Class and adds feature to only enable 2FA on login for those who have TOTP activated.
 */

namespace App\Support;

use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FAAuthenticator extends Authenticator
{
    protected function canPassWithoutCheckingOTP()
    {
        if($this->getUser()->passwordSecurity == null)
            return true;
        }
        return
            ! $this->getUser()->passwordSecurity->google2fa_enable ||
            ! $this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid();
    }

    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->passwordSecurity->{$this->config('otp_secret_column')};

        if (is_null($secret) || empty($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }
}
