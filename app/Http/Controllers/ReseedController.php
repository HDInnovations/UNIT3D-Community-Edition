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

use App\Models\History;
use App\Models\Torrent;
use App\Models\User;
use App\Notifications\NewReseedRequest;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class ReseedController extends Controller
{
    /**
     * ReseedController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Reseed Request A Torrent.
     */
    public function store(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        // TODO: Store reseed requests so can be viewed in a table view.

        $torrent = Torrent::findOrFail($id);
        $reseed = History::where('torrent_id', '=', $torrent->id)->where('active', '=', 0)->get();

        if ($torrent->seeders <= 2) {
            // Send Notification
            foreach ($reseed as $r) {
                User::find($r->user_id)->notify(new NewReseedRequest($torrent));
            }

            $torrentUrl = \href_torrent($torrent);

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, a reseed request was just placed on [url=%s]%s[/url] can you help out :question:', $torrentUrl, $torrent->name)
            );

            return \to_route('torrent', ['id' => $torrent->id])
                ->withSuccess('A notification has been sent to all users that downloaded this torrent along with original uploader!');
        }

        return \to_route('torrent', ['id' => $torrent->id])
            ->withErrors('This torrent doesnt meet the rules for a reseed request.');
    }
}
