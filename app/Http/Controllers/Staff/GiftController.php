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
use App\Http\Requests\Staff\StoreGiftRequest;
use App\Models\PrivateMessage;
use App\Models\User;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\GiftControllerTest
 */
class GiftController extends Controller
{
    /**
     * Send Gift Form.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.gift.index');
    }

    /**
     * Send The Gift.
     */
    public function store(StoreGiftRequest $request): \Illuminate\Http\RedirectResponse
    {
        $staff = $request->user();
        $recipient = User::where('username', '=', $request->username)->sole();

        $recipient->seedbonus += $request->seedbonus;
        $recipient->invites += $request->invites;
        $recipient->fl_tokens += $request->fl_tokens;
        $recipient->save();

        PrivateMessage::create([
            'sender_id'   => 1,
            'receiver_id' => $recipient->id,
            'subject'     => 'You Have Received A System Generated Gift',
            'message'     => \sprintf('We just wanted to let you know that staff member, %s, has credited your account with %s Bonus Points, %s Invites and %s Freeleech Tokens.
            [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]', $staff->username, $request->seedbonus, $request->invites, $request->fl_tokens)
        ]);

        return \to_route('staff.gifts.index')
            ->withSuccess('Gift Sent');
    }
}
