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
use App\Models\UserActivation;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password): void
    {
        $validatingRole = \cache()->rememberForever('validating_role', fn () => Role::where('slug', '=', 'validating')->pluck('id'));
        $memberRole = \cache()->rememberForever('member_role', fn () => Role::where('slug', '=', 'user')->pluck('id'));
        $user->password = \bcrypt($password);
        $user->remember_token = Str::random(60);

        if ($user->role_id === $validatingRole[0]) {
            $user->role_id = $memberRole[0];
        }

        $user->active = true;
        $user->save();

        UserActivation::where('user_id', '=', $user->id)->delete();

        $this->guard()->login($user);
    }
}
