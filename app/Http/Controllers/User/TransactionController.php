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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\BonExchange;
use App\Models\BonTransactions;
use App\Models\PersonalFreeleech;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Feature\Http\Controllers\BonusControllerTest
 */
class TransactionController extends Controller
{
    /**
     * BonusController Constructor.
     */
    public function __construct(protected \App\Interfaces\ByteUnitsInterface $byteUnits, private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Show Bonus Store System.
     */
    public function create(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $user->id, 403);

        $userbon = $user->getSeedbonus();
        $activefl = $user->personalFreeleeches()->exists();
        $items = BonExchange::all();

        return \view('user.transaction.create', [
            'user'              => $user,
            'userbon'           => $userbon,
            'activefl'          => $activefl,
            'items'             => $items,
        ]);
    }

    /**
     * Exchange Points For A Item.
     */
    public function store(StoreTransactionRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->sole();

        \abort_unless($request->user()->id === $user->id, 403);

        $request = (object) $request->validated();
        $item = BonExchange::findOrFail($request->exchange);

        switch (true) {
            case $item->upload:
                $user->increment('uploaded', $item->value);
                break;
            case $item->download:
                $user->decrement('downloaded', $item->value);
                break;
            case $item->personal_freeleech:
                $personalFreeleech = new PersonalFreeleech();
                $personalFreeleech->user_id = $user->id;
                $personalFreeleech->save();

                // Send Private Message
                $privateMessage = new PrivateMessage();
                $privateMessage->sender_id = 1;
                $privateMessage->receiver_id = $user->id;
                $privateMessage->subject = \trans('bon.pm-subject');
                $privateMessage->message = \sprintf(\trans('bon.pm-message'), Carbon::now()->addDays(1)->toDayDateTimeString()).\config('app.timezone').'[/b]! 
                [color=red][b]'.\trans('common.system-message').'[/b][/color]';
                $privateMessage->save();
                break;
            case $item->invite:
                $user->increment('invites', $item->value);
                break;
        }

        $bonTransaction = new BonTransactions();
        $bonTransaction->itemID = $item->id;
        $bonTransaction->name = $item->description;
        $bonTransaction->cost = $item->value;
        $bonTransaction->sender = $user->id;
        $bonTransaction->comment = $item->description;
        $bonTransaction->torrent_id = null;
        $bonTransaction->save();

        $user->decrement('seedbonus', $item->cost);

        return \to_route('transactions.create', ['username' => $user->username])
            ->withSuccess(\trans('bon.success'));
    }
}
