<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\User;
use App\Follow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::user()->id == $user->id) {
            return back()->with(Toastr::error("Nice try, but sadly you can not follow yourself.", 'Error!', ['options']));
        } elseif (!Auth::user()->isFollowing($user->id)) {
            // Create a new follow instance for the authenticated user
            Auth::user()->follows()->create([
                'target_id' => $user->id,
            ]);
            return back()->with(Toastr::success('You are now following ' . $user->username, 'Success!', ['options']));
        } else {
            return back()->with(Toastr::error('You are already following this user', 'Error!', ['options']));
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
        if (Auth::user()->isFollowing($user->id)) {
            $follow = Auth::user()->follows()->where('target_id', $user->id)->first();
            $follow->delete();

            return back()->with(Toastr::success('You are no longer following ' . $user->username, 'Success!', ['options']));
        } else {
            return back()->with(Toastr::error('You are not following this user to begin with', 'Error!', ['options']));
        }
    }

}
