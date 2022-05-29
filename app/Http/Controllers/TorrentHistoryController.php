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

class TorrentHistoryController extends Controller
{
    /**
     * Display History Of A Torrent.
     */
    public function index(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $history = History::with(['user'])->where('torrent_id', '=', $id)->latest()->get();

        return \view('torrent.history', ['torrent' => $torrent, 'history' => $history]);
    }
}
