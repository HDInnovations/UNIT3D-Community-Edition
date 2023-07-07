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

use App\Http\Requests\StorePlaylistTorrentRequest;
use App\Models\Playlist;
use App\Models\PlaylistTorrent;
use Exception;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PlaylistTorrentControllerTest
 */
class PlaylistTorrentController extends Controller
{
    /**
     * Attach A Torrent To A Playlist.
     */
    public function store(StorePlaylistTorrentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $playlist = Playlist::findOrFail($request->integer('playlist_id'));

        abort_unless($request->user()->id === $playlist->user_id, 403);

        PlaylistTorrent::create($request->validated());

        return to_route('playlists.show', ['playlist' => $playlist])
            ->withSuccess(trans('playlist.attached-success'));
    }

    /**
     * Detach A Torrent From A Playlist.
     *
     * @throws Exception
     */
    public function destroy(Request $request, PlaylistTorrent $playlistTorrent): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo || $request->user()->id === $playlistTorrent->playlist->user_id, 403);

        $playlistTorrent->delete();

        return to_route('playlists.show', ['playlist' => $playlistTorrent->playlist])
            ->withSuccess(trans('playlist.detached-success'));
    }
}
