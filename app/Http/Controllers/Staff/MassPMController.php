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
use App\PrivateMessage;
use \Toastr;

class MassPMController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function massPM()
    {
        return view('Staff.masspm.index');
    }

    /**
     * @method gift
     *
     * @access public
     *
     * @return void
     */
    public function sendMassPM(Request $request)
    {
        $staff = auth()->user();
        $users = User::all();

        if ($request->isMethod('POST')) {
            $v = validator($request->all(), [
                'title' => "required|min:5",
                'message' => "required|min:5"
            ]);

            if ($v->passes()) {
                foreach ($users as $user) {
                    PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $user->id, 'subject' => $request->input('title'), 'message' => $request->input('message')]);
                }

                // Activity Log
                \LogActivity::addToLog("Staff Member {$staff->username} has sent a MassPM.");

                return redirect('/staff_dashboard/masspm')->with(Toastr::success('MassPM Sent', 'Yay!', ['options']));
            } else {
                return redirect('/staff_dashboard/masspm')->with(Toastr::error('MassPM Failed', 'Whoops!', ['options']));
            }
        } else {
            return redirect('/staff_dashboard/masspm')->with(Toastr::error('Unknown error occurred', 'Whoops!', ['options']));
        }
    }
}
