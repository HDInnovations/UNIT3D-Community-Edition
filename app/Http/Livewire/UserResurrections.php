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

use App\Models\Graveyard;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserResurrections extends Component
{
    use WithPagination;

    public ?User $user = null;

    public int $perPage = 25;

    public string $name = '';

    public string $rewarded = 'any';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'perPage'           => ['except' => ''],
        'name'              => ['except' => ''],
        'rewarded'          => ['except' => 'any'],
        'sortField'         => ['except' => 'created_at'],
        'sortDirection'     => ['except' => 'desc'],
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

    final public function getResurrectionsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Graveyard::query()
            ->with(['torrent', 'user'])
            ->leftJoin('torrents', 'torrents.id', '=', 'graveyard.torrent_id')
            ->where('graveyard.user_id', '=', $this->user->id)
            ->when($this->rewarded === 'include', fn ($query) => $query->where('rewarded', '=', 1))
            ->when($this->rewarded === 'exclude', fn ($query) => $query->where('rewarded', '=', 0))
            ->when($this->name, fn ($query) => $query->where('name', 'like', '%'.\str_replace(' ', '%', $this->name).'%'))
            ->when(
                \in_array($this->sortField, ['created_at', 'seedtime', 'rewarded']),
                fn ($query) => $query->orderBy('graveyard.'.$this->sortField, $this->sortDirection),
                fn ($query) => $query->orderBy('torrents.'.$this->sortField, $this->sortDirection)
            )
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.user-resurrections', [
            'resurrections' => $this->resurrections,
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
