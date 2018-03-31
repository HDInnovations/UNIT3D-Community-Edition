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

    public function invite()
    {
        $user = auth()->user();
        if (config('other.invite-only') == false) {
            Toastr::error('Invitations Are Disabled Due To Open Registration!', 'Whoops!', ['options']);
        }
        if ($user->can_invite == 0) {
            Toastr::error('Your Invite Rights Have Been Revoked!!!', 'Whoops!', ['options']);
        }
        return view('user.invite', ['user' => $user]);
    }

    public function process(Request $request)
    {
        $current = new Carbon();
        $user = auth()->user();
        $invites_restricted = config('config.invites_restriced', false);
        $invite_groups = config('config.invite_groups', []);
        if ($invites_restricted && !in_array($user->group->name, $invite_groups)) {
            return redirect()->route('invite')->with(Toastr::error('Invites are currently disabled for your userclass.', 'Whoops!', ['options']));
        }
        $exsist = Invite::where('email', $request->input('email'))->first();
        $member = User::where('email', $request->input('email'))->first();
        if ($exsist || $member) {
            return redirect()->route('invite')->with(Toastr::error('The email address your trying to send a invite to has already been sent one or is a user already.', 'Whoops!', ['options']));
        }

        if ($user->invites > 0) {
            // Generate a version 4, truly random, UUID
            $code = Uuid::uuid4()->toString();

            //create a new invite record
            $invite = Invite::create([
                'user_id' => $user->id,
                'email' => $request->input('email'),
                'code' => $code,
                'expires_on' => $current->copy()->addDays(config('other.invite_expire')),
                'custom' => $request->input('message'),
            ]);

            // send the email
            Mail::to($request->input('email'))->send(new InviteUser($invite));

            // subtract 1 invite
            $user->invites -= 1;
            $user->save();

            return redirect()->route('invite')->with(Toastr::success('Invite was sent successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->route('invite')->with(Toastr::error('You do not have enough invites!', 'Whoops!', ['options']));
        }
    }

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
