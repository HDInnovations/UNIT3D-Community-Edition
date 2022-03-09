<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Mail\BanUser;
use App\Mail\UnbanUser;
use App\Models\Ban;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\BanControllerTest
 */
class BanController extends Controller
{
    /**
     * Display All Bans.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $bans = Ban::latest()->paginate(25);

        return \view('Staff.ban.index', ['bans' => $bans]);
    }

    /**
     * Ban A User (current_group -> banned).
     *
     * @throws \Exception
     */
    public function store(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $staff = $request->user();
        $canLogin = Privilege::where('slug', 'can_login')->firstOrFail();
        $activeUser = Privilege::where('slug', 'active_user')->firstOrFail();
        $banned = Role::where('slug', 'banned')->firstOrFail();
        \abort_if($user->hasPrivilegeTo('user_special_staff') ||
            ! $staff->hasPrivilegeTo('users_give_infractions') ||
            $request->user()->id == $user->id, 403);

        $user->UserRestrictedPrivileges()->attach($canLogin);
        $user->UserRestrictedPrivileges()->attach($activeUser);
        $user->roles()->attach($banned);

        $ban = new Ban();
        $ban->owned_by = $user->id;
        $ban->created_by = $staff->id;
        $ban->ban_reason = $request->input('ban_reason');

        $v = \validator($ban->toArray(), [
            'ban_reason' => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('users.show', ['username' => $user->username])
                ->withErrors($v->errors());
        }

        $user->save();
        $ban->save();
        // Send Email
        Mail::to($user->email)->send(new BanUser($user->email, $ban));

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('User Is Now Banned!');
    }

    /**
     * Unban A User (banned -> new_group).
     */
    public function update(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $staff = $request->user();
        $canLogin = Privilege::where('slug', 'can_login')->firstOrFail();
        $activeUser = Privilege::where('slug', 'active_user')->firstOrFail();
        $banned = Role::where('slug', 'banned')->firstOrFail();

        \abort_if($user->hasPrivilegeTo('user_special_staff') ||
            ! $staff->hasPrivilegeTo('users_edit_infractions') ||
            $request->user()->id == $user->id, 403);

        $user->UserRestrictedPrivileges()->detach($canLogin);
        $user->UserRestrictedPrivileges()->detach($activeUser);
        $user->roles()->detach($banned);

        $ban = new Ban();
        $ban->owned_by = $user->id;
        $ban->created_by = $staff->id;
        $ban->unban_reason = $request->input('unban_reason');
        $ban->removed_at = Carbon::now();

        $v = \validator($request->all(), [
            'group_id'     => 'required',
            'unban_reason' => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('users.show', ['username' => $user->username])
                ->withErrors($v->errors());
        }

        $user->save();
        $ban->save();
        // Send Email
        Mail::to($user->email)->send(new UnbanUser($user->email, $ban));

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('User Is Now Relieved Of His Ban!');
    }
}
