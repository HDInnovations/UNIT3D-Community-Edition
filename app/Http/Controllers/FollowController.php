<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Notifications\NewFollow;
use App\Notifications\NewUnfollow;

class FollowController extends Controller
{
    /**
     * Follow A User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User                      $user
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        if ($request->user()->id == $user->id) {
            return redirect()->route('users.show', ['username' => $user->username])
                ->withErrors('Nice try, but sadly you can not follow yourself.');
        }

        if (! $request->user()->isFollowing($user->id)) {
            $follow = new Follow();
            $follow->user_id = $request->user()->id;
            $follow->target_id = $user->id;
            $follow->save();
            if ($user->acceptsNotification($request->user(), $user->id, 'account', 'show_account_follow')) {
                $user->notify(new NewFollow('user', $request->user(), $user->id, $follow));
            }

            return redirect()->route('users.show', ['username' => $user->username])
                ->withSuccess('You are now following '.$user->username);
        } else {
            return redirect()->route('users.show', ['username' => $user->username])
                ->withErrors('You are already following this user');
        }
    }

    /**
     * Un Follow A User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User                      $user
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        if ($request->user()->isFollowing($user->id)) {
            $follow = $request->user()->follows()->where('target_id', '=', $user->id)->first();
            $follow->delete();
            if ($user->acceptsNotification($request->user(), $user, 'account', 'show_account_unfollow')) {
                $user->notify(new NewUnfollow('user', $request->user(), $user, $follow));
            }

            return redirect()->route('users.show', ['username' => $user->username])
                ->withSuccess('You are no longer following '.$user->username);
        } else {
            return redirect()->route('users.show', ['username' => $user->username])
                ->withErrors('You are not following this user to begin with');
        }
    }
}
