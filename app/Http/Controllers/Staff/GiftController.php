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

use App\Models\User;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use App\Http\Controllers\Controller;

class GiftController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * GiftController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Send Gift Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::oldest('username')->get();

        return view('Staff.gift.index', ['users' => $users]);
    }

    /**
     * Send The Gift.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function gift(Request $request)
    {
        $staff = auth()->user();

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
            return redirect()->route('systemGift')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $recipient = User::where('username', 'LIKE', $username)->first();

            if (! $recipient) {
                return redirect()->route('systemGift')
                    ->with($this->toastr->error('Unable To Find Specified User', 'Whoops!', ['options']));
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
            $pm->message = "We just wanted to let you know that staff member, {$staff->username}, has credited your account with {$seedbonus} Bonus Points, {$invites} Invites and {$fl_tokens} Freeleech Tokens.
                                [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]";
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has sent a system gift to {$recipient->username} account.");

            return redirect()->route('systemGift')
                ->with($this->toastr->success('Gift Sent', 'Yay!', ['options']));
        }
    }
}
