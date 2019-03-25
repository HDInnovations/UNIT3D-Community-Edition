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

use Carbon\Carbon;
use App\Models\Ban;
use App\Models\User;
use App\Mail\BanUser;
use App\Models\Group;
use App\Mail\UnbanUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class BanController extends Controller
{
    /**
     * Get All Bans.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBans()
    {
        $bans = Ban::latest()->paginate(25);

        return view('Staff.bans.index', ['bans' => $bans]);
    }

    /**
     * Ban A User (current_group -> banned).
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function ban(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();
        $bannedGroup = Group::select(['id'])->where('slug', '=', 'banned')->first();

        abort_if($user->group->is_modo || auth()->user()->id == $user->id, 403);

        $user->group_id = $bannedGroup->id;
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
            'ban_reason' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withErrors($v->errors());
        } else {
            $user->save();
            $ban->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has banned member {$user->username}.");

            // Send Email
            Mail::to($user->email)->send(new BanUser($user->email, $ban));

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withSuccess('User Is Now Banned!');
        }
    }

    /**
     * Unban A User (banned -> new_group).
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unban(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        abort_if($user->group->is_modo || auth()->user()->id == $user->id, 403);

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
            'group_id'     => 'required',
            'unban_reason' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withErrors($v->errors());
        } else {
            $user->save();
            $ban->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has unbanned member {$user->username}.");

            // Send Email
            Mail::to($user->email)->send(new UnbanUser($user->email, $ban));

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withSuccess('User Is Now Relieved Of His Ban!');
        }
    }
}
