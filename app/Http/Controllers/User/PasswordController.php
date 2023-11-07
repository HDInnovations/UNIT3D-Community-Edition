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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update user password.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->isNot($user);

        abort_if($changedByStaff && !$request->user()->group->is_owner && $request->user()->group->level <= $user->group->level, 403);

        $request->validate([
            'current_password' => Rule::when(!$changedByStaff, [
                'required',
                'current_password',
            ]),
            'new_password' => [
                'required',
                'confirmed',
                Password::min(12)->mixedCase()->letters()->numbers()->uncompromised(),
            ],
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($changedByStaff) {
            PrivateMessage::create([
                'sender_id'   => 1,
                'receiver_id' => $user->id,
                'subject'     => 'ATTENTION - Your password has been changed',
                'message'     => "Your password has been changed by staff. You will need to update your password manager with the new password.\n\nFor more information, please create a helpdesk ticket.\n\n[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]",
            ]);
        }

        return to_route('users.password.edit', ['user' => $user])
            ->withSuccess('Your new password has been saved successfully.');
    }

    /**
     * Edit user password.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.password.edit', ['user' => $user]);
    }
}
