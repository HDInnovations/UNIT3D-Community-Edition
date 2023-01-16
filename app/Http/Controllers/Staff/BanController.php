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
use App\Http\Requests\Staff\StoreBanRequest;
use App\Http\Requests\Staff\UpdateBanRequest;
use App\Models\Ban;
use App\Models\Group;
use App\Models\User;
use App\Notifications\UserBan;
use App\Notifications\UserBanExpire;
use Illuminate\Support\Carbon;

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
    public function store(StoreBanRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $staff = $request->user();
        $bannedGroup = \cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));

        // \abort_if($user->group->is_modo || $request->user()->id == $user->id, 403);

        $user->update([
            'group_id'     => $bannedGroup[0],
            'can_upload'   => 0,
            'can_download' => 0,
            'can_comment'  => 0,
            'can_invite'   => 0,
            'can_request'  => 0,
            'can_chat'     => 0,
        ]);

        $ban = Ban::create([
            'owned_by' => $user->id,
            'created_by' => $staff->id,
            'ban_reason' => $request->ban_reason,
        ]);

        \cache()->forget('user:'.$user->passkey);

        // Send Notifications
        $user->notify(new UserBan($ban));

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('User Is Now Banned!');
    }

    /**
     * Unban A User (banned -> new_group).
     */
    public function update(UpdateBanRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $staff = $request->user();

        \abort_if($user->group->is_modo || $request->user()->id == $user->id, 403);

        $user->update([
            'group_id'     => $request->group_id,
            'can_upload'   => 1,
            'can_download' => 1,
            'can_comment'  => 1,
            'can_invite'   => 1,
            'can_request'  => 1,
            'can_chat'     => 1,
        ]);

        Ban::create([
            'owned_by' => $user->id,
            'created_by' => $staff->id,
            'unban_reason' => $request->unban_reason,
            'removed_at' => Carbon::now(),
        ]);

        \cache()->forget('user:'.$user->passkey);

        // Send Notifications
        $user->notify(new UserBanExpire());

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('User Is Now Relieved Of His Ban!');
    }
}
