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
use Illuminate\Support\Facades\Mail;
use App\Mail\BanUser;
use App\Mail\UnbanUser;
use App\User;
use App\Ban;
use Carbon\Carbon;
use \Toastr;

class BanController extends Controller
{
    /**
     * Get All Bans
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBans()
    {
        $bans = Ban::latest()->paginate(25);

        return view('Staff.bans.index', ['bans' => $bans]);
    }

    /**
     * Ban the user (current_group -> banned)
     *
     * @param Request $request
     * @param $username
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function ban(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        if ($user->group->is_modo || auth()->user()->id == $user->id) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with(Toastr::error('You Cannot Ban Yourself Or Other Staff!', 'Whoops!', ['options']));
        } else {
            $user->group_id = 5;
            $user->can_upload = 0;
            $user->can_download = 0;
            $user->can_comment = 0;
            $user->can_invite = 0;
            $user->can_request = 0;
            $user->can_chat = 0;

            $ban = new Ban();
            $ban->owned_by = $user->id;
            $ban->created_by = $staff->id;
            $ban->ban_reason = $request->input('ban_reason');

            $v = validator($ban->toArray(), [
                'ban_reason' => 'required'
            ]);

            if ($v->fails()) {
                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                    ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
            } else {
                $user->save();
                $ban->save();

                // Activity Log
                \LogActivity::addToLog("Staff Member {$staff->username} has banned member {$user->username}.");

                // Send Email
                Mail::to($user->email)->send(new BanUser($user));

                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                    ->with(Toastr::success('User Is Now Banned!', 'Yay!', ['options']));
            }
        }
    }


    /**
     * Unban the user (banned -> new group)
     *
     * @param Request $request
     * @param $username
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function unban(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        if ($user->group->is_modo || auth()->user()->id == $user->id) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with(Toastr::error('You Cannot Unban Yourself Or Other Staff!', 'Whoops!', ['options']));
        } else {
            $user->group_id = $request->input('group_id');
            $user->can_upload = 1;
            $user->can_download = 1;
            $user->can_comment = 1;
            $user->can_invite = 1;
            $user->can_request = 1;
            $user->can_chat = 1;

            $ban = new Ban();
            $ban->owned_by = $user->id;
            $ban->created_by = $staff->id;
            $ban->unban_reason = $request->input('unban_reason');
            $ban->removed_at = Carbon::now();

            $v = validator($request->all(), [
                'group_id' => 'required',
                'unban_reason' => 'required'
            ]);

            if ($v->fails()) {
                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                    ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
            } else {
                $user->save();
                $ban->save();

                // Activity Log
                \LogActivity::addToLog("Staff Member {$staff->username} has unbanned member {$user->username}.");

                // Send Email
                Mail::to($user->email)->send(new UnbanUser($user));

                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                    ->with(Toastr::success('User Is Now Relieved Of His Ban!', 'Yay!', ['options']));
            }
        }
    }
}
