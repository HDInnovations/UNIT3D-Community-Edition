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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
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
        $users = User::oldest('username')->get();
        return view('Staff.gift.index', compact('users'));
    }

    /**
     * @method gift
     *
     * @access public
     *
     * @return void
     */
    public function gift(Request $request)
    {
        $user = auth()->user();

        if ($request->isMethod('POST')) {
            $v = validator($request->all(), [
                'username' => "required|exists:users,username|max:180",
                'bonus_points' => "required|numeric|min:0",
                'invites' => "required|numeric|min:0",
                'fl_tokens' => "required|numeric|min:0"
            ]);

            if ($v->passes()) {
                $recipient = User::where('username', 'LIKE', $request->input('username'))->first();

                if (!$recipient) {
                    return redirect('/staff_dashboard/systemgift')->with(Toastr::error('Unable to find specified user', 'Whoops!', ['options']));
                }

                $bon = $request->input('bonus_points');
                $invites = $request->input('invites');
                $fl_tokens = $request->input('fl_tokens');
                $recipient->seedbonus += $bon;
                $recipient->invites += $invites;
                $recipient->fl_tokens += $fl_tokens;
                $recipient->save();

                // Activity Log
                \LogActivity::addToLog("Staff Member {$user->username} has sent a system gift to {$recipient->username} account.");

                return redirect('/staff_dashboard/systemgift')->with(Toastr::success('Gift Sent', 'Yay!', ['options']));
            } else {
                return redirect('/staff_dashboard/systemgift')->with(Toastr::error('Gift Failed', 'Whoops!', ['options']));
            }
        } else {
            return redirect('/staff_dashboard/systemgift')->with(Toastr::error('Unknown error occurred', 'Whoops!', ['options']));
        }
    }
}
