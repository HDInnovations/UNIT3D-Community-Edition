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

namespace App\Traits;

use App\Models\Movie;
use App\Models\Tv;
use MarcReichel\IGDBLaravel\Models\Game;

trait TorrentMeta
{
    public function scopeMeta($torrents): \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
    {
        $movieIds = $torrents->where('meta', '=', 'movie')->pluck('tmdb');
        $tvIds = $torrents->where('meta', '=', 'tv')->pluck('tmdb');
        $gameIds = $torrents->where('meta', '=', 'game')->pluck('igdb');

        $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
        $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
        $games = [];

        foreach ($gameIds as $gameId) {
            $games[$gameId] = Game::with(['cover' => ['url', 'image_id']])->find($gameId);
        }

        return $torrents->map(function ($torrent) use ($movies, $tv, $games) {
            $torrent->meta = match ($torrent->meta) {
                'movie' => $movies[$torrent->tmdb] ?? null,
                'tv'    => $tv[$torrent->tmdb] ?? null,
                'game'  => $games[$torrent->igdb] ?? null,
                default => null,
            };

            return $torrent;
        });
    }
}
