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
    public function index(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $user->id || $request->user()->group->is_modo, 403);

        $userbon = $user->getSeedbonus();

        $gifttransactions = BonTransactions::query()
            ->with(['senderObj', 'receiverObj'])
            ->where(
                fn ($query) => $query
                ->where('sender', '=', $user->id)
                ->orwhere('receiver', '=', $user->id)
            )
            ->where('name', '=', 'gift')
            ->latest('date_actioned')
            ->paginate(25);

        $giftsSent = BonTransactions::query()
            ->where('sender', '=', $user->id)
            ->where('name', '=', 'gift')
            ->sum('cost');

        $giftsReceived = BonTransactions::query()
            ->where('receiver', '=', $user->id)
            ->where('name', '=', 'gift')
            ->sum('cost');

        return \view('user.gift.index', [
            'user'              => $user,
            'gifttransactions'  => $gifttransactions,
            'userbon'           => $userbon,
            'gifts_sent'        => $giftsSent,
            'gifts_received'    => $giftsReceived,
        ]);
    }

    /**
     * Show gift form.
     */
    public function create(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $user->id, 403);

        $userbon = $user->getSeedbonus();

        return \view('user.gift.create', [
            'user'              => $user,
            'userbon'           => $userbon,
        ]);
    }

    /**
     * Gift points to a user.
     */
    public function store(StoreGiftRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $user->id, 403);

        $request = (object) $request->validated();
        $recipient = User::where('username', '=', $request->to_username)->sole();

        $value = $request->bonus_points;

        $recipient->increment('seedbonus', $value);
        $user->decrement('seedbonus', $value);

        $bonTransactions = new BonTransactions();
        $bonTransactions->itemID = 0;
        $bonTransactions->name = 'gift';
        $bonTransactions->cost = $value;
        $bonTransactions->sender = $user->id;
        $bonTransactions->receiver = $recipient->id;
        $bonTransactions->comment = $request->bonus_message;
        $bonTransactions->torrent_id = null;
        $bonTransactions->save();

        if ($recipient->acceptsNotification($user, $recipient, 'bon', 'show_bon_gift')) {
            $recipient->notify(new NewBon('gift', $user->username, $bonTransactions));
        }

        $this->chatRepository->systemMessage(
            \sprintf(
                '[url=%s]%s[/url] has gifted %s BON to [url=%s]%s[/url]',
                \href_profile($user),
                $user->username,
                $value,
                \href_profile($recipient),
                $recipient->username
            )
        );

        return \redirect()->back()->withSuccess(\trans('bon.gift-sent'));
    }
}
