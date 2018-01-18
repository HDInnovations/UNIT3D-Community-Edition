<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Mail\InviteUser;
use App\User;
use App\Invite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use Illuminate\Support\Facades\Input;

use \Toastr;
use Carbon\Carbon;

class InviteController extends Controller
{

    public function invite()
    {
        $user = Auth::user();
        if (config('other.invite-only') == false) {
            Toastr::warning('Invitations Are Disabled Due To Open Registration!', 'Error!', ['options']);
        }
        if ($user->can_invite == 0) {
            Toastr::warning('Your Invite Rights Have Been Revoked!!!', 'Error!', ['options']);
        }
        return view('user.invite', ['user' => $user]);
    }

    public function process(Request $request)
    {
        $current = new Carbon();
        $user = Auth::user();
        $exsist = Invite::where('email', '=', $request->get('email'))->first();
        $member = User::where('email', '=', $request->get('email'))->first();
        if ($exsist || $member) {
            return Redirect::route('profil', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('The email address your trying to send a invite to has already been sent one or is a user already.', 'My Dude!', ['options']));
        }

        if ($user->invites > 0) {
            do {
                //generate a random string using Laravel's str_random helper
                $code = str_random();
            } //check if the token already exists and if it does, try again
            while (Invite::where('code', $code)->first()) {
            }

            //create a new invite record
            $invite = Invite::create([
                'user_id' => $user->id,
                'email' => $request->get('email'),
                'code' => $code,
                'expires_on' => $current->copy()->addDays(14),
                'custom' => $request->get('message'),
            ]);

            // send the email
            Mail::to($request->get('email'))->send(new InviteUser($invite));

            // subtract 1 invite
            $user->invites -= 1;
            $user->save();

            Toastr::success('Invitation Sent Successfully!', 'Yay!', ['options']);
        } else {
            Toastr::warning('You Dont Have Enough Invites!', 'Umm!', ['options']);
        }
        // redirect back where we came from
        return redirect()->back();
    }

    public function inviteTree($username, $id)
    {
        if (Auth::user()->group->is_modo) {
            $user = User::findOrFail($id);
            $records = Invite::with('sender')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $user = Auth::user();
            $records = Invite::with('sender')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }
        return view('user.invitetree', ['user' => $user, 'records' => $records]);
    }
}
