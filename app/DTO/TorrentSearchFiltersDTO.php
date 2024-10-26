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

use App\Models\User;
use App\Traits\TorrentFilter;
use Illuminate\Database\Eloquent\Builder;
use Closure;

readonly class TorrentSearchFiltersDTO
{
    use TorrentFilter;
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
            ->when($this->name !== '', fn ($query) => $query->ofName($this->name, $isRegex($this->name)))
            ->when($this->description !== '', fn ($query) => $query->ofDescription($this->description, $isRegex($this->description)))
            ->when($this->mediainfo !== '', fn ($query) => $query->ofMediainfo($this->mediainfo, $isRegex($this->mediainfo)))
            ->when($this->uploader !== '', fn ($query) => $query->ofUploader($this->uploader))
            ->when($this->keywords !== [], fn ($query) => $query->ofKeyword($this->keywords))
            ->when($this->startYear !== null, fn ($query) => $query->releasedAfterOrIn($this->startYear))
            ->when($this->endYear !== null, fn ($query) => $query->releasedBeforeOrIn($this->endYear))
            ->when($this->minSize !== null, fn ($query) => $query->ofSizeGreaterOrEqualto($this->minSize))
            ->when($this->maxSize !== null, fn ($query) => $query->ofSizeLesserOrEqualTo($this->maxSize))
            ->when($this->categoryIds !== [], fn ($query) => $query->ofCategory($this->categoryIds))
            ->when($this->typeIds !== [], fn ($query) => $query->ofType($this->typeIds))
            ->when($this->resolutionIds !== [], fn ($query) => $query->ofResolution($this->resolutionIds))
            ->when($this->genreIds !== [], fn ($query) => $query->ofGenre($this->genreIds))
            ->when($this->regionIds !== [], fn ($query) => $query->ofRegion($this->regionIds))
            ->when($this->distributorIds !== [], fn ($query) => $query->ofDistributor($this->distributorIds))
            ->when($this->tmdbId !== null, fn ($query) => $query->ofTmdb($this->tmdbId))
            ->when($this->imdbId !== null, fn ($query) => $query->ofImdb($this->imdbId))
            ->when($this->tvdbId !== null, fn ($query) => $query->ofTvdb($this->tvdbId))
            ->when($this->malId !== null, fn ($query) => $query->ofMal($this->malId))
            ->when($this->episodeNumber !== null, fn ($query) => $query->ofEpisode($this->episodeNumber))
            ->when($this->seasonNumber !== null, fn ($query) => $query->ofSeason($this->seasonNumber))
            ->when($this->playlistId !== null, fn ($query) => $query->ofPlaylist($this->playlistId))
            ->when($this->collectionId !== null, fn ($query) => $query->ofCollection($this->collectionId))
            ->when($this->companyId !== null, fn ($query) => $query->ofCompany($this->companyId))
            ->when($this->networkId !== null, fn ($query) => $query->ofNetwork($this->networkId))
            ->when($this->primaryLanguageNames !== [], fn ($query) => $query->ofPrimaryLanguage($this->primaryLanguageNames))
            ->when($this->free !== [], fn ($query) => $query->ofFreeleech($this->free))
            ->when($this->filename !== '', fn ($query) => $query->ofFilename($this->filename))
            ->when($this->adult === true, fn ($query) => $query->ofAdult(true))
            ->when($this->adult === false, fn ($query) => $query->ofAdult(false))
            ->when($this->doubleup, fn ($query) => $query->doubleup())
            ->when($this->featured, fn ($query) => $query->featured())
            ->when($this->refundable, fn ($query) => $query->refundable())
            ->when($this->stream, fn ($query) => $query->streamOptimized())
            ->when($this->sd, fn ($query) => $query->sd())
            ->when($this->highspeed, fn ($query) => $query->highspeed())
            ->when($this->userBookmarked, fn ($query) => $query->bookmarkedBy($this->user))
            ->when($this->userWished, fn ($query) => $query->wishedBy($this->user))
            ->when($this->internal, fn ($query) => $query->internal())
            ->when($this->personalRelease, fn ($query) => $query->personalRelease())
            ->when($this->trumpable, fn ($query) => $query->trumpable())
            ->when($this->alive, fn ($query) => $query->alive())
            ->when($this->dying, fn ($query) => $query->dying())
            ->when($this->dead, fn ($query) => $query->dead())
            ->when($this->graveyard, fn ($query) => $query->graveyard())
            ->when($this->userDownloaded === false, fn ($query) => $query->notDownloadedBy($this->user))
            ->when($this->userDownloaded === true, fn ($query) => $query->downloadedBy($this->user))
            ->when($this->userSeeder === true && $this->userActive === true, fn ($query) => $query->seededBy($this->user))
            ->when($this->userSeeder === false && $this->userActive === true, fn ($query) => $query->leechedBy($this->user))
            ->when($this->userSeeder === false && $this->userActive === false, fn ($query) => $query->uncompletedBy($this->user));
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
