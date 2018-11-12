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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Invite;
use App\Mail\InviteUser;
use Brian2694\Toastr\Toastr;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class InviteController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * InviteController Constructor
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Invite Form
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
        return view('user.invite', ['user' => $user]);
    }

    /**
     * Send Invite
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $current = new Carbon();
        $user = auth()->user();
        $invites_restricted = config('other.invites_restriced', false);
        $invite_groups = config('other.invite_groups', []);

        if ($invites_restricted && !in_array($user->group->name, $invite_groups)) {
            return redirect()->route('invite')
                ->with($this->toastr->error('Invites are currently disabled for your group.', 'Whoops!', ['options']));
        }

        if ($user->invites <= 0) {
            return redirect()->route('invite')
                ->with($this->toastr->error('You do not have enough invites!', 'Whoops!', ['options']));
        }

        $exist = Invite::where('email', $request->input('email'))->first();

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
            "email" => "required|email|unique:users|email_list:allow", // Whitelist
            "custom" => "required"
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            $v = validator($invite->toArray(), [
            "email" => "required|email|unique:users|email_list:block", // Blacklist
            "custom" => "required"
            ]);
        } else {
            $v = validator($invite->toArray(), [
            "email" => "required|email|unique:users", // Default
            "custom" => "required"
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
     * Invite Tree
     *
     * @param $username
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inviteTree($username, $id)
    {
        if (auth()->user()->group->is_modo) {
            $user = User::findOrFail($id);
            $records = Invite::with(['sender', 'receiver'])->where('user_id', $user->id)->latest()->paginate(25);
        } else {
            $user = auth()->user();
            $records = Invite::with(['sender', 'receiver'])->where('user_id', $user->id)->latest()->paginate(25);
        }
        return view('user.invitetree', ['user' => $user, 'records' => $records]);
    }
}
