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
use Brian2694\Toastr\Toastr;
use App\Notifications\NewFollow;
use App\Notifications\NewUnfollow;

class FollowController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * FollowController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Follow A User.
     *
     * @param User $user
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function follow(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('Nice try, but sadly you can not follow yourself.', 'Whoops!', ['options']));
        } elseif (! auth()->user()->isFollowing($user->id)) {
            $follow = new Follow();
            $follow->user_id = auth()->user()->id;
            $follow->target_id = $user->id;
            $follow->save();
            if ($user->acceptsNotification(auth()->user(), $user, 'account', 'show_account_follow')) {
                $user->notify(new NewFollow('user', auth()->user(), $user, $follow));
            }

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->success('You are now following '.$user->username, 'Yay!', ['options']));
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('You are already following this user', 'Whoops!', ['options']));
        }
    }

    /**
     * Un Follow A User.
     *
     * @param User $user
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unfollow(User $user)
    {
        if (auth()->user()->isFollowing($user->id)) {
            $follow = auth()->user()->follows()->where('target_id', '=', $user->id)->first();
            $follow->delete();
            if ($user->acceptsNotification(auth()->user(), $user, 'account', 'show_account_unfollow')) {
                $user->notify(new NewUnfollow('user', auth()->user(), $user, $follow));
            }

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->success('You are no longer following '.$user->username, 'Yay!', ['options']));
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('You are not following this user to begin with', 'Whoops!', ['options']));
        }
    }
}
