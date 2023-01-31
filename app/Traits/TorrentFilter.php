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

namespace App\Traits;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Keyword;
use App\Models\PlaylistTorrent;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TorrentFilter
{
    public function scopeOfName(Builder $query, string $name, bool $isRegex = false): Builder
    {
        return $query->when(
            $isRegex,
            fn ($query) => $query->where('name', 'REGEXP', \substr($name, 1, -1)),
            fn ($query) => $query->where('name', 'LIKE', '%'.\str_replace(' ', '%', $name).'%')
        );
    }

    public function scopeOfDescription(Builder $query, string $description, bool $isRegex = false): Builder
    {
        return $query->when(
            $isRegex,
            fn ($query) => $query->where('description', 'REGEXP', \substr($description, 1, -1)),
            fn ($query) => $query->where('description', 'LIKE', '%'.$description.'%')
        );
    }

    public function scopeOfMediainfo(Builder $query, string $mediainfo, bool $isRegex = false): Builder
    {
        return $query->when(
            $isRegex,
            fn ($query) => $query->where('mediainfo', 'REGEXP', \substr($mediainfo, 1, -1)),
            fn ($query) => $query->where('mediainfo', 'LIKE', '%'.$mediainfo.'%')
        );
    }

    public function scopeOfUploader(Builder $query, string $username): Builder
    {
        return $query
            ->whereIn('user_id', User::select('id')->where('username', '=', $username))
            ->where('anon', '=', 0);
    }

    public function scopeOfKeyword(Builder $query, array $keywords): Builder
    {
        return $query->whereIn('id', Keyword::select('torrent_id')->whereIn('name', $keywords));
    }

    public function scopeReleasedAfterOrIn(Builder $query, int $year): Builder
    {
        return $query->where('release_year', '>=', $year);
    }

    public function scopeReleasedBeforeOrIn(Builder $query, int $year): Builder
    {
        return $query->where('release_year', '<=', $year);
    }

    public function scopeOfCategory(Builder $query, array $categories): Builder
    {
        return $query->whereIntegerInRaw('category_id', $categories);
    }

    public function scopeOfType(Builder $query, array $types): Builder
    {
        return $query->whereIntegerInRaw('type_id', $types);
    }

    public function scopeOfResolution(Builder $query, array $resolutions): Builder
    {
        return $query->whereIntegerInRaw('resolution_id', $resolutions);
    }

    public function scopeOfGenre(Builder $query, array $genres): Builder
    {
        return $query
            ->where(
                fn ($query) => $query
                ->where(
                    fn ($query) => $query
                    ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                    ->whereIn('tmdb', DB::table('genre_movie')->select('movie_id')->whereIn('genre_id', $genres))
                )
                ->orWhere(
                    fn ($query) => $query
                    ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', 1))
                    ->whereIn('tmdb', DB::table('genre_tv')->select('tv_id')->whereIn('genre_id', $genres))
                )
            );
    }

    public function scopeOfRegion(Builder $query, array $regions): Builder
    {
        return $query->whereIntegerInRaw('region_id', $regions);
    }

    public function scopeOfDistributor(Builder $query, array $distributors): Builder
    {
        return $query->whereIntegerInRaw('distributor_id', $distributors);
    }

    public function scopeOfTmdb(Builder $query, int $tvdbId): Builder
    {
        return $query->where('tmdb', '=', $tvdbId);
    }

    public function scopeOfimdb(Builder $query, int $tvdbId): Builder
    {
        return $query->where('imdb', '=', $tvdbId);
    }

    public function scopeOfTvdb(Builder $query, int $tvdbId): Builder
    {
        return $query->where('tvdb', '=', $tvdbId);
    }

    public function scopeOfMal(Builder $query, int $malId): Builder
    {
        return $query->where('mal', '=', $malId);
    }

    public function scopeOfPlaylist(Builder $query, int $playlistId): Builder
    {
        return $query->whereIn('id', PlaylistTorrent::select('torrent_id')->where('playlist_id', '=', $playlistId));
    }

    public function scopeOfCollection(Builder $query, int $collectionId): Builder
    {
        return $query
            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
            ->whereIn('tmdb', DB::table('collection_movie')->select('movie_id')->where('collection_id', '=', $collectionId));
    }

    public function scopeOfFreeleech(Builder $query, string|array $free): Builder
    {
        return $query->whereIntegerInRaw('free', (array) $free);
    }

    public function scopeDoubleup(Builder $query): Builder
    {
        return $query->where('doubleup', '=', 1);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', '=', 1);
    }

    public function scopeStreamOptimized(Builder $query): Builder
    {
        return $query->where('stream', '=', 1);
    }

    public function scopeSd(Builder $query): Builder
    {
        return $query->where('sd', '=', 1);
    }

    public function scopeHighSpeed(Builder $query): Builder
    {
        return $query->where('highspeed', '=', 1);
    }

    public function scopeBookmarkedBy(Builder $query, User $user): Builder
    {
        return $query->whereIn('id', Bookmark::select('torrent_id')->where('user_id', '=', $user->id));
    }

    public function scopeWishedBy(Builder $query, User $user): Builder
    {
        return $query->whereIn('tmdb', Wish::select('tmdb')->where('user_id', '=', $user->id));
    }

    public function scopeInternal(Builder $query): Builder
    {
        return $query->where('internal', '=', 1);
    }

    public function scopePersonalRelease(Builder $query): Builder
    {
        return $query->where('personal_release', '=', 1);
    }

    public function scopeAlive(Builder $query): Builder
    {
        return $query->where('seeders', '>', 0);
    }

    public function scopeDying(Builder $query): Builder
    {
        return $query
            ->where('seeders', '=', 1)
            ->where('times_completed', '>=', 3);
    }

    public function scopeDead(Builder $query): Builder
    {
        return $query->where('seeders', '=', 0);
    }

    public function scopeNotDownloadedBy(Builder $query, User $user): Builder
    {
        return $query
            ->whereDoesntHave(
                'history',
                fn ($query) => $query
                ->where('user_id', '=', $user->id)
            );
    }

    public function scopeDownloadedBy(Builder $query, User $user): Builder
    {
        return $query
            ->whereHas(
                'history',
                fn (Builder $query) => $query
                ->where('user_id', '=', $user->id)
            );
    }

    public function scopeSeededBy(Builder $query, User $user): Builder
    {
        return $query
            ->whereHas(
                'history',
                fn ($query) => $query
                ->where('user_id', '=', $user->id)
                ->where('active', '=', 1)
                ->where('seeder', '=', 1)
            );
    }

    public function scopeLeechedby(Builder $query, User $user): Builder
    {
        return $query
            ->whereHas(
                'history',
                fn ($query) => $query
                ->where('user_id', '=', $user->id)
                ->where('active', '=', 1)
                ->where('seeder', '=', 0)
            );
    }

    public function scopeUncompletedBy(Builder $query, User $user): Builder
    {
        return $query
            ->whereHas(
                'history',
                fn ($query) => $query
                ->where('user_id', '=', $user->id)
                ->where('active', '=', 0)
                ->where('seeder', '=', 0)
                ->where('seedtime', '=', 0)
            );
    }

    public function scopeOfFilename(Builder $query, string $filename): Builder
    {
        return $query
            ->whereHas(
                'files',
                fn ($query) => $query
                ->where('name', $filename)
            );
    }

    public function scopeOfSeason(Builder $query, int $seasonNumber): Builder
    {
        return $query->where('season_number', '=', $seasonNumber);
    }

    public function scopeOfEpisode(Builder $query, int $episodeNumber): Builder
    {
        return $query->where('episode_number', '=', $episodeNumber);
    }
}
