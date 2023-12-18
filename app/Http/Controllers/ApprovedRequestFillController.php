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

namespace App\Http\Controllers;

use App\Achievements\UserFilled100Requests;
use App\Achievements\UserFilled25Requests;
use App\Achievements\UserFilled50Requests;
use App\Achievements\UserFilled75Requests;
use App\Models\BonTransactions;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Notifications\NewRequestFillApprove;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RequestControllerTest
 */
class ApprovedRequestFillController extends Controller
{
    /**
     * RequestController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Approve A Torrent Request.
     */
    public function store(Request $request, TorrentRequest $torrentRequest): \Illuminate\Http\RedirectResponse
    {
        abort_unless(($request->user()->id === $torrentRequest->user_id || $request->user()->group->is_modo) && $torrentRequest->approved_by === null, 403);

        $approver = $request->user();
        $filler = $torrentRequest->filler;

        $torrentRequest->update([
            'approved_by'   => $approver->id,
            'approved_when' => Carbon::now(),
        ]);

        BonTransactions::create([
            'bon_exchange_id' => 0,
            'name'            => 'request',
            'cost'            => $torrentRequest->bounty,
            'receiver_id'     => $torrentRequest->filled_by,
            'comment'         => sprintf('%s has filled %s and has been awarded %s BONUS.', $filler->username, $torrentRequest->name, $torrentRequest->bounty),
        ]);

        $filler->increment('seedbonus', $torrentRequest->bounty);

        // Achievements
        if (!$torrentRequest->filled_anon) {
            $filler->addProgress(new UserFilled25Requests(), 1);
            $filler->addProgress(new UserFilled50Requests(), 1);
            $filler->addProgress(new UserFilled75Requests(), 1);
            $filler->addProgress(new UserFilled100Requests(), 1);
        }

        // Auto Shout
        if ($torrentRequest->filled_anon) {
            $this->chatRepository->systemMessage(
                sprintf('An anonymous user has filled request, [url=%s]%s[/url]', href_request($torrentRequest), $torrentRequest->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                sprintf('[url=%s]%s[/url] has filled request, [url=%s]%s[/url]', href_profile($filler), $filler->username, href_request($torrentRequest), $torrentRequest->name)
            );
        }

        if ($filler->acceptsNotification($approver, $filler, 'request', 'show_request_fill_approve')) {
            $filler->notify(new NewRequestFillApprove('torrent', $approver->username, $torrentRequest));
        }

        return to_route('requests.show', ['torrentRequest' => $torrentRequest])
            ->withSuccess(sprintf(trans('request.approved-user'), $torrentRequest->name, $torrentRequest->filled_anon ? 'Anonymous' : $filler->username));
    }

    /**
     * Destroy A Torrent Request Fill.
     */
    public function destroy(Request $request, TorrentRequest $torrentRequest): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $filler = $torrentRequest->filler;

        $torrentRequest->update([
            'approved_by'   => null,
            'approved_when' => null,
        ]);

        // TODO: Change database column to signed from unsigned to handle negative bon.
        $refunded = min($torrentRequest->bounty, $filler->seedbonus);

        BonTransactions::create([
            'bon_exchange_id' => 0,
            'name'            => 'request',
            'cost'            => $refunded,
            'sender_id'       => $torrentRequest->filled_by,
            'comment'         => sprintf('%s has had %s unfilled and has forfeited %s BONUS.', $filler->username, $torrentRequest->name, $refunded),
        ]);

        $filler->decrement('seedbonus', $refunded);

        return to_route('requests.show', ['torrentRequest' => $torrentRequest])
            ->withSuccess(trans('request.request-reset'));
    }
}
