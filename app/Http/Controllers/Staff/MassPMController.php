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
     * Mass PM Form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function massPM()
    {
        return view('Staff.masspm.index');
    }

    /**
     * Send The Mass PM
     *
     * @param Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendMassPM(Request $request)
    {
        $staff = auth()->user();
        $users = User::all();

        $subject = $request->input('subject');
        $message = $request->input('message');


        $v = validator($request->all(), [
            'subject' => "required|min:5",
            'message' => "required|min:5"
        ]);

        if ($v->fails()) {
            return redirect()->route('massPM')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            foreach ($users as $user) {
                PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $user->id, 'subject' => $subject, 'message' => $message]);
            }

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has sent a MassPM.");

            return redirect()->route('massPM')
                ->with(Toastr::success('MassPM Sent', 'Yay!', ['options']));
        }
    }
}
