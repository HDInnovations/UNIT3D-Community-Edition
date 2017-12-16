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
use App\PrivateMessage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

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
    public function sendMassPM()
    {
        $staff = Auth::user();
        $users = User::all();

        if (Request::isMethod('post')) {
            $v = Validator::make(Request::all(), [
                'title' => "required|min:5",
                'message' => "required|min:5"
            ]);

            if ($v->passes()) {

                foreach ($users as $user) {
                    PrivateMessage::create(['sender_id' => "0", 'reciever_id' => $user->id, 'subject' => Request::get('title'), 'message' => Request::get('message')]);
                }

                // Activity Log
                \LogActivity::addToLog("Staff Member " . $staff->username . " has sent a MassPM.");

                return Redirect::to('/staff_dashboard/masspm')->with(Toastr::info('Sent', 'MassPM', ['options']));
            } else {
                return Redirect::to('/staff_dashboard/masspm')->with(Toastr::error('Failed', 'MassPM', ['options']));
            }
        } else {
            return Redirect::to('/staff_dashboard/masspm')->with(Toastr::error('Unknown error occurred', 'Error!', ['options']));
        }
    }
}
