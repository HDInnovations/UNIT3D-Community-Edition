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
        $cheatedTorrents = Torrent::query()
            ->select([
                'id',
                'name',
                'seeders',
                'leechers',
                'times_completed',
                'size',
                'balance',
                'balance_offset',
                'created_at',
            ])
            ->selectRaw('balance + COALESCE(balance_offset, 0) AS current_balance')
            ->selectRaw('(CAST((balance + COALESCE(balance_offset, 0)) AS float) / CAST((size + 1) AS float)) AS times_cheated')
            ->having('current_balance', '<>', '0')
            ->orderByDesc('times_cheated')
            ->paginate(25);

        return \view('Staff.cheated_torrent.index', ['torrents' => $cheatedTorrents]);
    }

    /**
     * Reset the balance of a cheated torrent.
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        Torrent::where('id', '=', $id)->update(['balance_offset' => DB::raw('balance * -1')]);

        return \to_route('staff.cheated_torrents.index')
            ->withSuccess('Balance successfully reset');
    }

    /**
     * Reset the balance of a cheated torrent.
     */
    public function massDestroy(): \Illuminate\Http\RedirectResponse
    {
        Torrent::query()->update(['balance_offset' => DB::raw('balance * -1')]);

        return \to_route('staff.cheated_torrents.index')
            ->withSuccess('All balances successfully reset');
    }
}
