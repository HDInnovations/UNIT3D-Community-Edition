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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

use \Toastr;

class GiftController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('username', 'ASC')->get();
        return view('Staff.gift.index', compact('users'));
    }

    /**
     * @method gift
     *
     * @access public
     *
     * @return void
     */
    public function gift()
    {
        $user = Auth::user();

        if (Request::isMethod('post')) {
            $v = Validator::make(Request::all(), [
                'username' => "required|exists:users,username|max:180",
                'bonus_points' => "required|numeric|min:0",
                'invites' => "required|numeric|min:0"
            ]);

            if ($v->passes()) {
                $recipient = User::where('username', 'LIKE', Request::get('username'))->first();

                if (!$recipient) {
                    return Redirect::to('/staff_dashboard/systemgift')->with(Toastr::error('Unable to find specified user', 'Gifting Failed', ['options']));
                }

                $bon = Request::get('bonus_points');
                $invites = Request::get('invites');
                $recipient->seedbonus += $bon;
                $recipient->invites += $invites;
                $recipient->save();

                // Activity Log
                \LogActivity::addToLog("Staff Member " . $user->username . " has sent a system gift to " . $recipient->username . " account.");

                return Redirect::to('/staff_dashboard/systemgift')->with(Toastr::info('Sent', 'Gift', ['options']));
            } else {
                return Redirect::to('/staff_dashboard/systemgift')->with(Toastr::error('Failed', 'Gifting', ['options']));
            }
        } else {
            return Redirect::to('/staff_dashboard/systemgift')->with(Toastr::error('Unknown error occurred', 'Error!', ['options']));
        }
    }
}
