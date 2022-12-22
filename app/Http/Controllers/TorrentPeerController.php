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

use App\Models\Peer;
use App\Models\Torrent;

class TorrentPeerController extends Controller
{
    /**
     * Display Peers Of A Torrent.
     */
    public function index(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $peers = Peer::query()
            ->with(['user'])
            ->select(['torrent_id', 'user_id', 'uploaded', 'downloaded', 'left', 'port', 'agent', 'created_at', 'updated_at', 'seeder'])
            ->selectRaw('INET6_NTOA(ip) as ip')
            ->where('torrent_id', '=', $id)
            ->latest('seeder')
            ->get()
            ->map(function ($peer) use ($torrent) {
                $progress = 100 * (1 - $peer->left / $torrent->size);
                $peer['progress'] = match (true) {
                    0 < $progress && $progress < 1    => 1,
                    99 < $progress && $progress < 100 => 99,
                    default                           => round($progress),
                };

                return $peer;
            });

        return \view('torrent.peers', ['torrent' => $torrent, 'peers' => $peers]);
    }
}
