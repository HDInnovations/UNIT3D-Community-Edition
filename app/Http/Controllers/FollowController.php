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

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
use App\Notifications\NewFollow;
use App\Notifications\NewUnfollow;

class FollowController extends Controller
{
    /**
     * Follow A User.
     *
     * @param User $user
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function follow(Request $request, User $user)
    {
        if ($request->user()->id == $user->id) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withErrors('Nice try, but sadly you can not follow yourself.');
        } elseif (! $request->user()->isFollowing($user->id)) {
            $follow = new Follow();
            $follow->user_id = $request->user()->id;
            $follow->target_id = $user->id;
            $follow->save();
            if ($user->acceptsNotification($request->user(), $user, 'account', 'show_account_follow')) {
                $user->notify(new NewFollow('user', $request->user(), $user, $follow));
            }

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withSuccess('You are now following '.$user->username);
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withErrors('You are already following this user');
        }
    }

    /**
     * Un Follow A User.
     *
     * @param User $user
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unfollow(Request $request, User $user)
    {
        if ($request->user()->isFollowing($user->id)) {
            $follow = $request->user()->follows()->where('target_id', '=', $user->id)->first();
            $follow->delete();
            if ($user->acceptsNotification($request->user(), $user, 'account', 'show_account_unfollow')) {
                $user->notify(new NewUnfollow('user', $request->user(), $user, $follow));
            }

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withSuccess('You are no longer following '.$user->username);
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withErrors('You are not following this user to begin with');
        }
    }
}
