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
use App\Models\Invite;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Mail\InviteUser;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InviteController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * InviteController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Invite Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invite()
    {
        $user = auth()->user();

        if (config('other.invite-only') == false) {
            return redirect()->route('home')
            ->with($this->toastr->error('Invitations Are Disabled Due To Open Registration!', 'Whoops!', ['options']));
        }
        if ($user->can_invite == 0) {
            return redirect()->route('home')
            ->with($this->toastr->error('Your Invite Rights Have Been Revoked!!!', 'Whoops!', ['options']));
        }
        if (config('other.invites_restriced') == true && ! in_array($user->group->name, config('other.invite_groups'))) {
            return redirect()->route('home')
                ->with($this->toastr->error('Invites are currently disabled for your group.', 'Whoops!', ['options']));
        }

        return view('user.invite', ['user' => $user, 'route' => 'invite']);
    }

    /**
     * Send Invite.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $current = new Carbon();
        $user = auth()->user();

        if (config('other.invites_restriced') == true && ! in_array($user->group->name, config('other.invite_groups'))) {
            return redirect()->route('home')
                ->with($this->toastr->error('Invites are currently disabled for your group.', 'Whoops!', ['options']));
        }

        if ($user->invites <= 0) {
            return redirect()->route('invite')
                ->with($this->toastr->error('You do not have enough invites!', 'Whoops!', ['options']));
        }

        $exist = Invite::where('email', '=', $request->input('email'))->first();

        if ($exist) {
            return redirect()->route('invite')
                ->with($this->toastr->error('The email address your trying to send a invite to has already been sent one.', 'Whoops!', ['options']));
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
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            Mail::to($request->input('email'))->send(new InviteUser($invite));
            $invite->save();

            $user->invites -= 1;
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has sent a invite to {$invite->email} .");

            return redirect()->route('invite')
                ->with($this->toastr->success('Invite was sent successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Resend Invite.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function reProcess($id)
    {
        $user = auth()->user();
        $invite = Invite::findOrFail($id);

        abort_unless($invite->user_id === $user->id, 403);

        if ($invite->accepted_by !== null) {
            return redirect()->back()
                ->with($this->toastr->error('The invite you are trying to resend has already been used.', 'Whoops!', ['options']));
        }

        Mail::to($invite->email)->send(new InviteUser($invite));

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has resent invite to {$invite->email} .");

        return redirect()->back()
            ->with($this->toastr->success('Invite was resent successfully!', 'Yay!', ['options']));
    }

    /**
     * Invite Tree.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invites($username, $id)
    {
        $user = auth()->user();
        $owner = User::findOrFail($id);
        abort_unless($user->group->is_modo || $user->id === $owner->id, 403);

        $invites = Invite::with(['sender', 'receiver'])->where('user_id', '=', $id)->latest()->paginate(25);

        return view('user.invites', ['owner' => $owner, 'invites' => $invites, 'route' => 'invite']);
    }
}
