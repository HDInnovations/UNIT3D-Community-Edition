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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Ban;
use Carbon\Carbon;
use \Toastr;

class BanController extends Controller
{
    public function getBans()
    {
        $bans = Ban::orderBy('created_at', 'DESC')->get();

        return view('Staff.bans.index', ['bans' => $bans]);
    }

    /**
     * Ban the user (current_group -> banned)
     *
     * @access public
     * @param $username
     * @param $id
     *
     */
    public function ban($username, $id)
    {
        $user = User::findOrFail($id);
        $user->group_id = 5;
        $user->can_upload = 0;
        $user->can_download = 0;
        $user->can_comment = 0;
        $user->can_invite = 0;
        $user->can_request = 0;
        $user->can_chat = 0;
        $user->save();

        $staff = Auth::user();
        $v = Validator::make(Request::all(), [
            'owned_by' => 'required',
            'created_by' => 'required|numeric',
            'ban_reason' => 'required',
        ]);

        $ban = new Ban();
        $ban->owned_by = $user->id;
        $ban->created_by = $staff->id;
        $ban->ban_reason = Request::get('ban_reason');
        $ban->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member " . $staff->username . " has banned member " . $user->username . ".");

        return redirect()->back()->with(Toastr::success('User Is Now Banned!', 'Alert', ['options']));
    }


    /**
     * Unban the user (banned -> new group)
     *
     * @access public
     * @param $username
     * @param $id
     *
     */
    public function unban($username, $id)
    {
        $user = User::findOrFail($id);
        $user->group_id = Request::get('group_id');
        $user->can_upload = 1;
        $user->can_download = 1;
        $user->can_comment = 1;
        $user->can_invite = 1;
        $user->can_request = 1;
        $user->can_chat = 1;
        $user->save();

        $staff = Auth::user();
        $v = Validator::make(Request::all(), [
            'unban_reason' => 'required',
            'removed_at' => 'required'
        ]);

        $ban = new Ban();
        $ban->owned_by = $user->id;
        $ban->created_by = $staff->id;
        $ban->unban_reason = Request::get('unban_reason');
        $ban->removed_at = Carbon::now();
        $ban->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member " . $staff->username . " has unbanned member " . $user->username . ".");

        return redirect()->back()->with(Toastr::success('User Is Now Relieved Of His Ban!', 'Alert', ['options']));
    }
}
