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

use App\Models\TorrentRequest;
use App\Models\TorrentRequestClaim;
use App\Notifications\NewRequestClaim;
use App\Notifications\NewRequestUnclaim;
use Illuminate\Http\Request;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RequestControllerTest
 */
class ClaimController extends Controller
{
    /**
     * Claim A Torrent Request.
     */
    public function store(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::with('user')->findOrFail($id);

        if ($torrentRequest->claimed == null) {
            $torrentRequestClaim = new TorrentRequestClaim();
            $torrentRequestClaim->request_id = $id;
            $torrentRequestClaim->username = $user->username;
            $torrentRequestClaim->anon = $request->input('anon');
            $torrentRequestClaim->save();

            $torrentRequest->claimed = 1;
            $torrentRequest->save();

            $sender = $request->input('anon') == 1 ? 'Anonymous' : $user->username;

            $requester = $torrentRequest->user;

            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_claim')) {
                $requester->notify(new NewRequestClaim('torrent', $sender, $torrentRequest));
            }

            return to_route('requests.show', ['id' => $id])
                ->withSuccess(trans('request.claimed-success'));
        }

        return to_route('requests.show', ['id' => $id])
            ->withErrors(trans('request.already-claimed'));
    }

    /**
     * Uncliam A Torrent Request.
     *
     * @throws Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);
        $claimer = TorrentRequestClaim::where('request_id', '=', $id)->first();

        abort_unless($user->group->is_modo || $user->username == $claimer->username, 403);

        if ($torrentRequest->claimed == 1) {
            $requestClaim = TorrentRequestClaim::where('request_id', '=', $id)->sole();
            $isAnon = $requestClaim->anon;
            $requestClaim->delete();

            $torrentRequest->claimed = null;
            $torrentRequest->save();

            $sender = $isAnon == 1 ? 'Anonymous' : $user->username;

            $requester = $torrentRequest->user;

            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_unclaim')) {
                $requester->notify(new NewRequestUnclaim('torrent', $sender, $torrentRequest));
            }

            return to_route('requests.show', ['id' => $id])
                ->withSuccess(trans('request.unclaimed-success'));
        }

        return to_route('requests.show', ['id' => $id])
            ->withErrors(trans('request.unclaim-error'));
    }
}
