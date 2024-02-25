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

namespace App\Http\Controllers\Staff;

use App\Enums\UserGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateUserRequest;
use App\Models\Comment;
use App\Models\FailedLoginAttempt;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Like;
use App\Models\Message;
use App\Models\Peer;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Role;
use App\Models\Scopes\ApprovedScope;
use App\Models\Thank;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\UserControllerTest
 */
class UserController extends Controller
{
    /**
     * Users List.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.user.index');
    }

    /**
     * User Edit Form.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $group = $request->user()->group;

        return view('Staff.user.edit', [
            'user'   => $user->load('roles'),
            'groups' => Group::query()
                ->when(!$group->is_owner, fn ($query) => $query->where('level', '<=', $group->level))
                ->orderBy('position')
                ->get(),
            'roles' => Role::query()->orderBy('position')->get(),
        ]);
    }

    /**
     * Edit A User.
     */
    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $user->load('group');
        $staff = $request->user();
        $group = Group::findOrFail($request->integer('user.group_id'));

        abort_if(!($staff->group->is_owner || $staff->group->is_admin) && ($staff->group->level <= $user->group->level || $staff->group->level <= $group->level), 403);

        $user->update($request->validated());
        $user->roles()->sync($request->validated('roles'));

        cache()->forget('user:'.$user->passkey);
        cache()->forget('rbac-user-roles');

        Unit3dAnnounce::addUser($user);

        return to_route('users.show', ['user' => $user])
            ->withSuccess('Account Was Updated Successfully!');
    }

    /**
     * Delete A User.
     */
    protected function destroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_if($user->group->is_modo || $request->user()->is($user), 403);

        $user->update([
            'group_id'   => UserGroup::PRUNED->value,
            'deleted_by' => auth()->id(),
        ]);

        Torrent::withoutGlobalScope(ApprovedScope::class)->where('user_id', '=', $user->id)->update([
            'user_id' => User::SYSTEM_USER_ID,
        ]);

        Comment::where('user_id', '=', $user->id)->update([
            'user_id' => User::SYSTEM_USER_ID,
        ]);

        Post::where('user_id', '=', $user->id)->update([
            'user_id' => User::SYSTEM_USER_ID,
        ]);

        Topic::where('first_post_user_id', '=', $user->id)->update([
            'first_post_user_id' => User::SYSTEM_USER_ID,
        ]);

        Topic::where('last_post_user_id', '=', $user->id)->update([
            'last_post_user_id' => User::SYSTEM_USER_ID,
        ]);

        PrivateMessage::where('sender_id', '=', $user->id)->update([
            'sender_id' => User::SYSTEM_USER_ID,
        ]);

        PrivateMessage::where('receiver_id', '=', $user->id)->update([
            'receiver_id' => User::SYSTEM_USER_ID,
        ]);

        Message::where('user_id', '=', $user->id)->delete();
        Like::where('user_id', '=', $user->id)->delete();
        Thank::where('user_id', '=', $user->id)->delete();
        Peer::where('user_id', '=', $user->id)->delete();
        History::where('user_id', '=', $user->id)->delete();
        FailedLoginAttempt::where('user_id', '=', $user->id)->delete();

        // Removes all follows for user
        $user->followers()->detach();
        $user->following()->detach();

        // Removes all FL Tokens for user
        foreach (FreeleechToken::where('user_id', '=', $user->id)->get() as $token) {
            $token->delete();
            cache()->forget('freeleech_token:'.$user->id.':'.$token->torrent_id);
        }

        if ($user->delete()) {
            cache()->forget('user:'.$user->passkey);

            Unit3dAnnounce::removeUser($user);

            return to_route('staff.dashboard.index')
                ->withSuccess('Account Has Been Removed');
        }

        return to_route('staff.dashboard.index')
            ->withErrors('Something Went Wrong!');
    }
}
