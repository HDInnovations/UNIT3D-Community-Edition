<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApikeyController extends Controller
{
    /**
     * Update user apikey.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->isNot($user);

        abort_if($changedByStaff && !$request->user()->group->is_owner && $request->user()->group->level <= $user->group->level, 403);

        DB::transaction(function () use ($user, $changedByStaff): void {
            $user->apikeys()->latest()->first()?->update(['deleted_at' => now()]);

            $user->update([
                'api_token' => Str::random(100),
            ]);

            $user->apikeys()->create(['content' => $user->api_token]);

            if ($changedByStaff) {
                PrivateMessage::create([
                    'sender_id'   => 1,
                    'receiver_id' => $user->id,
                    'subject'     => 'ATTENTION - Your API key has been reset',
                    'message'     => "Your API key has been reset by staff. You will need to update your API key in all your scripts to continue using the API.\n\nFor more information, please create a helpdesk ticket.\n\n[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]",
                ]);
            }
        });

        return to_route('users.apikeys.index', ['user' => $user])
            ->withSuccess('Your API key was changed successfully.');
    }

    /**
     * Edit user apikey.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.apikey.index', [
            'user'    => $user,
            'apikeys' => $user->apikeys()->latest()->get()
        ]);
    }
}
