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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewFollow;
use App\Notifications\NewUnfollow;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\FollowControllerTest
 */
class FollowController extends Controller
{
    /**
     * User Followers.
     */
    public function index(User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $followers = $user->followers()->orderByPivot('created_at', 'desc')->paginate(25);

        return \view('user.follower.index', [
            'followers' => $followers,
            'user'      => $user,
        ]);
    }

    /**
     * Follow A User.
     */
    public function store(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return \to_route('users.show', ['username' => $user->username])
                ->withErrors(\trans('user.follow-yourself'));
        }

        $user->followers()->attach($request->user()->id);

        if ($user->acceptsNotification($request->user(), $user, 'account', 'show_account_follow')) {
            $user->notify(new NewFollow('user', $request->user()));
        }

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess(\sprintf(\trans('user.follow-user'), $user->username));
    }

    /**
     * Un Follow A User.
     */
    public function destroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $user->followers()->detach($request->user()->id);

        if ($user->acceptsNotification($request->user(), $user, 'account', 'show_account_unfollow')) {
            $user->notify(new NewUnfollow('user', $request->user()));
        }

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess(\sprintf(\trans('user.follow-revoked'), $user->username));
    }
}
