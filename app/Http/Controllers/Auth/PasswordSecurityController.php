<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordSecurity;
use PragmaRX\Recovery\Recovery;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PasswordSecurityController extends Controller
{
    /**
     * Display 2FA Formular.
     */
    public function show2faForm(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = \auth()->user();

        $google2fa_url = "";
        $google2fa_secret = "";
        $recovery = [];
        if($user->passwordSecurity()->exists()){
            $google2fa = app('pragmarx.google2fa');

            $google2fa_secret = Crypt::decrypt($user->passwordSecurity->google2fa_secret);
            $recovery = $user->passwordSecurity->recovery;

            $google2fa_url = $google2fa->getQRCodeInline(
                \config('app.name'),
                $user->email,
                $google2fa_secret
            );
        }

        return \view('auth.2fa', [
            'user'              => $user,
            'google2fa_url'     => $google2fa_url,
            'google2fa_secret'  => $google2fa_secret,
            'recovery'          => $recovery,
            'tempRecovery'      => false,
        ]);
    }

    /**
     * Generate 2FA Secret Key.
     */
    public function generate2faSecret(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();
        $google2fa = app('pragmarx.google2fa');
        $recovery = new Recovery();;

        // Encrypt Recovery Keys
        $recoveryCrypt = array();
        foreach($recovery->setCount(10)->toArray() as $key) {
            $recoveryCrypt[] = Crypt::encrypt($key);
        }

        // Add the secret key to the registration data
        PasswordSecurity::create([
            'user_id'           => $user->id,
            'google2fa_enable'  => 0,
            'google2fa_secret'  => Crypt::encrypt($google2fa->generateSecretKey()),
            'recovery'          => $recoveryCrypt,
        ]);
    
        return \redirect()->route('2fa')
            ->withSuccess(\trans('auth.create-success'));
    }

    /**
     * Generate new Recovery Codes.
     */
    public function generateNewRecoveryCodes(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();
        $google2fa = app('pragmarx.google2fa');
        $recovery = new Recovery();;

        $secret = $request->input('verify-code');
        $valid = $google2fa->verifyKey(Crypt::decrypt($user->passwordSecurity->google2fa_secret), $secret);

        if ($valid) {
            // Encrypt Recovery Keys
            $recoveryCrypt = array();
            foreach($recovery->setCount(10)->toArray() as $key) {
                $recoveryCrypt[] = Crypt::encrypt($key);
            }

            $user->passwordSecurity->recovery = $recoveryCrypt;
            $user->passwordSecurity->save();

            return \view('auth.2fa', [
                'user'              => $user,
                'google2fa_url'     => 1,
                'google2fa_secret'  => '',
                'recovery'          => $recoveryCrypt,
                'tempRecovery'      => true,
            ]);
        }

        return \redirect()->route('2fa')
            ->withErrors(\trans('auth.invalid-token'));
    }

    /**
     * Enable 2FA Authentication.
     */
    public function enable2fa(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();
        $google2fa = app('pragmarx.google2fa');

        $secret = $request->input('verify-code');
        $valid = $google2fa->verifyKey(Crypt::decrypt($user->passwordSecurity->google2fa_secret), $secret);

        if ($valid) {
            $user->passwordSecurity->google2fa_enable = 1;
            $user->passwordSecurity->save();

            return \redirect()->route('2fa')
                ->withSuccess(\trans('auth.activate-success'));
        }

        return \redirect()->route('2fa')
            ->withErrors(\trans('auth.invalid-token'));
    }

    /**
     * Disable 2FA Authentication.
     */
    public function disable2fa(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();

        // Check if the passwords matches
        if (! (Hash::check($request->get('current-password'), $user->password))) {
            return \redirect()->route('2fa')
                ->withErrors(\trans('auth.invalid-password'));
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);

        $user->passwordSecurity->google2fa_enable = 0;
        $user->passwordSecurity->save();

        return \redirect()->route('2fa')
            ->withSuccess(\trans('auth.deactivate-success'));
    }

    /**
     * Recovery Mode: Disable 2FA Authentication.
     */
    public function recovery2fa(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();

        $recoveryCode = $request->input('one_time_recovery');

        $recovery = array();
        foreach($user->passwordSecurity->recovery as $crypt) {
            $recovery[] = Crypt::decrypt($crypt);
        }

        $valid = in_array($recoveryCode, $recovery);

        $validatedData = $request->validate([
            'one_time_recovery' => 'required'
        ]);

        if ($valid) {
            // Remove Recovery Key from Array
            if (($key = array_search($recoveryCode, $recovery)) !== false) {
                unset($recovery[$key]);
            }

            // Encrypt Recovery Keys
            $recoveryCrypt = array();
            foreach($recovery as $key) {
                $recoveryCrypt[] = Crypt::encrypt($key);
            }

            $user->passwordSecurity->recovery = $recoveryCrypt;
            $user->passwordSecurity->google2fa_enable = 0;
            $user->passwordSecurity->save();

            return \redirect()->route('2fa')
                ->withSuccess(\trans('auth.deactivate-success'));
        }

        return \redirect()->route('2fa')
            ->withErrors(\trans('auth.invalid-recovery-key'));
    }
}
