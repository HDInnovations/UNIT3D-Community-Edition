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

namespace App\Http\Livewire;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\History;
use App\Models\Keyword;
use App\Models\PersonalFreeleech;
use App\Models\PlaylistTorrent;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TorrentListSearch extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var string
     */
    public $mediainfo = '';

    /**
     * @var string
     */
    public $uploader = '';

    /**
     * @var mixed[]
     */
    public $keywords = [];

    /**
     * @var string
     */
    public $startYear = '';

    /**
     * @var string
     */
    public $endYear = '';

    /**
     * @var mixed[]
     */
    public $categories = [];

    /**
     * @var mixed[]
     */
    public $types = [];

    /**
     * @var mixed[]
     */
    public $resolutions = [];

    /**
     * @var mixed[]
     */
    public $genres = [];

    /**
     * @var string
     */
    public $tmdbId = '';

    /**
     * @var string
     */
    public $imdbId = '';

    /**
     * @var string
     */
    public $tvdbId = '';

    /**
     * @var string
     */
    public $malId = '';

    /**
     * @var string
     */
    public $playlistId = '';

    /**
     * @var string
     */
    public $collectionId = '';

    public $free;

    public $doubleup;

    public $featured;

    public $stream;

    public $sd;

    public $highspeed;

    public $bookmarked;

    public $wished;

    public $internal;

    public $personalRelease;

    public $alive;

    public $dying;

    public $dead;

    public $notDownloaded;

    public $downloaded;

    public $seeding;

    public $leeching;

    public $incomplete;

    /**
     * @var int
     */
    public $perPage = 25;

    /**
     * @var string
     */
    public $sortField = 'bumped_at';

    /**
     * @var string
     */
    public $sortDirection = 'desc';

    /**
     * @var array<string, array<string, int|string|mixed[]|false>>
     */
    protected $queryString = [
        'name'             => ['except' => ''],
        'description'      => ['except' => ''],
        'mediainfo'        => ['except' => ''],
        'uploader'         => ['except' => ''],
        'keywords'         => ['except' => []],
        'startYear'        => ['except' => ''],
        'endYear'          => ['except' => ''],
        'categories'       => ['except' => []],
        'types'            => ['except' => []],
        'resolutions'      => ['except' => []],
        'genres'           => ['except' => []],
        'tmdbId'           => ['except' => ''],
        'imdbId'           => ['except' => ''],
        'tvdbId'           => ['except' => ''],
        'malId'            => ['except' => ''],
        'playlistId'       => ['except' => ''],
        'collectionId'     => ['except' => ''],
        'free'             => ['except' => false],
        'doubleup'         => ['except' => false],
        'featured'         => ['except' => false],
        'stream'           => ['except' => false],
        'sd'               => ['except' => false],
        'highspeed'        => ['except' => false],
        'bookmarked'       => ['except' => false],
        'wished'           => ['except' => false],
        'internal'         => ['except' => false],
        'personalRelease'  => ['except' => false],
        'alive'            => ['except' => false],
        'dying'            => ['except' => false],
        'dead'             => ['except' => false],
        'downloaded'       => ['except' => false],
        'seeding'          => ['except' => false],
        'leeching'         => ['except' => false],
        'incomplete'       => ['except' => false],
        'sortField'        => ['except' => 'bumped_at'],
        'sortDirection'    => ['except' => 'desc'],
        'page'             => ['except' => 1],
        'perPage'          => ['except' => ''],
    ];

    /**
     * @var array<string, string>
     */
    protected $rules = [
        'genres.*' => 'exists:genres,id',
    ];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingName(): void
    {
        $this->resetPage();
    }

    final public function getTorrentsStatProperty(): ?object
    {
        return DB::table('torrents')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(case when seeders > 0 then 1 end) as alive')
            ->selectRaw('count(case when seeders = 0 then 1 end) as dead')
            ->first();
    }

    final public function getPersonalFreeleechProperty()
    {
        return PersonalFreeleech::where('user_id', '=', \auth()->user()->id)->first();
    }

    final public function getTorrentsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->withCount(['thanks', 'comments'])
            ->when($this->name, function ($query): void {
                $terms = \explode(' ', $this->name);
                $search = '';
                foreach ($terms as $term) {
                    $search .= '%'.$term.'%';
                }

                $query->where('name', 'LIKE', $search);
            })
            ->when($this->description, function ($query): void {
                $query->where('description', 'LIKE', '%'.$this->description.'%');
            })
            ->when($this->mediainfo, function ($query): void {
                $query->where('mediainfo', 'LIKE', '%'.$this->mediainfo.'%');
            })
            ->when($this->uploader, function ($query): void {
                $match = User::where('username', 'LIKE', '%'.$this->uploader.'%')->orderBy('username', 'ASC')->first();
                if ($match) {
                    $query->where('user_id', '=', $match->id)->where('anon', '=', 0);
                }
            })
            ->when($this->keywords, function ($query): void {
                $keywords = self::parseKeywords($this->keywords);
                $keyword = Keyword::whereIn('name', $keywords)->pluck('torrent_id');
                $query->whereIn('id', $keyword);
            })
            ->when($this->startYear && $this->endYear, function ($query): void {
                $query->whereBetween('release_year', [$this->startYear, $this->endYear]);
            })
            ->when($this->categories, function ($query): void {
                $query->whereIn('category_id', $this->categories);
            })
            ->when($this->types, function ($query): void {
                $query->whereIn('type_id', $this->types);
            })
            ->when($this->resolutions, function ($query): void {
                $query->whereIn('resolution_id', $this->resolutions);
            })
            ->when($this->genres, function ($query): void {
                $this->validate();

                $tvCollection = DB::table('genre_tv')->whereIn('genre_id', $this->genres)->pluck('tv_id');
                $movieCollection = DB::table('genre_movie')->whereIn('genre_id', $this->genres)->pluck('movie_id');
                $mergedCollection = $tvCollection->merge($movieCollection);

                $query->whereRaw("tmdb in ('".\implode("','", $mergedCollection->toArray())."')"); // Protected with Validation that IDs passed are not malicious
                //$query->whereIn('tmdb', $mergedCollection); Very SLOW!
            })
            ->when($this->tmdbId === '0' || $this->tmdbId, function ($query): void {
                $query->where('tmdb', '=', $this->tmdbId);
            })
            ->when($this->imdbId === '0' || $this->imdbId, function ($query): void {
                $query->where('imdb', '=', $this->imdbId);
            })
            ->when($this->tvdbId === '0' || $this->tvdbId, function ($query): void {
                $query->where('tvdb', '=', $this->tvdbId);
            })
            ->when($this->malId === '0' || $this->malId, function ($query): void {
                $query->where('mal', '=', $this->malId);
            })
            ->when($this->playlistId, function ($query): void {
                $playlist = PlaylistTorrent::where('playlist_id', '=', $this->playlistId)->pluck('torrent_id');
                $query->whereIn('id', $playlist);
            })
            ->when($this->collectionId, function ($query): void {
                $categories = Category::where('movie_meta', '=', 1)->pluck('id');
                $collection = DB::table('collection_movie')->where('collection_id', '=', $this->collectionId)->pluck('movie_id');
                $query->whereIn('category_id', $categories)->whereIn('tmdb', $collection);
            })
            ->when($this->free, function ($query): void {
                $query->where('free', '=', 1);
            })
            ->when($this->doubleup, function ($query): void {
                $query->where('doubleup', '=', 1);
            })
            ->when($this->featured, function ($query): void {
                $query->where('featured', '=', 1);
            })
            ->when($this->stream, function ($query): void {
                $query->where('stream', '=', 1);
            })
            ->when($this->sd, function ($query): void {
                $query->where('sd', '=', 1);
            })
            ->when($this->highspeed, function ($query): void {
                $query->where('highspeed', '=', 1);
            })
            ->when($this->bookmarked, function ($query): void {
                $bookmarks = Bookmark::where('user_id', '=', \auth()->user()->id)->pluck('torrent_id');
                $query->whereIn('id', $bookmarks);
            })
            ->when($this->wished, function ($query): void {
                $wishes = Wish::where('user_id', '=', \auth()->user()->id)->pluck('tmdb');
                $query->whereIn('tmdb', $wishes);
            })
            ->when($this->internal, function ($query): void {
                $query->where('internal', '=', 1);
            })
            ->when($this->personalRelease, function ($query): void {
                $query->where('personal_release', '=', 1);
            })
            ->when($this->alive, function ($query): void {
                $query->where('seeders', '>=', 1);
            })
            ->when($this->dying, function ($query): void {
                $query->where('seeders', '=', 1)->where('times_completed', '>=', 3);
            })
            ->when($this->dead, function ($query): void {
                $query->where('seeders', '=', 0);
            })
            ->when($this->notDownloaded, function ($query): void {
                $history = History::where('user_id', '=', \auth()->user()->id)->pluck('info_hash')->toArray();
                if (! $history || ! \is_array($history)) {
                    $history = [];
                }

                $query->whereNotIn('info_hash', $history);
            })
            ->when($this->downloaded, function ($query): void {
                $query->whereHas('history', function ($query): void {
                    $query->where('user_id', '=', \auth()->user()->id);
                });
            })
            ->when($this->seeding, function ($query): void {
                $query->whereHas('history', function ($q): void {
                    $q->where('user_id', '=', \auth()->user()->id)->where('active', '=', true)->where('seeder', '=', true);
                });
            })
            ->when($this->leeching, function ($query): void {
                $query->whereHas('history', function ($q): void {
                    $q->where('user_id', '=', \auth()->user()->id)->where('active', '=', true)->where('seedtime', '=', '0');
                });
            })
            ->when($this->incomplete, function ($query): void {
                $query->whereHas('history', function ($q): void {
                    $q->where('user_id', '=', \auth()->user()->id)->where('active', '=', false)->where('seeder', '=', false)->where('seedtime', '=', '0');
                });
            })
            ->orderBy('sticky', 'desc')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * @return string[]
     */
    private static function parseKeywords($text): array
    {
        $parts = \explode(', ', $text);
        $result = [];
        foreach ($parts as $part) {
            $part = \trim($part);
            if ($part != '') {
                $result[] = $part;
            }
        }

        return $result;
    }

    final public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.torrent-list-search', [
            'user'              => User::with('history')->findOrFail(\auth()->user()->id),
            'torrents'          => $this->torrents,
            'torrentsStat'      => $this->torrentsStat,
            'personalFreeleech' => $this->personalFreeleech,
        ]);
    }
}
