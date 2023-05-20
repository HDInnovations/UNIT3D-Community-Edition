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

class TwoStepController extends Controller
{
    /**
     * Update user two step auth status.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->id == $user->id || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->id !== $user->id;

        abort_if($changedByStaff && ! $request->user()->group->is_owner && $request->user()->group->level < $user->group->level, 403);

        $request->validate([
            'twostep' => 'required|boolean',
        ]);

        $user->twostep = $request->twostep;
        $user->save();

        if ($changedByStaff) {
            PrivateMessage::create([
                'sender_id'   => 1,
                'receiver_id' => $user->id,
                'subject'     => 'ATTENTION - Your two step authentication status has been changed',
                'message'     => "Your two step authentication status has been changed by staff.\n\nFor more information, please create a helpdesk ticket.\n\n[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]",
            ]);
        }

        return to_route('users.two_step.edit', ['user' => $user])
            ->withSuccess('You changed your two-step authentication Status.');
    }

    /**
     * Edit user two step auth status.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->id == $user->id || $request->user()->group->is_modo, 403);

        return view('user.two_step.edit', ['user' => $user]);
    }
}
