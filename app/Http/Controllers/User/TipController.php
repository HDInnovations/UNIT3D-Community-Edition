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
use App\Http\Requests\StoreTipRequest;
use App\Models\BonTransactions;
use App\Models\Post;
use App\Models\Torrent;
use App\Models\User;
use App\Notifications\NewPostTip;
use App\Notifications\NewUploadTip;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\BonusControllerTest
 */
class TipController extends Controller
{
    /**
     * Show previous tip history.
     */
    public function index(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $user->id || $request->user()->group->is_modo, 403);

        $userbon = $user->getSeedbonus();
        $bontransactions = BonTransactions::query()
            ->with(['senderObj', 'receiverObj'])
            ->where(
                fn ($query) => $query
                ->where('sender', '=', $user->id)
                ->orwhere('receiver', '=', $user->id)
            )
            ->where('name', '=', 'tip')
            ->latest('date_actioned')
            ->paginate(25);

        $tipsSent = BonTransactions::query()
            ->where('sender', '=', $user->id)
            ->where('name', '=', 'tip')
            ->sum('cost');

        $tipsReceived = BonTransactions::query()
            ->where('receiver', '=', $user->id)
            ->where('name', '=', 'tip')
            ->sum('cost');

        return \view('user.tip.index', [
            'user'              => $user,
            'bontransactions'   => $bontransactions,
            'userbon'           => $userbon,
            'tips_sent'         => $tipsSent,
            'tips_received'     => $tipsReceived,
        ]);
    }

    /**
     * Tip Points To A User.
     */
    public function store(StoreTipRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $sender = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $sender->id, 403);

        $request = $request->safe()->collect();
        $tipable = match (true) {
            $request->has('torrent') => Torrent::withAnyStatus()->findOrFail($request->get('torrent')),
            $request->has('post')    => Post::findOrFail($request->get('post')),
        };
        $recipient = $tipable->user;
        $tipAmount = $request->get('tip');

        $recipient->increment('seedbonus', $tipAmount);
        $sender->decrement('seedbonus', $tipAmount);

        $bonTransactions = new BonTransactions();
        $bonTransactions->itemID = 0;
        $bonTransactions->name = 'tip';
        $bonTransactions->cost = $tipAmount;
        $bonTransactions->sender = $sender->id;
        $bonTransactions->receiver = $recipient->id;
        $bonTransactions->comment = 'tip';
        $bonTransactions->post_id = $request->has('post') ? $tipable->id : null;
        $bonTransactions->torrent_id = $request->has('torrent') ? $tipable->id : null;
        $bonTransactions->save();

        if ($request->has('torrent')) {
            if ($recipient->acceptsNotification($sender, $recipient, 'torrent', 'show_torrent_tip')) {
                $recipient->notify(new NewUploadTip('torrent', $sender->username, $tipAmount, $tipable));
            }
        } elseif ($request->has('post')) {
            $recipient->notify(new NewPostTip('forum', $sender->username, $tipAmount, $tipable));
        }

        return \redirect()->back()->withSuccess(\trans('bon.success-tip'));
    }
}
