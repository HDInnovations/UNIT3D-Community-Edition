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
use App\Models\Scopes\ApprovedScope;
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
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.tip.index', [
            'user' => $user,
            'tips' => BonTransactions::with(['sender.group', 'receiver.group', 'torrent', 'post'])
                ->where(fn ($query) => $query->where('sender_id', '=', $user->id)->orwhere('receiver_id', '=', $user->id))
                ->where('name', '=', 'tip')
                ->latest()
                ->paginate(25),
            'bon'          => $user->formatted_seedbonus,
            'sentTips'     => $user->sentTips()->sum('cost'),
            'receivedTips' => $user->receivedTips()->sum('cost'),
        ]);
    }

    /**
     * Tip Points To A User.
     *
     * @param User $user The tipping user.
     */
    public function store(StoreTipRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $request = $request->safe()->collect();
        $tipable = match (true) {
            $request->has('torrent') => Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($request->get('torrent')),
            $request->has('post')    => Post::findOrFail($request->get('post')),
            default                  => abort(500, 'This is not one of: torrent, post.')
        };
        $recipient = $tipable->user;
        $tipAmount = $request->get('tip');

        $user->decrement('seedbonus', $tipAmount);
        $recipient->increment('seedbonus', $tipAmount);

        BonTransactions::create([
            'bon_exchange_id' => 0,
            'name'            => 'tip',
            'cost'            => $tipAmount,
            'sender_id'       => $user->id,
            'receiver_id'     => $recipient->id,
            'comment'         => 'tip',
            'post_id'         => $request->has('post') ? $tipable->id : null,
            'torrent_id'      => $request->has('torrent') ? $tipable->id : null,
        ]);

        if ($request->has('torrent')) {
            if ($recipient->acceptsNotification($user, $recipient, 'torrent', 'show_torrent_tip')) {
                $recipient->notify(new NewUploadTip('torrent', $user->username, $tipAmount, $tipable));
            }
        } elseif ($request->has('post')) {
            $recipient->notify(new NewPostTip('forum', $user->username, $tipAmount, $tipable));
        }

        return redirect()->back()->withSuccess(trans('bon.success-tip'));
    }
}
