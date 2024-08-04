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

    public function __construct(
        private ?User $user = null,
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
    }

    /**
     * @return Closure(Builder<\App\Models\Torrent>): Builder<\App\Models\Torrent>
     */
    final public function toSqlQueryBuilder(): Closure
    {
        $user = $this->user ?? auth()->user();
        $isRegexAllowed = $user->group->is_modo || $user->group->is_editor;
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
            ->when($this->userBookmarked, fn ($query) => $query->bookmarkedBy($user))
            ->when($this->userWished, fn ($query) => $query->wishedBy($user))
            ->when($this->internal, fn ($query) => $query->internal())
            ->when($this->personalRelease, fn ($query) => $query->personalRelease())
            ->when($this->trumpable, fn ($query) => $query->trumpable())
            ->when($this->alive, fn ($query) => $query->alive())
            ->when($this->dying, fn ($query) => $query->dying())
            ->when($this->dead, fn ($query) => $query->dead())
            ->when($this->graveyard, fn ($query) => $query->graveyard())
            ->when($this->userDownloaded === false, fn ($query) => $query->notDownloadedBy($user))
            ->when($this->userDownloaded === true, fn ($query) => $query->downloadedBy($user))
            ->when($this->userSeeder === true && $this->userActive === true, fn ($query) => $query->seededBy($user))
            ->when($this->userSeeder === false && $this->userActive === true, fn ($query) => $query->leechedBy($user))
            ->when($this->userSeeder === false && $this->userActive === false, fn ($query) => $query->uncompletedBy($user));
    }
}
