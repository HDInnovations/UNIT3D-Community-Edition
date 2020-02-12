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

use App\Models\Playlist;
use App\Models\PlaylistTorrent;
use Illuminate\Http\Request;

class PlaylistTorrentController extends Controller
{
    /**
     * Attach A Torrent To A Playlist.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($request->input('playlist_id'));

        abort_unless($user->id === $playlist->user_id, 403);

        $playlist_torrent = new PlaylistTorrent();
        $playlist_torrent->playlist_id = $playlist->id;
        $playlist_torrent->torrent_id = $request->input('torrent_id');

        $v = validator($playlist_torrent->toArray(), [
            'playlist_id'    => 'required|numeric|exists:playlists,id',
            'torrent_id'     => 'required|numeric|exists:torrents,id',
        ]);

        if ($v->fails()) {
            return redirect()->route('playlists.show', ['id' => $playlist->id])
                ->withErrors($v->errors());
        }
        $playlist_torrent->save();

        return redirect()->route('playlists.show', ['id' => $playlist->id])
            ->withSuccess('Torrent Has Successfully Been Attached To Your Playlist.');
    }

    /**
     * Detach A Torrent From A Playlist.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $playlist_torrent = PlaylistTorrent::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $playlist_torrent->playlist->user_id, 403);
        $playlist_torrent->delete();

        return redirect()->route('playlists.show', ['id' => $playlist_torrent->playlist->id])
            ->withSuccess('Torrent Has Successfully Been Detached From Your Playlist.');
    }
}
