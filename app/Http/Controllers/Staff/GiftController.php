<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GiftController extends Controller
{
    /**
     * Send Gift Form.
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('Staff.gift.index');
    }

    /**
     * Send The Gift.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $staff = $request->user();

        $username = $request->input('username');
        $seedbonus = $request->input('seedbonus');
        $invites = $request->input('invites');
        $fl_tokens = $request->input('fl_tokens');

        $v = validator($request->all(), [
            'username'  => 'required|exists:users,username|max:180',
            'seedbonus' => 'required|numeric|min:0',
            'invites'   => 'required|numeric|min:0',
            'fl_tokens' => 'required|numeric|min:0',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.gifts.index')
                ->withErrors($v->errors());
        }
        $recipient = User::where('username', '=', $username)->first();
        if (!$recipient) {
            return redirect()->route('staff.gifts.index')
                ->withErrors('Unable To Find Specified User');
        }
        $recipient->seedbonus += $seedbonus;
        $recipient->invites += $invites;
        $recipient->fl_tokens += $fl_tokens;
        $recipient->save();
        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = 1;
        $pm->receiver_id = $recipient->id;
        $pm->subject = 'You Have Received A System Generated Gift';
        $pm->message = sprintf('We just wanted to let you know that staff member, %s, has credited your account with %s Bonus Points, %s Invites and %s Freeleech Tokens.
                                [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]', $staff->username, $seedbonus, $invites, $fl_tokens);
        $pm->save();

        return redirect()->route('staff.gifts.index')
            ->withSuccess('Gift Sent');
    }
}
