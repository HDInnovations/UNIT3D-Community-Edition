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
use App\Rules\EmailBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmailController extends Controller
{
    /**
     * Update user email.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->isNot($user);

        abort_if($changedByStaff && !$request->user()->group->is_owner && $request->user()->group->level <= $user->group->level, 403);

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:70',
                'unique:users',
                Rule::when(config('email-blacklist.enabled'), fn () => new EmailBlacklist()),
            ],
        ]);

        DB::transaction(function () use ($request, $user, $changedByStaff): void {
            $user->emailUpdates()->latest()->first()?->update(['deleted_at' => now()]);

            $user->update([
                'email' => $request->email,
            ]);

            $user->emailUpdates()->create();

            if ($changedByStaff) {
                PrivateMessage::create([
                    'sender_id'   => 1,
                    'receiver_id' => $user->id,
                    'subject'     => 'ATTENTION - Your email has been changed',
                    'message'     => "Your email has been changed by staff.\n\nFor more information, please create a helpdesk ticket.\n\n[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]",
                ]);
            }
        });

        return to_route('users.email.edit', ['user' => $user])
            ->withSuccess('Your email was updated successfully.');
    }

    /**
     * Edit user email.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.email.edit', [
            'user'         => $user,
            'emailUpdates' => $user->emailUpdates()->latest()->get(),
        ]);
    }
}
