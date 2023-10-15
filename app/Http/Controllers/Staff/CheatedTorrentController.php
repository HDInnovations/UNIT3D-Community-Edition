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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Torrent;
use Illuminate\Support\Facades\DB;

class CheatedTorrentController extends Controller
{
    /**
     * Cheated Torrents.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.cheated_torrent.index', [
            'torrents' => Torrent::query()
                ->select([
                    'torrents.id',
                    'torrents.name',
                    'torrents.seeders',
                    'torrents.leechers',
                    'torrents.times_completed',
                    'torrents.size',
                    'torrents.balance',
                    'torrents.balance_offset',
                    'torrents.created_at',
                ])
                ->selectRaw('MAX(history.completed_at) as last_completed')
                ->selectRaw('MAX(history.created_at) as last_started')
                ->selectRaw('balance + COALESCE(balance_offset, 0) AS current_balance')
                ->selectRaw('(CAST((balance + COALESCE(balance_offset, 0)) AS float) / CAST((size + 1) AS float)) AS times_cheated')
                ->join('history', 'history.torrent_id', '=', 'torrents.id')
                ->groupBy([
                    'torrents.id',
                    'torrents.name',
                    'torrents.seeders',
                    'torrents.leechers',
                    'torrents.times_completed',
                    'torrents.size',
                    'torrents.balance',
                    'torrents.balance_offset',
                    'torrents.created_at',
                ])
                ->having('current_balance', '<>', '0')
                ->having('last_completed', '<', now()->subHours(2))
                ->having('last_started', '<', now()->subHours(2))
                ->orderByDesc('times_cheated')
                ->paginate(25),
        ]);
    }

    /**
     * Reset the balance of a cheated torrent.
     */
    public function destroy(Torrent $cheatedTorrent): \Illuminate\Http\RedirectResponse
    {
        $cheatedTorrent->update([
            'balance_offset' => DB::raw('balance * -1'),
        ]);

        return to_route('staff.cheated_torrents.index')
            ->withSuccess('Balance successfully reset');
    }

    /**
     * Reset the balance of a cheated torrent.
     */
    public function massDestroy(): \Illuminate\Http\RedirectResponse
    {
        Torrent::query()->update([
            'balance_offset' => DB::raw('balance * -1'),
        ]);

        return to_route('staff.cheated_torrents.index')
            ->withSuccess('All balances successfully reset');
    }
}
