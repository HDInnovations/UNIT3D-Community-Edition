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
use App\Models\Movie;
use App\Models\PlaylistTorrent;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\Tv;
use App\Models\User;
use App\Models\Wish;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TorrentFilter
{
    /**
     * @param Builder<Torrent|TorrentRequest> $query
     */
    public function scopeOfName(Builder $query, string $name, bool $isRegex = false): void
    {
        $query->when(
            $isRegex,
            fn ($query) => $query->where('name', 'REGEXP', substr($name, 1, -1)),
            fn ($query) => $query->where('name', 'LIKE', '%'.str_replace(' ', '%', $name).'%')
        );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfDescription(Builder $query, string $description, bool $isRegex = false): void
    {
        $query->when(
            $isRegex,
            fn ($query) => $query->where('description', 'REGEXP', substr($description, 1, -1)),
            fn ($query) => $query->where('description', 'LIKE', '%'.$description.'%')
        );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfMediainfo(Builder $query, string $mediainfo, bool $isRegex = false): void
    {
        $query->when(
            $isRegex,
            fn ($query) => $query->where('mediainfo', 'REGEXP', substr($mediainfo, 1, -1)),
            fn ($query) => $query->where('mediainfo', 'LIKE', '%'.$mediainfo.'%')
        );
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     */
    public function scopeOfUploader(Builder $query, string $username, User $authenticatedUser = null): void
    {
        $authenticatedUser ??= auth()->user();

        $query
            ->whereIn('user_id', User::select('id')->where('username', '=', $username))
            ->when(
                $authenticatedUser === null,
                fn ($query) => $query->where('anon', '=', false),
                fn ($query) => $query->when(
                    !$authenticatedUser->group->is_modo,
                    fn ($query) => $query->where(fn ($query) => $query->where('anon', '=', false)->orWhere('user_id', '=', $authenticatedUser->id))
                )
            );
    }

    /**
     * @param Builder<Torrent> $query
     * @param array<string>    $keywords
     */
    public function scopeOfKeyword(Builder $query, array $keywords): void
    {
        $query->whereIn('id', Keyword::select('torrent_id')->whereIn('name', $keywords));
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeReleasedAfterOrIn(Builder $query, int $year): void
    {
        $query->where('release_year', '>=', $year);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeReleasedBeforeOrIn(Builder $query, int $year): void
    {
        $query->where('release_year', '<=', $year);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfSizeGreaterOrEqualTo(Builder $query, int $size): void
    {
        $query->where('size', '>=', $size);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfSizeLesserOrEqualTo(Builder $query, int $size): void
    {
        $query->where('size', '<=', $size);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     * @param array<int>                      $categories
     */
    public function scopeOfCategory(Builder $query, array $categories): void
    {
        $query->whereIntegerInRaw('category_id', $categories);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     * @param array<int>                      $types
     */
    public function scopeOfType(Builder $query, array $types): void
    {
        $query->whereIntegerInRaw('type_id', $types);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     * @param array<int>                      $resolutions
     */
    public function scopeOfResolution(Builder $query, array $resolutions): void
    {
        $query->whereIntegerInRaw('resolution_id', $resolutions);
    }

    /**
     * @param Builder<Torrent> $query
     * @param array<int>       $genres
     */
    public function scopeOfGenre(Builder $query, array $genres): void
    {
        $query
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

    /**
     * @param Builder<Torrent> $query
     * @param array<int>       $regions
     */
    public function scopeOfRegion(Builder $query, array $regions): void
    {
        $query->whereIntegerInRaw('region_id', $regions);
    }

    /**
     * @param Builder<Torrent> $query
     * @param array<int>       $distributors
     */
    public function scopeOfDistributor(Builder $query, array $distributors): void
    {
        $query->whereIntegerInRaw('distributor_id', $distributors);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     */
    public function scopeOfTmdb(Builder $query, int $tvdbId): void
    {
        $query->where('tmdb', '=', $tvdbId);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     */
    public function scopeOfImdb(Builder $query, int $tvdbId): void
    {
        $query->where('imdb', '=', $tvdbId);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     */
    public function scopeOfTvdb(Builder $query, int $tvdbId): void
    {
        $query->where('tvdb', '=', $tvdbId);
    }

    /**
     * @param Builder<Torrent|TorrentRequest> $query
     */
    public function scopeOfMal(Builder $query, int $malId): void
    {
        $query->where('mal', '=', $malId);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfPlaylist(Builder $query, int $playlistId): void
    {
        $query->whereIn('id', PlaylistTorrent::select('torrent_id')->where('playlist_id', '=', $playlistId));
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfCollection(Builder $query, int $collectionId): void
    {
        $query
            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
            ->whereIn('tmdb', DB::table('collection_movie')->select('movie_id')->where('collection_id', '=', $collectionId));
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfCompany(Builder $query, int $companyId): void
    {
        $query
            ->where(
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                            ->whereIn('tmdb', DB::table('company_movie')->select('movie_id')->where('company_id', '=', $companyId))
                    )
                    ->orWhere(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', 1))
                            ->whereIn('tmdb', DB::table('company_tv')->select('tv_id')->where('company_id', '=', $companyId))
                    )
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfNetwork(Builder $query, int $networkId): void
    {
        $query
            ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', 1))
            ->whereIn('tmdb', DB::table('network_tv')->select('tv_id')->where('network_id', '=', $networkId));
    }

    /**
     * @param Builder<Torrent> $query
     * @param int|array<int>   $free
     */
    public function scopeOfFreeleech(Builder $query, int|array $free): void
    {
        $query->whereIntegerInRaw('free', (array) $free);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeDoubleup(Builder $query): void
    {
        $query->where('doubleup', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeRefundable(Builder $query): void
    {
        $query->where('refundable', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeStreamOptimized(Builder $query): void
    {
        $query->where('stream', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeSd(Builder $query): void
    {
        $query->where('sd', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeHighSpeed(Builder $query): void
    {
        $query->where('highspeed', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeBookmarkedBy(Builder $query, User $user): void
    {
        $query->whereIn('id', Bookmark::select('torrent_id')->where('user_id', '=', $user->id));
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeWishedBy(Builder $query, User $user): void
    {
        $query->whereIn('tmdb', Wish::select('tmdb')->where('user_id', '=', $user->id));
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeInternal(Builder $query): void
    {
        $query->where('internal', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopePersonalRelease(Builder $query): void
    {
        $query->where('personal_release', '=', 1);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeAlive(Builder $query): void
    {
        $query->where('seeders', '>', 0);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeDying(Builder $query): void
    {
        $query
            ->where('seeders', '=', 1)
            ->where('times_completed', '>=', 3);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeDead(Builder $query): void
    {
        $query->where('seeders', '=', 0);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeGraveyard(Builder $query): void
    {
        $query->where('seeders', '=', 0)->where('created_at', '<', Carbon::now()->subDays(30));
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeNotDownloadedBy(Builder $query, User $user): void
    {
        $query
            ->whereDoesntHave(
                'history',
                fn ($query) => $query
                    ->where('user_id', '=', $user->id)
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeDownloadedBy(Builder $query, User $user): void
    {
        $query
            ->whereHas(
                'history',
                fn (Builder $query) => $query
                    ->where('user_id', '=', $user->id)
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeSeededBy(Builder $query, User $user): void
    {
        $query
            ->whereHas(
                'history',
                fn ($query) => $query
                    ->where('user_id', '=', $user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 1)
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeLeechedby(Builder $query, User $user): void
    {
        $query
            ->whereHas(
                'history',
                fn ($query) => $query
                    ->where('user_id', '=', $user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 0)
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeUncompletedBy(Builder $query, User $user): void
    {
        $query
            ->whereHas(
                'history',
                fn ($query) => $query
                    ->where('user_id', '=', $user->id)
                    ->where('active', '=', 0)
                    ->where('seeder', '=', 0)
                    ->where('seedtime', '=', 0)
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfFilename(Builder $query, string $filename): void
    {
        $query
            ->whereHas(
                'files',
                fn ($query) => $query
                    ->where('name', $filename)
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfSeason(Builder $query, int $seasonNumber): void
    {
        $query->where('season_number', '=', $seasonNumber);
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfEpisode(Builder $query, int $episodeNumber): void
    {
        $query->where('episode_number', '=', $episodeNumber);
    }

    /**
     * @param Builder<Torrent> $query
     * @param array<int>       $languages
     */
    public function scopeOfPrimaryLanguage(Builder $query, array $languages): void
    {
        $query
            ->where(
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                            ->whereIn('tmdb', Movie::select('id')->whereIn('original_language', $languages))
                    )
                    ->orWhere(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', 1))
                            ->whereIn('tmdb', Tv::select('id')->whereIn('original_language', $languages))
                    )
            );
    }

    /**
     * @param Builder<Torrent> $query
     */
    public function scopeOfAdult(Builder $query, ?bool $isAdult = null): void
    {
        // Currently, only movies have an `adult` column.
        $query
            ->when(
                $isAdult === true,
                fn ($query) => $query
                    ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                    ->whereIn('tmdb', Movie::select('id')->where('adult', '=', true)),
            )
            ->when(
                $isAdult === false,
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                                    ->whereIn('tmdb', Movie::select('id')->where('adult', '=', false))
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->whereNotIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                            )
                    )
            );
    }
}
