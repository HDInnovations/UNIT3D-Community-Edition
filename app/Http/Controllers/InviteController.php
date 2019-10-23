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

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Invite;
use App\Mail\InviteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InviteController extends Controller
{
    /**
     * Invite Form.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invite(Request $request)
    {
        $user = $request->user();

        if (config('other.invite-only') == false) {
            return redirect()->route('home.index')
            ->withErrors('Invitations Are Disabled Due To Open Registration!');
        }
        if ($user->can_invite == 0) {
            return redirect()->route('home.index')
            ->withErrors('Your Invite Rights Have Been Revoked!');
        }
        if (config('other.invites_restriced') == true && ! in_array($user->group->name, config('other.invite_groups'))) {
            return redirect()->route('home.index')
                ->withErrors('Invites are currently disabled for your group.');
        }

        return view('user.invite', ['user' => $user, 'route' => 'invite']);
    }

    /**
     * Send Invite.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function process(Request $request)
    {
        $current = new Carbon();
        $user = $request->user();

        if (config('other.invites_restriced') == true && ! in_array($user->group->name, config('other.invite_groups'))) {
            return redirect()->route('home.index')
                ->withErrors('Invites are currently disabled for your group.');
        }

        if ($user->invites <= 0) {
            return redirect()->route('invite')
                ->withErrors('You do not have enough invites!');
        }

        $exist = Invite::where('email', '=', $request->input('email'))->first();

        if ($exist) {
            return redirect()->route('invite')
                ->withErrors('The email address your trying to send a invite to has already been sent one.');
        }

        $code = Uuid::uuid4()->toString();
        $invite = new Invite();
        $invite->user_id = $user->id;
        $invite->email = $request->input('email');
        $invite->code = $code;
        $invite->expires_on = $current->copy()->addDays(config('other.invite_expire'));
        $invite->custom = $request->input('message');

        if (config('email-white-blacklist.enabled') === 'allow') {
            $v = validator($invite->toArray(), [
            'email'  => 'required|email|unique:users|email_list:allow', // Whitelist
            'custom' => 'required',
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            $v = validator($invite->toArray(), [
            'email'  => 'required|email|unique:users|email_list:block', // Blacklist
            'custom' => 'required',
            ]);
        } else {
            $v = validator($invite->toArray(), [
            'email'  => 'required|email|unique:users', // Default
            'custom' => 'required',
            ]);
        }

        if ($v->fails()) {
            return redirect()->route('invite')
                ->withErrors($v->errors());
        } else {
            Mail::to($request->input('email'))->send(new InviteUser($invite));
            $invite->save();

            $user->invites -= 1;
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has sent a invite to {$invite->email} .");

            return redirect()->route('invite')
                ->withSuccess('Invite was sent successfully!');
        }
    }

    /**
     * Resend Invite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function reProcess(Request $request, $id)
    {
        $user = $request->user();
        $invite = Invite::findOrFail($id);

        abort_unless($invite->user_id === $user->id, 403);

        if ($invite->accepted_by !== null) {
            return redirect()->route('user_invites', ['id' => $user->id])
                ->withErrors('The invite you are trying to resend has already been used.');
        }

        Mail::to($invite->email)->send(new InviteUser($invite));

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has resent invite to {$invite->email} .");

        return redirect()->route('user_invites', ['id' => $user->id])
            ->withSuccess('Invite was resent successfully!');
    }

    /**
     * Invite Tree.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invites(Request $request, $username)
    {
        $user = $request->user();
        $owner = User::where('username', '=', $username)->firstOrFail();
        abort_unless($user->group->is_modo || $user->id === $owner->id, 403);

        $invites = Invite::with(['sender', 'receiver'])->where('user_id', '=', $owner->id)->latest()->paginate(25);

        return view('user.invites', ['owner' => $owner, 'invites' => $invites, 'route' => 'invite']);
    }
}
