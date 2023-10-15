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

use App\Http\Requests\StoreTorrentRequestBountyRequest;
use App\Http\Requests\UpdateTorrentRequestBountyRequest;
use App\Models\BonTransactions;
use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Notifications\NewRequestBounty;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RequestControllerTest
 */
class BountyController extends Controller
{
    /**
     * RequestController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Add Bounty To A Torrent Request.
     */
    public function store(StoreTorrentRequestBountyRequest $request, TorrentRequest $torrentRequest): \Illuminate\Http\RedirectResponse
    {
        abort_unless($torrentRequest->approved_by === null, 403);

        $user = $request->user();

        $user->decrement('seedbonus', $request->integer('seedbonus'));

        $torrentRequest->bounties()->create(['user_id' => $user->id] + $request->validated());

        $torrentRequest->votes++;
        $torrentRequest->bounty += $request->integer('seedbonus');
        $torrentRequest->created_at = Carbon::now();
        $torrentRequest->save();

        BonTransactions::create([
            'bon_exchange_id' => 0,
            'name'            => 'request',
            'cost'            => $request->integer('seedbonus'),
            'sender_id'       => $user->id,
            'comment'         => sprintf('adding bonus to %s', $torrentRequest->name),
        ]);

        if ($request->boolean('anon') == 0) {
            $this->chatRepository->systemMessage(
                sprintf(
                    '[url=%s]%s[/url] has added %s BON bounty to request [url=%s]%s[/url]',
                    href_profile($user),
                    $user->username,
                    $request->input('seedbonus'),
                    href_request($torrentRequest),
                    $torrentRequest->name
                )
            );
        } else {
            $this->chatRepository->systemMessage(
                sprintf(
                    'An anonymous user added %s BON bounty to request [url=%s]%s[/url]',
                    $request->input('seedbonus'),
                    href_request($torrentRequest),
                    $torrentRequest->name
                )
            );
        }

        $sender = $request->boolean('anon') ? 'Anonymous' : $request->user()->username;
        $requester = $torrentRequest->user;

        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_bounty')) {
            $requester->notify(new NewRequestBounty('torrent', $sender, $request->integer('seedbonus'), $torrentRequest));
        }

        return to_route('requests.show', ['torrentRequest' => $torrentRequest])
            ->withSuccess(trans('request.added-bonus'));
    }

    public function update(UpdateTorrentRequestBountyRequest $request, TorrentRequest $torrentRequest, TorrentRequestBounty $torrentRequestBounty): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo || $request->user()->id === $torrentRequestBounty->user_id, 403);

        $torrentRequestBounty->update($request->validated());

        return back();
    }
}
