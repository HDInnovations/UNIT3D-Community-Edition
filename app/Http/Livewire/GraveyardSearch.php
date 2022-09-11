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
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class GraveyardSearch extends Component
{
    use WithPagination;

    public string $name = '';

    public array $categories = [];

    public array $types = [];

    public array $resolutions = [];

    public string $tmdbId = '';

    public string $imdbId = '';

    public string $tvdbId = '';

    public string $malId = '';

    public array $free = [];

    public bool $doubleup = false;

    public bool $featured = false;

    public bool $stream = false;

    public bool $sd = false;

    public bool $highspeed = false;

    public bool $internal = false;

    public int $perPage = 25;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public bool $showFilters = false;

    protected $queryString = [
        'name'          => ['except' => ''],
        'categories'    => ['except' => []],
        'types'         => ['except' => []],
        'resolutions'   => ['except' => []],
        'tmdbId'        => ['except' => ''],
        'imdbId'        => ['except' => ''],
        'tvdbId'        => ['except' => ''],
        'malId'         => ['except' => ''],
        'free'          => ['except' => []],
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
        $user = \auth()->user();
        $isRegexAllowed = $user->group->is_modo;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen($field) >= 2
            && $field[0] === '/'
            && $field[-1] === '/';

        return Torrent::with('category', 'type', 'resolution')
            ->where('created_at', '<', Carbon::now()->copy()->subDays(30)->toDateTimeString())
            ->dead()
            ->when($this->name !== '', fn ($query) => $query->ofName($this->name, $isRegex($this->name)))
            ->when($this->categories !== [], fn ($query) => $query->ofCategory($this->categories))
            ->when($this->types !== [], fn ($query) => $query->ofType($this->types))
            ->when($this->resolutions !== [], fn ($query) => $query->ofResolution($this->resolutions))
            ->when($this->tmdbId !== '', fn ($query) => $query->ofTmdb((int) $this->tmdbId))
            ->when($this->imdbId !== '', fn ($query) => $query->ofImdb((int) (\preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)))
            ->when($this->tvdbId !== '', fn ($query) => $query->ofTvdb((int) $this->tvdbId))
            ->when($this->malId !== '', fn ($query) => $query->ofMal((int) $this->malId))
            ->when($this->free !== [], fn ($query) => $query->ofFreeleech($this->free))
            ->when($this->doubleup !== false, fn ($query) => $query->doubleup())
            ->when($this->featured !== false, fn ($query) => $query->featured())
            ->when($this->stream !== false, fn ($query) => $query->streamOptimized())
            ->when($this->sd !== false, fn ($query) => $query->sd())
            ->when($this->highspeed !== false, fn ($query) => $query->highspeed())
            ->when($this->internal !== false, fn ($query) => $query->internal())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function sortBy($field): void
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
