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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeerController extends Controller
{
    /**
     * Show user peers.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        \abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);

        $history = DB::table('history')
            ->where('user_id', '=', $user->id)
            ->where('created_at', '>', $user->created_at)
            ->selectRaw('sum(actual_uploaded) as upload')
            ->selectRaw('sum(uploaded) as credited_upload')
            ->selectRaw('sum(actual_downloaded) as download')
            ->selectRaw('sum(downloaded) as credited_download')
            ->first();

        return \view('user.peer.index', [
            'user'    => $user,
            'history' => $history,
        ]);
    }

    /**
     * Delete user peers.
     */
    public function massDestroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->id == $user->id, 403);

        // Check if User can flush
        if ($request->user()->own_flushes == 0) {
            return \redirect()->back()->withErrors('You can only flush twice a day!');
        }

        $carbon = new Carbon();

        // Get Peer List from User
        $peers = $user->peers()
            ->select(['id', 'torrent_id', 'user_id', 'updated_at'])
            ->where('updated_at', '<', $carbon->copy()->subMinutes(70)->toDateTimeString())
            ->get();

        // Return with Error if no Peer exists
        if ($peers->isEmpty()) {
            return \redirect()->back()->withErrors('No Peers found! Please wait at least 70 Minutes after the last announce from the client!');
        }

        $user->own_flushes--;

        $peers->join(
            'history',
            static fn ($join) => $join
            ->on('peers.user_id', '=', 'history.user_id')
            ->on('peers.torrent_id', '=', 'history.torrent_id')
        )->update(['active' => false]);
        $peers->delete();

        return \redirect()->back()->withSuccess('Peers were flushed successfully!');
    }
}
