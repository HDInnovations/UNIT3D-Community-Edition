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

use App\Models\History;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTorrents extends Component
{
    use WithPagination;

    public ?\Illuminate\Contracts\Auth\Authenticatable $user = null;

    public int $perPage = 25;

    public string $name = '';

    public string $unsatisfied = 'any';

    public string $active = 'any';

    public string $completed = 'any';

    public string $uploaded = 'any';

    public string $hitrun = 'any';

    public string $prewarn = 'any';

    public string $immune = 'any';

    public string $downloaded = 'any';

    public array $status = [];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public $showMorePrecision = false;

    protected $queryString = [
        'perPage'           => ['except' => ''],
        'name'              => ['except' => ''],
        'sortField'         => ['except' => 'created_at'],
        'sortDirection'     => ['except' => 'desc'],
        'unsatisfied'       => ['except' => 'any'],
        'active'            => ['except' => 'any'],
        'completed'         => ['except' => 'any'],
        'prewarn'           => ['except' => 'any'],
        'hitrun'            => ['except' => 'any'],
        'immune'            => ['except' => 'any'],
        'uploaded'          => ['except' => 'any'],
        'downloaded'        => ['except' => 'any'],
        'status'            => ['except' => []],
        'showMorePrecision' => ['except' => false],
    ];

    final public function mount($userId): void
    {
        $this->user = User::find($userId);
    }

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getHistoryProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return History::query()
            ->join(
                'torrents',
                fn ($join) => $join
                ->on('history.torrent_id', '=', 'torrents.id')
                ->where('history.user_id', '=', $this->user->id)
            )
            ->select(
                'history.torrent_id',
                'history.agent',
                'history.uploaded',
                'history.downloaded',
                'history.seeder',
                'history.actual_uploaded',
                'history.actual_downloaded',
                'history.seedtime',
                'history.created_at',
                'history.updated_at',
                'history.completed_at',
                'history.immune',
                'history.hitrun',
                'history.prewarn',
                'torrents.name',
                'torrents.seeders',
                'torrents.leechers',
                'torrents.times_completed',
                'torrents.size',
                'torrents.user_id',
                'torrents.status',
            )
            ->selectRaw('IF(torrents.user_id = ?, 1, 0) AS uploader', [$this->user->id])
            ->selectRaw('history.active AND history.seeder AS seeding')
            ->selectRaw('history.active AND NOT history.seeder AS leeching')
            ->selectRaw('TIMESTAMPDIFF(SECOND, history.created_at, history.completed_at) AS leechtime')
            ->selectRaw('CAST(history.uploaded AS float) / CAST((history.downloaded + 1) AS float) AS ratio')
            ->selectRaw('CAST(history.actual_uploaded AS float) / CAST((history.actual_downloaded + 1) AS float) AS actual_ratio')
            ->when(
                $this->name,
                fn ($query) => $query
                ->where('name', 'like', '%'.str_replace(' ', '%', $this->name).'%')
            )
                ->when(
                    $this->unsatisfied === 'exclude',
                    fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                        ->where('seedtime', '>', \config('hitrun.seedtime'))
                        ->orWhere('immune', '=', 1)
                        ->orWhereRaw('actual_downloaded < (torrents.size * ? / 100)', [\config('hitrun.buffer')])
                    )
                )
                ->when(
                    $this->unsatisfied === 'include',
                    fn ($query) => $query
                    ->where('seedtime', '<', \config('hitrun.seedtime'))
                    ->where('immune', '=', 0)
                    ->whereRaw('actual_downloaded > (torrents.size * ? / 100)', [\config('hitrun.buffer')])
                )
            ->when($this->active === 'include', fn ($query) => $query->where('active', '=', 1))
            ->when($this->active === 'exclude', fn ($query) => $query->where(fn ($query) => $query->where('active', '=', 0)->orWhereNull('active')))
            ->when($this->completed === 'include', fn ($query) => $query->where('seeder', '=', 1))
            ->when($this->completed === 'exclude', fn ($query) => $query->where(fn ($query) => $query->where('seeder', '=', 0)->orWhereNull('seeder')))
            ->when($this->prewarn === 'include', fn ($query) => $query->where('prewarn', '=', 1))
            ->when($this->prewarn === 'exclude', fn ($query) => $query->where(fn ($query) => $query->where('prewarn', '=', 0)->orWhereNull('prewarn')))
            ->when($this->hitrun === 'include', fn ($query) => $query->where('hitrun', '=', 1))
            ->when($this->hitrun === 'exclude', fn ($query) => $query->where(fn ($query) => $query->where('hitrun', '=', 0)->orWhereNull('hitrun')))
            ->when($this->immune === 'include', fn ($query) => $query->where('immune', '=', 1))
            ->when($this->immune === 'exclude', fn ($query) => $query->where(fn ($query) => $query->where('immune', '=', 0)->orWhereNull('immune')))
            ->when($this->uploaded === 'include', fn ($query) => $query->where('torrents.user_id', '=', $this->user->id))
            ->when($this->uploaded === 'exclude', fn ($query) => $query->where('torrents.user_id', '<>', $this->user->id))
            ->when($this->downloaded === 'include', fn ($query) => $query->where('history.actual_downloaded', '>', 0))
            ->when($this->downloaded === 'exclude', fn ($query) => $query->where('history.actual_downloaded', '=', 0))
            ->when(! empty($this->status), fn ($query) => $query->whereIntegerInRaw('status', $this->status))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.user-torrents', [
            'histories' => $this->history,
        ]);
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
}
