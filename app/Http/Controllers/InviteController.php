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

namespace App\Http\Controllers;

use App\Models\Invite;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\InviteControllerTest
 */
class InviteController extends \App\Http\Controllers\Controller
{
    /**
     * Invite Tree.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(\Illuminate\Http\Request $request, $username)
    {
        $user = $request->user();
        $owner = \App\Models\User::where('username', '=', $username)->firstOrFail();
        \abort_unless($user->group->is_modo || $user->id === $owner->id, 403);
        $invites = \App\Models\Invite::with(['sender', 'receiver'])->where('user_id', '=', $owner->id)->latest()->paginate(25);

        return \view('user.invites', ['owner' => $owner, 'invites' => $invites, 'route' => 'invite']);
    }

    /**
     * Invite Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        if (\config('other.invite-only') == false) {
            return \redirect()->route('home.index')->withErrors('Invitations Are Disabled Due To Open Registration!');
        }
        if ($user->can_invite == 0) {
            return \redirect()->route('home.index')->withErrors('Your Invite Rights Have Been Revoked!');
        }
        if (\config('other.invites_restriced') == true && ! \in_array($user->group->name, \config('other.invite_groups'))) {
            return \redirect()->route('home.index')->withErrors('Invites are currently disabled for your group.');
        }

        return \view('user.invite', ['user' => $user, 'route' => 'invite']);
    }

    /**
     * Send Invite.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $carbon = new \Carbon\Carbon();
        $user = $request->user();
        if (\config('other.invites_restriced') == true && ! \in_array($user->group->name, \config('other.invite_groups'))) {
            return \redirect()->route('home.index')->withErrors('Invites are currently disabled for your group.');
        }
        if ($user->invites <= 0) {
            return \redirect()->route('invites.create')->withErrors('You do not have enough invites!');
        }
        $exist = \App\Models\Invite::where('email', '=', $request->input('email'))->first();
        if ($exist) {
            return \redirect()->route('invites.create')->withErrors('The email address your trying to send a invite to has already been sent one.');
        }
        $code = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $invite = new \App\Models\Invite();
        $invite->user_id = $user->id;
        $invite->email = $request->input('email');
        $invite->code = $code;
        $invite->expires_on = $carbon->copy()->addDays(\config('other.invite_expire'));
        $invite->custom = $request->input('message');
        if (\config('email-blacklist.enabled')) {
            $v = \validator($invite->toArray(), ['email' => 'required|string|email|max:70|blacklist|unique:users', 'custom' => 'required']);
        } else {
            $v = \validator($invite->toArray(), ['email' => 'required|string|email|max:70|unique:users', 'custom' => 'required']);
        }
        if ($v->fails()) {
            return \redirect()->route('invites.create')->withErrors($v->errors());
        }
        \Illuminate\Support\Facades\Mail::to($request->input('email'))->send(new \App\Mail\InviteUser($invite));
        $invite->save();
        $user->invites--;
        $user->save();

        return \redirect()->route('invites.create')->withSuccess('Invite was sent successfully!');
    }

    /**
     * Resend Invite.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Invite       $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $invite = \App\Models\Invite::findOrFail($id);
        \abort_unless($invite->user_id === $user->id, 403);
        if ($invite->accepted_by !== null) {
            return \redirect()->route('invites.index', ['username' => $user->username])->withErrors('The invite you are trying to resend has already been used.');
        }
        \Illuminate\Support\Facades\Mail::to($invite->email)->send(new \App\Mail\InviteUser($invite));

        return \redirect()->route('invites.index', ['username' => $user->username])->withSuccess('Invite was resent successfully!');
    }
}
