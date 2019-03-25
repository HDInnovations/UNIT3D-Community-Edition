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

namespace App\Http\Controllers\Staff;

use App\Models\User;
use App\Jobs\ProcessMassPM;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MassPMController extends Controller
{
    /**
     * Mass PM Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function massPM()
    {
        return view('Staff.masspm.index');
    }

    /**
     * Send The Mass PM.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendMassPM(Request $request)
    {
        $staff = auth()->user();
        $users = User::all();

        $sender_id = 1;
        $subject = $request->input('subject');
        $message = $request->input('message');

        $v = validator($request->all(), [
            'subject' => 'required|min:5',
            'message' => 'required|min:5',
        ]);

        if ($v->fails()) {
            return redirect()->route('massPM')
                ->withErrors($v->errors());
        } else {
            foreach ($users as $user) {
                $this->dispatch(new ProcessMassPM($sender_id, $user->id, $subject, $message));
            }

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has sent a MassPM.");

            return redirect()->route('massPM')
                ->withSuccess('MassPM Sent');
        }
    }
}
