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
use App\Models\Privilege;
use App\Models\Role;
use App\Models\UserActivation;

/**
 * @see \Tests\Feature\Http\Controllers\Auth\ActivationControllerTest
 */
class ActivationController extends Controller
{
    public function activate($token): \Illuminate\Http\RedirectResponse
    {
        $member = Role::where('slug', 'user')->firstOrFail();
        $validating = Role::where('slug', 'validating')->firstOrFail();

        $activation = UserActivation::with('user')->where('token', '=', $token)->firstOrFail();
        if (
            $activation->user->id && ! $activation->user->hasRole('banned')) {
            $activation->user->privileges()->attach(Privilege::where('slug', 'can_login')->firstOrFail());
            $activation->user->privileges()->attach(Privilege::where('slug', 'active_user')->firstOrFail());
            $activation->user->roles()->attach($member);
            $activation->user->roles()->detach($validating);
            $activation->user->active = 1;
            $activation->user->primaryRole()->associate($member);
            $activation->user->save();

            $activation->delete();

            return \to_route('login')
                ->withSuccess(\trans('auth.activation-success'));
        }

        return \to_route('login')
            ->withErrors(\trans('auth.activation-error'));
    }
}
