<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\User;
use App\Follow;
use \Toastr;

class FollowController extends Controller
{

    /**
     * Follow A User
     *
     *
     * @param $user
     */
    public function follow(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error("Nice try, but sadly you can not follow yourself.", 'Whoops!', ['options']));
        } elseif (!auth()->user()->isFollowing($user->id)) {
            // Create a new follow instance for the authenticated user
            auth()->user()->follows()->create([
                'target_id' => $user->id,
            ]);
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('You are now following ' . $user->username, 'Yay!', ['options']));
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('You are already following this user', 'Whoops!', ['options']));
        }
    }

    /**
     * Unfollow A User
     *
     *
     * @param $user
     */
    public function unfollow(User $user)
    {
        if (auth()->user()->isFollowing($user->id)) {
            $follow = auth()->user()->follows()->where('target_id', $user->id)->first();
            $follow->delete();

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('You are no longer following ' . $user->username, 'Yay!', ['options']));
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('You are not following this user to begin with', 'Whoops!', ['options']));
        }
    }
}
