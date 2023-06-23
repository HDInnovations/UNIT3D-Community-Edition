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

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateUserRequest;
use App\Models\Comment;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Internal;
use App\Models\Invite;
use App\Models\Like;
use App\Models\Message;
use App\Models\Note;
use App\Models\Peer;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Thank;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Warning;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
    public function settings(string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.user.edit', [
            'user'      => User::withTrashed()->where('username', '=', $username)->sole(),
            'groups'    => Group::all(),
            'internals' => Internal::all(),
        ]);
    }

    /**
     * Edit A User.
     */
    public function edit(UpdateUserRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::with('group')->where('username', '=', $username)->sole();
        $staff = $request->user();
        $group = Group::findOrFail($request->group_id);

        abort_if(! $staff->group->is_owner && ($staff->group->level < $user->group->level || $staff->group->level < $group->level), 403);

        $user->update($request->validated());

        cache()->forget('user:'.$user->passkey);
        Unit3dAnnounce::addUser($user);

        return to_route('users.show', ['username' => $username])
            ->withSuccess('Account Was Updated Successfully!');
    }

    /**
     * Edit A Users Permissions.
     */
    public function permissions(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->sole();
        $user->can_upload = $request->input('can_upload');
        $user->can_download = $request->input('can_download');
        $user->can_comment = $request->input('can_comment');
        $user->can_invite = $request->input('can_invite');
        $user->can_request = $request->input('can_request');
        $user->can_chat = $request->input('can_chat');
        $user->save();

        cache()->forget('user:'.$user->passkey);
        Unit3dAnnounce::addUser($user);

        return to_route('users.show', ['username' => $username])
            ->withSuccess('Account Permissions Successfully Edited');
    }

    /**
     * Delete A User.
     */
    protected function destroy(string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->sole();

        abort_if($user->group->is_modo || auth()->user()->id == $user->id, 403);

        $user->can_upload = 0;
        $user->can_download = 0;
        $user->can_comment = 0;
        $user->can_invite = 0;
        $user->can_request = 0;
        $user->can_chat = 0;
        $user->group_id = UserGroups::PRUNED;
        $user->deleted_by = auth()->user()->id;
        $user->save();

        // Removes UserID from Torrents if any and replaces with System UserID (1)
        foreach (Torrent::withAnyStatus()->where('user_id', '=', $user->id)->get() as $tor) {
            $tor->user_id = 1;
            $tor->save();
        }

        // Removes UserID from Comments if any and replaces with System UserID (1)
        foreach (Comment::where('user_id', '=', $user->id)->get() as $com) {
            $com->user_id = 1;
            $com->save();
        }

        // Removes UserID from Posts if any and replaces with System UserID (1)
        foreach (Post::where('user_id', '=', $user->id)->get() as $post) {
            $post->user_id = 1;
            $post->save();
        }

        // Removes UserID from Topic Creators if any and replaces with System UserID (1)
        foreach (Topic::where('first_post_user_id', '=', $user->id)->get() as $topic) {
            $topic->first_post_user_id = 1;
            $topic->save();
        }

        // Removes UserID from Topic if any and replaces with System UserID (1)
        foreach (Topic::where('last_post_user_id', '=', $user->id)->get() as $topic) {
            $topic->last_post_user_id = 1;
            $topic->save();
        }

        // Removes UserID from PM if any and replaces with System UserID (1)
        foreach (PrivateMessage::where('sender_id', '=', $user->id)->get() as $sent) {
            $sent->sender_id = 1;
            $sent->save();
        }

        // Removes UserID from PM if any and replaces with System UserID (1)
        foreach (PrivateMessage::where('receiver_id', '=', $user->id)->get() as $received) {
            $received->receiver_id = 1;
            $received->save();
        }

        // Removes all Posts made by User from the shoutbox
        foreach (Message::where('user_id', '=', $user->id)->get() as $shout) {
            $shout->delete();
        }

        // Removes all notes for user
        foreach (Note::where('user_id', '=', $user->id)->get() as $note) {
            $note->delete();
        }

        // Removes all likes for user
        foreach (Like::where('user_id', '=', $user->id)->get() as $like) {
            $like->delete();
        }

        // Removes all thanks for user
        foreach (Thank::where('user_id', '=', $user->id)->get() as $thank) {
            $thank->delete();
        }

        // Removes all follows for user
        $user->followers()->detach();
        $user->following()->detach();

        // Removes UserID from Sent Invites if any and replaces with System UserID (1)
        foreach (Invite::where('user_id', '=', $user->id)->get() as $sentInvite) {
            $sentInvite->user_id = 1;
            $sentInvite->save();
        }

        // Removes UserID from Received Invite if any and replaces with System UserID (1)
        foreach (Invite::where('accepted_by', '=', $user->id)->get() as $receivedInvite) {
            $receivedInvite->accepted_by = 1;
            $receivedInvite->save();
        }

        // Removes all Peers for user
        foreach (Peer::where('user_id', '=', $user->id)->get() as $peer) {
            $peer->delete();
        }

        // Remove all History records for user
        foreach (History::where('user_id', '=', $user->id)->get() as $history) {
            $history->delete();
        }

        // Removes all FL Tokens for user
        foreach (FreeleechToken::where('user_id', '=', $user->id)->get() as $token) {
            $token->delete();
            cache()->forget('freeleech_token:'.$user->id.':'.$token->torrent_id);
        }

        if ($user->delete()) {
            return to_route('staff.dashboard.index')
                ->withSuccess('Account Has Been Removed');
        }

        cache()->forget('user:'.$user->passkey);
        Unit3dAnnounce::removeUser($user);

        return to_route('staff.dashboard.index')
            ->withErrors('Something Went Wrong!');
    }

    /**
     * Manually warn a user.
     */
    protected function warnUser(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->sole();
        $carbon = new Carbon();
        $warning = new Warning();
        $warning->user_id = $user->id;
        $warning->warned_by = $request->user()->id;
        $warning->torrent = null;
        $warning->reason = $request->input('message');
        $warning->expires_on = $carbon->copy()->addDays(config('hitrun.expire'));
        $warning->active = '1';
        $warning->save();

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = 1;
        $pm->receiver_id = $user->id;
        $pm->subject = 'Received warning';
        $pm->message = 'You have received a [b]warning[/b]. Reason: '.$request->input('message');
        $pm->save();

        return to_route('users.show', ['username' => $username])
            ->withSuccess('Warning issued successfully!');
    }
}
