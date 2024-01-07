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

        $tipable = match (true) {
            $request->integer('torrent') > 0 => Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($request->integer('torrent')),
            $request->integer('post') > 0    => Post::findOrFail($request->integer('post')),
            default                          => abort(400),
        };
        $recipient = $tipable->user;
        $tipAmount = $request->integer('tip');

        $user->decrement('seedbonus', $tipAmount);
        $recipient->increment('seedbonus', $tipAmount);

        BonTransactions::create([
            'bon_exchange_id' => 0,
            'name'            => 'tip',
            'cost'            => $tipAmount,
            'sender_id'       => $user->id,
            'receiver_id'     => $recipient->id,
            'comment'         => 'tip',
            'post_id'         => $tipable instanceof Post ? $tipable->id : null,
            'torrent_id'      => $tipable instanceof Torrent ? $tipable->id : null,
        ]);

        switch (true) {
            case $tipable instanceof Torrent:
                if ($recipient->acceptsNotification($user, $recipient, 'torrent', 'show_torrent_tip')) {
                    $recipient->notify(new NewUploadTip('torrent', $user->username, $tipAmount, $tipable));
                }

                break;
            case $tipable instanceof Post:
                $recipient->notify(new NewPostTip('forum', $user->username, $tipAmount, $tipable));

                break;
        }

        return redirect()->back()->withSuccess(trans('bon.success-tip'));
    }
}
