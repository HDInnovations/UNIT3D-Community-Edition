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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\DTO;

use App\Models\PlaylistTorrent;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Database\Eloquent\Builder;
use Closure;
use Illuminate\Support\Facades\DB;

readonly class TorrentSearchFiltersDTO
{
    private ?User $user;

    public function __construct(
        User $user = null,
        private string $name = '',
        private string $description = '',
        private string $mediainfo = '',
        private string $uploader = '',
        /** @var array<mixed> */
        private array $keywords = [],
        private ?int $startYear = null,
        private ?int $endYear = null,
        private ?int $minSize = null,
        private ?int $maxSize = null,
        private ?int $episodeNumber = null,
        private ?int $seasonNumber = null,
        /** @var array<mixed> */
        private array $categoryIds = [],
        /** @var array<mixed> */
        private array $typeIds = [],
        /** @var array<mixed> */
        private array $resolutionIds = [],
        /** @var array<mixed> */
        private array $genreIds = [],
        /** @var array<mixed> */
        private array $regionIds = [],
        /** @var array<mixed> */
        private array $distributorIds = [],
        private ?bool $adult = null,
        private ?int $tmdbId = null,
        private ?int $imdbId = null,
        private ?int $tvdbId = null,
        private ?int $malId = null,
        private ?int $playlistId = null,
        private ?int $collectionId = null,
        private ?int $networkId = null,
        private ?int $companyId = null,
        /** @var array<mixed> */
        private array $primaryLanguageNames = [],
        /** @var array<mixed> */
        private array $free = [],
        private bool $doubleup = false,
        private bool $featured = false,
        private bool $refundable = false,
        private bool $stream = false,
        private bool $sd = false,
        private bool $highspeed = false,
        private bool $internal = false,
        private bool $trumpable = false,
        private bool $personalRelease = false,
        private bool $alive = false,
        private bool $dying = false,
        private bool $dead = false,
        private string $filename = '',
        private bool $graveyard = false,
        private bool $userBookmarked = false,
        private bool $userWished = false,
        private ?bool $userDownloaded = null,
        private ?bool $userSeeder = null,
        private ?bool $userActive = null,
    ) {
        $this->user = $user ?? auth()->user();
    }

    /**
     * @return Closure(Builder<\App\Models\Torrent>): Builder<\App\Models\Torrent>
     */
    final public function toSqlQueryBuilder(): Closure
    {
        $group = $this->user->group;

        $isRegexAllowed = $group->is_modo || $group->is_editor;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen((string) $field) > 2
            && $field[0] === '/'
            && $field[-1] === '/'
            && @preg_match($field, 'Validate regex') !== false;

        return fn ($query) => $query
            ->when(
                $this->name !== '',
                fn ($query) => $query
                    ->when(
                        $isRegex($this->name),
                        fn ($query) => $query->where('name', 'REGEXP', substr($this->name, 1, -1)),
                        fn ($query) => $query->where('name', 'LIKE', '%'.str_replace(' ', '%', $this->name).'%')
                    )
            )
            ->when(
                $this->description !== '',
                fn ($query) => $query
                    ->when(
                        $isRegex($this->description),
                        fn ($query) => $query->where('description', 'REGEXP', substr($this->description, 1, -1)),
                        fn ($query) => $query->where('description', 'LIKE', '%'.$this->description.'%')
                    )
            )
            ->when(
                $this->mediainfo !== '',
                fn ($query) => $query
                    ->when(
                        $isRegex($this->mediainfo),
                        fn ($query) => $query->where('mediainfo', 'REGEXP', substr($this->mediainfo, 1, -1)),
                        fn ($query) => $query->where('mediainfo', 'LIKE', '%'.$this->mediainfo.'%')
                    )
            )
            ->when(
                $this->uploader !== '',
                fn ($query) => $query
                    ->whereRelation('user', 'username', '=', $this->uploader)
                    ->when(
                        $this->user === null,
                        fn ($query) => $query->where('anon', '=', false),
                        fn ($query) => $query
                            ->when(
                                !$this->user->group->is_modo,
                                fn ($query) => $query
                                    ->where(
                                        fn ($query) => $query
                                            ->where('anon', '=', false)
                                            ->orWhere('user_id', '=', $this->user->id)
                                    )
                            )
                    )
            )
            ->when(
                $this->keywords !== [],
                fn ($query) => $query->whereHas('keywords', fn ($query) => $query->whereIn('name', $this->keywords))
            )
            ->when(
                $this->startYear !== null,
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereRelation('movie', 'release_date', '>=', $this->startYear.'-01-01 00:00:00')
                                    ->whereRelation('category', 'movie_meta', '=', true)
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->whereRelation('tv', 'first_air_date', '>=', $this->startYear.'-01-01 00:00:00')
                                    ->whereRelation('category', 'tv_meta', '=', true)
                            )
                    )
            )
            ->when(
                $this->endYear !== null,
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereRelation('movie', 'release_date', '<=', $this->endYear.'-12-31 23:59:59')
                                    ->whereRelation('category', 'movie_meta', '=', true)
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->orWhereRelation('tv', 'first_air_date', '<=', $this->endYear.'-12-31 23:59:59')
                                    ->whereRelation('category', 'tv_meta', '=', true)
                            )
                    )
            )
            ->when($this->minSize !== null, fn ($query) => $query->where('size', '>=', $this->minSize))
            ->when($this->maxSize !== null, fn ($query) => $query->where('size', '<=', $this->maxSize))
            ->when($this->categoryIds !== [], fn ($query) => $query->whereIntegerInRaw('category_id', $this->categoryIds))
            ->when($this->typeIds !== [], fn ($query) => $query->whereIntegerInRaw('type_id', $this->typeIds))
            ->when($this->resolutionIds !== [], fn ($query) => $query->whereIntegerInRaw('resolution_id', $this->resolutionIds))
            ->when(
                $this->genreIds !== [],
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereRelation('category', 'movie_meta', '=', true)
                                    ->whereIn('tmdb', DB::table('genre_movie')->select('movie_id')->whereIn('genre_id', $this->genreIds))
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->whereRelation('category', 'tv_meta', '=', true)
                                    ->whereIn('tmdb', DB::table('genre_tv')->select('tv_id')->whereIn('genre_id', $this->genreIds))
                            )
                    )
            )
            ->when(
                $this->regionIds !== [],
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIntegerInRaw('region_id', $this->regionIds)
                            ->when(\in_array(0, $this->regionIds), fn ($query) => $query->orWhereNull('region_id'))
                    )
            )
            ->when(
                $this->distributorIds !== [],
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIntegerInRaw('distributor_id', $this->distributorIds)
                            ->when(\in_array(0, $this->distributorIds), fn ($query) => $query->orWhereNull('distributor_id'))
                    )
            )
            ->when($this->tmdbId !== null, fn ($query) => $query->where('tmdb', '=', $this->tmdbId))
            ->when($this->imdbId !== null, fn ($query) => $query->where('imdb', '=', $this->imdbId))
            ->when($this->tvdbId !== null, fn ($query) => $query->where('tvdb', '=', $this->tvdbId))
            ->when($this->malId !== null, fn ($query) => $query->where('mal', '=', $this->malId))
            ->when($this->episodeNumber !== null, fn ($query) => $query->where('episode_number', '=', $this->episodeNumber))
            ->when($this->seasonNumber !== null, fn ($query) => $query->where('season_number', '=', $this->seasonNumber))
            ->when(
                $this->playlistId !== null,
                fn ($query) => $query
                    ->whereIn(
                        'id',
                        PlaylistTorrent::select('torrent_id')
                            ->where('playlist_id', '=', $this->playlistId)
                            ->when(
                                $this->user === null,
                                fn ($query) => $query->whereRelation('playlist', 'is_private', '=', false),
                                fn ($query) => $query->when(
                                    ! $this->user->group->is_modo,
                                    fn ($query) => $query
                                        ->where(
                                            fn ($query) => $query
                                                ->whereRelation('playlist', 'is_private', '=', false)
                                                ->orWhereRelation('playlist', 'user_id', '=', $this->user->id)
                                        )
                                )
                            )
                    )
            )
            ->when(
                $this->collectionId !== null,
                fn ($query) => $query
                    ->whereRelation('category', 'movie_meta', '=', true)
                    ->whereIn('tmdb', DB::table('collection_movie')->select('movie_id')->where('collection_id', '=', $this->collectionId))
            )
            ->when(
                $this->companyId !== null,
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereRelation('category', 'movie_meta', '=', true)
                                    ->whereIn('tmdb', DB::table('company_movie')->select('movie_id')->where('company_id', '=', $this->companyId))
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->whereRelation('category', 'tv_meta', '=', true)
                                    ->whereIn('tmdb', DB::table('company_tv')->select('tv_id')->where('company_id', '=', $this->companyId))
                            )
                    )
            )
            ->when(
                $this->networkId !== null,
                fn ($query) => $query
                    ->whereRelation('category', 'tv_meta', '=', true)
                    ->whereIn('tmdb', DB::table('network_tv')->select('tv_id')->where('network_id', '=', $this->networkId))
            )
            ->when(
                $this->primaryLanguageNames !== [],
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereRelation('category', 'movie_meta', '=', true)
                                    ->whereHas('movie', fn ($query) => $query->whereIn('original_language', $this->primaryLanguageNames))
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->whereRelation('category', 'tv_meta', '=', true)
                                    ->whereHas('tv', fn ($query) => $query->whereIn('original_language', $this->primaryLanguageNames))
                            )
                    )
            )
            ->when(
                $this->free !== [],
                fn ($query) => $query
                    ->when(
                        config('other.freeleech'),
                        fn ($query) => $query->whereBetween('free', [0, 100]),
                        fn ($query) => $query->whereIntegerInRaw('free', (array) $this->free)
                    )
            )
            ->when($this->filename !== '', fn ($query) => $query->whereRelation('files', 'name', '=', $this->filename))
            ->when(
                $this->adult === true,
                fn ($query) => $query
                    ->whereRelation('category', 'movie_meta', '=', true)
                    ->whereRelation('movie', 'adult', '=', true)
            )
            // Currently, only movies have an `adult` column.
            ->when(
                $this->adult === false,
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where(
                                fn ($query) => $query
                                    ->whereRelation('category', 'movie_meta', '=', true)
                                    ->whereRelation('movie', 'adult', '=', false)
                            )
                            ->orWhere(
                                fn ($query) => $query
                                    ->whereRelation('category', 'movie_meta', '=', false)
                            )
                    )
            )
            ->when($this->doubleup, fn ($query) => $query->where('doubleup', '=', 1))
            ->when($this->featured, fn ($query) => $query->where('featured', '=', 1))
            ->when($this->refundable, fn ($query) => $query->where('refundable', '=', true))
            ->when($this->stream, fn ($query) => $query->where('stream', '=', 1))
            ->when($this->sd, fn ($query) => $query->where('sd', '=', 1))
            ->when($this->highspeed, fn ($query) => $query->where('highspeed', '=', 1))
            ->when($this->userBookmarked, fn ($query) => $query->whereRelation('bookmarks', 'user_id', '=', $this->user->id))
            ->when($this->userWished, fn ($query) => $query->whereIn('tmdb', Wish::select('tmdb')->where('user_id', '=', $this->user->id)))
            ->when($this->internal, fn ($query) => $query->where('internal', '=', 1))
            ->when($this->personalRelease, fn ($query) => $query->where('personal_release', '=', 1))
            ->when($this->trumpable, fn ($query) => $query->has('trump'))
            ->when($this->alive, fn ($query) => $query->where('seeders', '>', 0))
            ->when($this->dying, fn ($query) => $query->where('seeders', '=', 1)->where('times_completed', '>=', 3))
            ->when($this->dead, fn ($query) => $query->where('seeders', '=', 0))
            ->when($this->graveyard, fn ($query) => $query->where('seeders', '=', 0)->where('created_at', '<', now()->subDays(30)))
            ->when(
                $this->userDownloaded === false,
                fn ($query) => $query
                    ->whereDoesntHave(
                        'history',
                        fn ($query) => $query
                            ->where('user_id', '=', $this->user->id)
                    )
            )
            ->when(
                $this->userDownloaded === true,
                fn ($query) => $query->whereRelation('history', 'user_id', '=', $this->user->id)
            )
            ->when(
                $this->userSeeder === true && $this->userActive === true,
                fn ($query) => $query
                    ->whereHas(
                        'history',
                        fn ($query) => $query
                            ->where('user_id', '=', $this->user->id)
                            ->where('active', '=', 1)
                            ->where('seeder', '=', 1)
                    )
            )
            ->when(
                $this->userSeeder === false && $this->userActive === true,
                fn ($query) => $query
                    ->whereHas(
                        'history',
                        fn ($query) => $query
                            ->where('user_id', '=', $this->user->id)
                            ->where('active', '=', 1)
                            ->where('seeder', '=', 0)
                    )
            )
            ->when(
                $this->userSeeder === false && $this->userActive === false,
                fn ($query) => $query
                    ->whereHas(
                        'history',
                        fn ($query) => $query
                            ->where('user_id', '=', $this->user->id)
                            ->where('active', '=', 0)
                            ->where('seeder', '=', 0)
                            ->where('seedtime', '=', 0)
                    )
            );
    }

    /**
     * @return list<string|list<string>>
     */
    final public function toMeilisearchFilter(): array
    {
        $group = $this->user->group;

        $filters = [
            'deleted_at IS NULL',
            'status = 1',
        ];

        if ($this->uploader !== '') {
            $filters[] = 'user.username = '.json_encode($this->uploader);

            if (!$group->is_modo) {
                $filters[] = 'anon = false';
            }
        }

        if ($this->keywords !== []) {
            $filters[] = 'keywords IN '.json_encode($this->keywords);
        }

        if ($this->startYear !== null) {
            $filters[] = [
                'movie.year >= '.$this->startYear,
                'tv.year >= '.$this->startYear,
            ];
        }

        if ($this->endYear !== null) {
            $filters[] = [
                'movie.year <= '.$this->endYear,
                'tv.year <= '.$this->endYear,
            ];
        }

        if ($this->minSize !== null) {
            $filters[] = 'size >= '.$this->minSize;
        }

        if ($this->maxSize !== null) {
            $filters[] = 'size <= '.$this->maxSize;
        }

        if ($this->seasonNumber !== null) {
            $filters[] = 'season_number = '.$this->seasonNumber;
        }

        if ($this->episodeNumber !== null) {
            $filters[] = 'episode_number = '.$this->episodeNumber;
        }

        if ($this->categoryIds !== []) {
            $filters[] = 'category.id IN '.json_encode(array_map('intval', $this->categoryIds));
        }

        if ($this->typeIds !== []) {
            $filters[] = 'type.id IN '.json_encode(array_map('intval', $this->typeIds));
        }

        if ($this->resolutionIds !== []) {
            $filters[] = 'resolution.id IN '.json_encode(array_map('intval', $this->resolutionIds));
        }

        if ($this->genreIds !== []) {
            $filters[] = [
                'movie.genres.id IN '.json_encode(array_map('intval', $this->genreIds)),
                'tv.genres.id IN '.json_encode(array_map('intval', $this->genreIds)),
            ];
        }

        if ($this->regionIds !== []) {
            if (\in_array(0, $this->regionIds, false)) {
                $filters[] = [
                    'region_id IS NULL',
                    'region_id IN '.json_encode(array_map('intval', $this->regionIds)),
                ];
            } else {
                $filters[] = 'region_id IN '.json_encode(array_map('intval', $this->regionIds));
            }
        }

        if ($this->distributorIds !== []) {
            if (\in_array(0, $this->distributorIds, false)) {
                $filters[] = [
                    'distributor_id IS NULL',
                    'distributor_id IN '.json_encode(array_map('intval', $this->distributorIds)),
                ];
            } else {
                $filters[] = 'distributor_id IN '.json_encode(array_map('intval', $this->distributorIds));
            }
        }

        if ($this->adult !== null) {
            $filters[] = 'movie.adult = '.($this->adult ? 'true' : 'false');
        }

        if ($this->tmdbId !== null) {
            $filters[] = 'tmdb = '.$this->tmdbId;
        }

        if ($this->imdbId !== null) {
            $filters[] = 'imdb = '.$this->imdbId;
        }

        if ($this->tvdbId !== null) {
            $filters[] = 'tvdb = '.$this->tvdbId;
        }

        if ($this->malId !== null) {
            $filters[] = 'mal = '.$this->malId;
        }

        if ($this->playlistId !== null) {
            $filters[] = 'playlists.id = '.$this->playlistId;
        }

        if ($this->collectionId !== null) {
            $filters[] = 'movie.collection.id = '.$this->collectionId;
        }

        if ($this->companyId !== null) {
            $filters[] = [
                'movie.companies.id = '.$this->companyId,
                'tv.companies.id = '.$this->companyId,
            ];
        }

        if ($this->networkId !== null) {
            $filters[] = 'tv.networks.id = '.$this->networkId;
        }

        if ($this->primaryLanguageNames !== []) {
            $filters[] = [
                'movie.original_language IN '.json_encode(array_map('strval', $this->primaryLanguageNames)),
                'tv.original_language IN '.json_encode(array_map('strval', $this->primaryLanguageNames)),
            ];
        }

        if ($this->free !== []) {
            if (!config('other.freeleech')) {
                $filters[] = 'free IN '.json_encode(array_map('intval', $this->free));
            }
        }

        if ($this->doubleup) {
            $filters[] = 'doubleup = true';
        }

        if ($this->featured) {
            $filters[] = 'featured = true';
        }

        if ($this->refundable) {
            $filters[] = 'refundable = true';
        }

        if ($this->stream) {
            $filters[] = 'stream = true';
        }

        if ($this->sd) {
            $filters[] = 'sd = true';
        }

        if ($this->highspeed) {
            $filters[] = 'highspeed = true';
        }

        if ($this->internal) {
            $filters[] = 'internal = true';
        }

        if ($this->trumpable) {
            $filters[] = 'trumpable = true';
        }

        if ($this->personalRelease) {
            $filters[] = 'personal_release = true';
        }

        if ($this->alive) {
            $filters[] = 'seeders > 0';
        }

        if ($this->dying) {
            $filters[] = 'seeders = 1';
            $filters[] = 'times_completed >= 3';
        }

        if ($this->dead) {
            $filters[] = 'seeders = 0';
        }

        if ($this->filename !== '') {
            $filters[] = 'files.name = '.json_encode($this->filename);
        }

        if ($this->graveyard) {
            $filters[] = 'seeders = 0';
            $filters[] = 'created_at < '.now()->subDays(30)->timestamp;
        }

        if ($this->userBookmarked) {
            $filters[] = 'bookmarks.user_id = '.$this->user->id;
        }

        if ($this->userWished) {
            $filters[] = [
                'movie.wishes.user_id = '.$this->user->id,
                'tv.wishes.user_id = '.$this->user->id,
            ];
        }

        if ($this->userDownloaded === true) {
            $filters[] = [
                'history_complete.user_id = '.$this->user->id,
                'history_incomplete.user_id = '.$this->user->id,
            ];
        }

        if ($this->userDownloaded === false) {
            $filters[] = 'history_complete.user_id != '.$this->user->id;
            $filters[] = 'history_incomplete.user_id != '.$this->user->id;
        }

        if ($this->userSeeder === false) {
            $filters[] = 'history_leechers.user_id = '.$this->user->id;
        }

        if ($this->userSeeder === true) {
            $filters[] = 'history_seeders.user_id = '.$this->user->id;
        }

        if ($this->userActive === true) {
            $filters[] = 'history_active.user_id = '.$this->user->id;
        }

        if ($this->userActive === false) {
            $filters[] = 'history_inactive.user_id = '.$this->user->id;
        }

        return $filters;
    }
}
