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
    public function store(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $tr = TorrentRequest::with('user')->findOrFail($id);
        $tr->votes++;
        $tr->bounty += $request->input('bonus_value');
        $tr->created_at = Carbon::now();

        $v = validator($request->all(), [
            'bonus_value' => sprintf('required|numeric|min:100|max:%s', $user->seedbonus),
        ]);

        if ($v->fails()) {
            return to_route('requests.show', ['id' => $tr->id])
                ->withErrors($v->errors());
        }

        $tr->save();
        $torrentRequestBounty = new TorrentRequestBounty();
        $torrentRequestBounty->user_id = $user->id;
        $torrentRequestBounty->seedbonus = $request->input('bonus_value');
        $torrentRequestBounty->requests_id = $tr->id;
        $torrentRequestBounty->anon = $request->input('anon');
        $torrentRequestBounty->save();
        $BonTransactions = new BonTransactions();
        $BonTransactions->itemID = 0;
        $BonTransactions->name = 'request';
        $BonTransactions->cost = $request->input('bonus_value');
        $BonTransactions->sender = $user->id;
        $BonTransactions->comment = sprintf('adding bonus to %s', $tr->name);
        $BonTransactions->save();
        $user->seedbonus -= $request->input('bonus_value');
        $user->save();
        $trUrl = href_request($tr);
        $profileUrl = href_profile($user);
        // Auto Shout
        if ($torrentRequestBounty->anon == 0) {
            $this->chatRepository->systemMessage(
                sprintf('[url=%s]%s[/url] has added %s BON bounty to request [url=%s]%s[/url]', $profileUrl, $user->username, $request->input('bonus_value'), $trUrl, $tr->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                sprintf('An anonymous user added %s BON bounty to request [url=%s]%s[/url]', $request->input('bonus_value'), $trUrl, $tr->name)
            );
        }

        $sender = $request->input('anon') == 1 ? 'Anonymous' : $user->username;
        $requester = $tr->user;

        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_bounty')) {
            $requester->notify(new NewRequestBounty('torrent', $sender, $request->input('bonus_value'), $tr));
        }

        return to_route('requests.show', ['id' => $request->integer('request_id')])
            ->withSuccess(trans('request.added-bonus'));
    }
}
