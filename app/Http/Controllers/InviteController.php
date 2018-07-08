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
use \Toastr;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class InviteController extends Controller
{
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
            ->with(Toastr::error('Invitations Are Disabled Due To Open Registration!', 'Whoops!', ['options']));
        }
        if ($user->can_invite == 0) {
            return redirect()->route('home')
            ->with(Toastr::error('Your Invite Rights Have Been Revoked!!!', 'Whoops!', ['options']));
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
                ->with(Toastr::error('Invites are currently disabled for your group.', 'Whoops!', ['options']));
        }

        if ($user->invites <= 0) {
            return redirect()->route('invite')
                ->with(Toastr::error('You do not have enough invites!', 'Whoops!', ['options']));
        }

        $exist = Invite::where('email', $request->input('email'))->first();
        $member = User::where('email', $request->input('email'))->first();

        if ($exist || $member) {
            return redirect()->route('invite')
                ->with(Toastr::error('The email address your trying to send a invite to has already been sent one or is a used already.', 'Whoops!', ['options']));
        }

        $code = Uuid::uuid4()->toString();
        $invite = new Invite();
        $invite->user_id = $user->id;
        $invite->email = $request->input('email');
        $invite->code = $code;
        $invite->expires_on = $current->copy()->addDays(config('other.invite_expire'));
        $invite->custom = $request->input('message');

            if (config('email-white-blacklist.enabled') === 'allow'){
                $v = validator($invite->toArray(), [
                "email" => "required|email|email_list:allow", // Whitelist
                "custom" => "required"
                ]);
            } elseif (config('email-white-blacklist.enabled') === 'block') {
                $v = validator($invite->toArray(), [
                    "email" => "required|email|email_list:block", // Blacklist
                    "custom" => "required"
                ]);
            } else {
                $v = validator($invite->toArray(), [
                    "email" => "required|email", // Default
                    "custom" => "required"
                ]);
            }


        if ($v->fails()) {
            return redirect()->route('invite')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            Mail::to($request->input('email'))->send(new InviteUser($invite));
            $invite->save();

            $user->invites -= 1;
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has sent a invite to {$invite->email} .");

            return redirect()->route('invite')
                ->with(Toastr::success('Invite was sent successfully!', 'Yay!', ['options']));
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
            $records = Invite::with('sender')->where('user_id', $user->id)->latest()->get();
        } else {
            $user = auth()->user();
            $records = Invite::with('sender')->where('user_id', $user->id)->latest()->get();
        }
        return view('user.invitetree', ['user' => $user, 'records' => $records]);
    }
}
