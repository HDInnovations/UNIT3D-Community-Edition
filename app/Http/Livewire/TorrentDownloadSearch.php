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

namespace App\Http\Livewire;

use App\Models\Announce;
use App\Models\TorrentDownload;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator<Announce> $announces
 */
class TorrentDownloadSearch extends Component
{
    use WithPagination;

    public int $perPage = 50;

    public string $torrentName = '';

    public string $username = '';

    public string $torrentDownloadType = '';

    public string $from = '';

    public string $until = '';

    public string $groupBy = 'none';

    public string $sortField = 'id';

    public string $sortDirection = 'desc';

    /**
     * @var array<string, mixed>
     */
    protected $queryString = [
        'page'                => ['except' => 1],
        'perPage'             => ['except' => 25],
        'torrentName'         => ['except' => ''],
        'username'            => ['except' => ''],
        'torrentDownloadType' => ['except' => ''],
        'from'                => ['except' => null],
        'until'               => ['except' => null],
        'groupBy'             => ['except' => 'none'],
        'sortField'           => ['except' => 'id'],
        'sortDirection'       => ['except' => 'desc'],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingGroupBy(string $value): void
    {
        $this->sortField = match ($value) {
            'user_id' => 'distinct_torrent_count',
            default   => 'id',
        };

        if ($value === 'user_id') {
            // By default, set `from` to 1 week ago otherwise it takes 20 seconds
            // to load the page if it gets the values for all time
            $this->from = now()->subWeek()->format('Y-m-d');
        }
    }

    final public function updatingFrom(string &$value): void
    {
        $value = $value === '' ? '' : Carbon::parse($value)->format('Y-m-d');

        $this->from = $value;
    }

    final public function updatingUntil(string &$value): void
    {
        $value = $value === '' ? '' : Carbon::parse($value)->format('Y-m-d');

        $this->until = $value;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<TorrentDownload>
     */
    final public function getTorrentDownloadsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return TorrentDownload::query()
            ->with([
                'user' => fn ($query) => $query->with('group')->withTrashed(),
                'torrent:id,name'
            ])
            ->when($this->torrentName !== '', fn ($query) => $query->whereRelation('torrent', 'name', 'LIKE', '%'.$this->torrentName.'%'))
            ->when($this->username !== '', fn ($query) => $query->whereRelation('user', 'username', '=', $this->username))
            ->when($this->torrentDownloadType !== '', fn ($query) => $query->where('type', 'LIKE', '%'.$this->torrentDownloadType.'%'))
            ->when($this->sortField !== '', fn ($query) => $query->orderBy($this->sortField, $this->sortDirection))
            ->when($this->from !== '', fn ($query) => $query->where('created_at', '>=', $this->from))
            ->when($this->until !== '', fn ($query) => $query->where('created_at', '<=', $this->until))
            ->when(
                $this->groupBy === 'user_id',
                fn ($query) => $query->groupBy('user_id')
                    ->select([
                        'user_id',
                        DB::raw('COUNT(*) as download_count'),
                        DB::raw('COUNT(DISTINCT(torrent_id)) as distinct_torrent_count'),
                        DB::raw('MIN(created_at) as created_at_min'),
                        DB::raw('MAX(created_at) as created_at_max'),
                    ])
                    ->withCasts([
                        'created_at_min' => 'datetime',
                        'created_at_max' => 'datetime',
                    ])
            )
            ->paginate($this->perPage);
    }

    final public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'desc';
        }

        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.torrent-download-search', [
            'torrentDownloads' => $this->torrentDownloads,
        ])
            ->extends('layout.default')
            ->section('content');
    }
}
