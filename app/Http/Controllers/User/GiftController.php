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
use App\Http\Requests\StoreGiftRequest;
use App\Models\BonTransactions;
use App\Models\User;
use App\Notifications\NewBon;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    /**
     * UserGiftController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Show previous gift history.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.gift.index', [
            'user'  => $user,
            'gifts' => BonTransactions::query()
                ->with(['sender.group', 'receiver.group'])
                ->where(fn ($query) => $query->where('sender_id', '=', $user->id)->orwhere('receiver_id', '=', $user->id))
                ->where('name', '=', 'gift')
                ->latest()
                ->paginate(25),
            'bon'           => $user->formatted_seedbonus,
            'sentGifts'     => $user->sentGifts()->sum('cost'),
            'receivedGifts' => $user->receivedGifts()->sum('cost'),
        ]);
    }

    /**
     * Show gift form.
     */
    public function create(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.gift.create', [
            'user' => $user,
            'bon'  => $user->formatted_seedbonus,
        ]);
    }

    /**
     * Gift points to a user.
     */
    public function store(StoreGiftRequest $request): \Illuminate\Http\RedirectResponse
    {
        $sender = $request->user();
        $receiver = User::where('username', '=', $request->receiver_username)->sole();

        $sender->decrement('seedbonus', $request->cost);
        $receiver->increment('seedbonus', $request->cost);

        $bonTransactions = BonTransactions::create([
            'bon_exchange_id' => 0,
            'name'            => 'gift',
            'cost'            => $request->cost,
            'sender_id'       => $sender->id,
            'receiver_id'     => $receiver->id,
            'comment'         => $request->comment,
            'torrent_id'      => null,
        ]);

        if ($receiver->acceptsNotification($sender, $receiver, 'bon', 'show_bon_gift')) {
            $receiver->notify(new NewBon('gift', $sender->username, $bonTransactions));
        }

        $this->chatRepository->systemMessage(
            sprintf(
                '[url=%s]%s[/url] has gifted %s BON to [url=%s]%s[/url]',
                href_profile($sender),
                $sender->username,
                $request->cost,
                href_profile($receiver),
                $receiver->username
            )
        );

        return redirect()->back()->withSuccess(trans('bon.gift-sent'));
    }
}
