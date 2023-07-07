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
use App\Services\Unit3dAnnounce;
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
    public function __construct(protected \App\Interfaces\ByteUnitsInterface $byteUnits)
    {
    }

    /**
     * Show Bonus Store System.
     */
    public function create(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.transaction.create', [
            'user'     => $user,
            'bon'      => $user->getSeedbonus(),
            'activefl' => $user->personalFreeleeches()->exists(),
            'items'    => BonExchange::all(),
        ]);
    }

    /**
     * Exchange Points For A Item.
     */
    public function store(StoreTransactionRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

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
                PersonalFreeleech::create(['user_id' => $user->id]);

                cache()->put('personal_freeleech:'.$user->id, true);

                Unit3dAnnounce::addPersonalFreeleech($user->id);

                PrivateMessage::create([
                    'sender_id'   => 1,
                    'receiver_id' => $user->id,
                    'subject'     => trans('bon.pm-subject'),
                    'message'     => sprintf(trans('bon.pm-message'), Carbon::now()->addDays(1)->toDayDateTimeString()).config('app.timezone').'[/b]! 
                    [color=red][b]'.trans('common.system-message').'[/b][/color]',
                ]);

                break;
            case $item->invite:
                $user->increment('invites', $item->value);

                break;
        }

        BonTransactions::create([
            'itemID'     => $item->id,
            'name'       => $item->description,
            'cost'       => $item->value,
            'sender'     => $user->id,
            'comment'    => $item->description,
            'torrent_id' => null,
        ]);

        $user->decrement('seedbonus', $item->cost);

        return to_route('users.transactions.create', ['user' => $user])
            ->withSuccess(trans('bon.success'));
    }
}
