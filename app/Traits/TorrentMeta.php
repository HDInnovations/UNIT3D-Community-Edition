<?php

declare(strict_types=1);

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
use JsonException;
use MarcReichel\IGDBLaravel\Models\Game;
use ReflectionException;

trait TorrentMeta
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection<int, \App\Models\Torrent>|\Illuminate\Pagination\CursorPaginator<\App\Models\Torrent>|\Illuminate\Pagination\LengthAwarePaginator<\App\Models\Torrent>|\Illuminate\Contracts\Pagination\LengthAwarePaginator<\App\Models\Torrent> $torrents
     *
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException
     * @throws ReflectionException
     * @throws JsonException
     * @return (
     *        $torrents is \Illuminate\Database\Eloquent\Collection<int, \App\Models\Torrent> ? \Illuminate\Support\Collection<int, \App\Models\Torrent>
     *     : ($torrents is \Illuminate\Pagination\CursorPaginator<\App\Models\Torrent> ? \Illuminate\Pagination\CursorPaginator<\App\Models\Torrent>
     *     : ($torrents is \Illuminate\Pagination\LengthAwarePaginator<\App\Models\Torrent> ? \Illuminate\Pagination\LengthAwarePaginator<\App\Models\Torrent>
     *     : \Illuminate\Contracts\Pagination\LengthAwarePaginator<\App\Models\Torrent>
     * )))
     */
    public function scopeMeta(\Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\CursorPaginator|\Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\LengthAwarePaginator $torrents): \Illuminate\Support\Collection|\Illuminate\Pagination\CursorPaginator|\Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($torrents instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator || $torrents instanceof \Illuminate\Contracts\Pagination\CursorPaginator) {
            $movieIds = collect($torrents->items())->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = collect($torrents->items())->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = collect($torrents->items())->where('meta', '=', 'game')->pluck('igdb');
        } else {
            $movieIds = $torrents->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = $torrents->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = $torrents->where('meta', '=', 'game')->pluck('igdb');
        }

        $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
        $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
        $games = [];

        foreach ($gameIds as $gameId) {
            $games[$gameId] = Game::with(['cover' => ['url', 'image_id'], 'genres' => ['name']])->find($gameId);
        }

        $setRelation = function ($torrent) use ($movies, $tv, $games) {
            $torrent->setAttribute(
                'meta',
                match ($torrent->meta) {
                    'movie' => $movies[$torrent->tmdb] ?? null,
                    'tv'    => $tv[$torrent->tmdb] ?? null,
                    'game'  => $games[$torrent->igdb] ?? null,
                    default => null,
                },
            );

            return $torrent;
        };

        if ($torrents instanceof \Illuminate\Database\Eloquent\Collection) {
            return $torrents->map($setRelation);
        }

        /**
         * Laravel's \Illuminate\Contracts\Pagination\LengthAwarePaginator does not have a through method
         * but we are passed a \Illuminate\Pagination\LengthAwarePaginator which does have such a method.
         * Seems to be caused by some Laravel type error that's returning an interface instead of the type
         * itself, or that the interface is missing the method.
         *
         * @phpstan-ignore method.notFound
         */
        return $torrents->through($setRelation);
    }
}
