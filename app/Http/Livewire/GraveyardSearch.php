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

use App\Models\Torrent;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class GraveyardSearch extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public $name = '';

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

    public $free;

    public $doubleup;

    public $featured;

    public $stream;

    public $sd;

    public $highspeed;

    public $internal;

    /**
     * @var int
     */
    public $perPage = 25;

    /**
     * @var string
     */
    public $sortField = 'created_at';

    /**
     * @var string
     */
    public $sortDirection = 'desc';

    /**
     * @var bool
     */
    public $showFilters = false;

    /**
     * @var array<string, array<string, int|string|mixed[]|false>>
     */
    protected $queryString = [
        'name'          => ['except' => ''],
        'categories'    => ['except' => []],
        'types'         => ['except' => []],
        'resolutions'   => ['except' => []],
        'tmdbId'        => ['except' => ''],
        'imdbId'        => ['except' => ''],
        'tvdbId'        => ['except' => ''],
        'malId'         => ['except' => ''],
        'free'          => ['except' => false],
        'doubleup'      => ['except' => false],
        'featured'      => ['except' => false],
        'stream'        => ['except' => false],
        'sd'            => ['except' => false],
        'highspeed'     => ['except' => false],
        'internal'      => ['except' => false],
        'sortField'     => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page'          => ['except' => 1],
    ];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function toggleShowFilters(): void
    {
        $this->showFilters = ! $this->showFilters;
    }

    final public function getTorrentsStatProperty()
    {
        return Torrent::where('seeders', '=', 0)
            ->where('created_at', '<', Carbon::now()->copy()->subDays(30)->toDateTimeString())
            ->count();
    }

    final public function getTorrentsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Torrent::with('category', 'type', 'resolution')
            ->where('created_at', '<', Carbon::now()->copy()->subDays(30)->toDateTimeString())
            ->where('seeders', '=', 0)
            ->when($this->name, function ($query): void {
                $query->where('name', 'LIKE', '%'.$this->name.'%');
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
            ->when($this->tmdbId, function ($query): void {
                $query->where('tmdb', '=', $this->tmdbId);
            })
            ->when($this->imdbId, function ($query): void {
                $query->where('imdb', '=', $this->imdbId);
            })
            ->when($this->tvdbId, function ($query): void {
                $query->where('tvdb', '=', $this->tvdbId);
            })
            ->when($this->malId, function ($query): void {
                $query->where('mal', '=', $this->malId);
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
            ->when($this->internal, function ($query): void {
                $query->where('internal', '=', 1);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
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
        return \view('livewire.graveyard-search', [
            'user'         => \auth()->user(),
            'torrents'     => $this->torrents,
            'torrentsStat' => $this->torrentsStat,
        ]);
    }
}
