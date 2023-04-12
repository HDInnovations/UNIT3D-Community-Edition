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

use App\Models\Graveyard;
use App\Models\History;
use App\Models\Torrent;
use Illuminate\Http\Request;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\GraveyardControllerTest
 */
class GraveyardController extends Controller
{
    /**
     * Resurrect A Torrent.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrent = Torrent::findOrFail($request->torrent_id);

        if ($user->id === $torrent->user_id) {
            return to_route('torrent.show', ['id' => $torrent->id])
                ->withErrors(trans('graveyard.resurrect-failed-own'));
        }

        if ($torrent->seeders !== 0) {
            return to_route('torrent.show', ['id' => $torrent->id])
                ->withErrors('This torrent is not dead.');
        }

        if ($torrent->created_at->gt(now()->subDays(30))) {
            return to_route('torrent.show', ['id' => $torrent->id])
                ->withErrors('This torrent is not older than 30 days.');
        }

        $resurrection = Graveyard::where('torrent_id', '=', $torrent->id)->exists();

        if ($resurrection) {
            return to_route('torrent.show', ['id' => $torrent->id])
                ->withErrors(trans('graveyard.resurrect-failed-pending'));
        }

        $history = History::where('torrent_id', '=', $torrent->id)->where('user_id', '=', $user->id)->first();
        $seedtime = config('graveyard.time') + $history?->seedtime ?? 0;

        $graveyard = new Graveyard();
        $graveyard->user_id = $user->id;
        $graveyard->torrent_id = $torrent->id;
        $graveyard->seedtime = $seedtime;
        $graveyard->save();

        return to_route('torrent.show', ['id' => $torrent->id])
            ->withSuccess(trans('graveyard.resurrect-complete'));
    }

    /**
     * Cancel A Ressurection.
     *
     *
     * @throws Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $resurrection = Graveyard::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $resurrection->user_id, 403);
        $resurrection->delete();

        return to_route('users.resurrections.index', ['user' => $user])
            ->withSuccess(trans('graveyard.resurrect-canceled'));
    }
}
