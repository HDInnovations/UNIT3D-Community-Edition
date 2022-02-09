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

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class TicketSearch extends Component
{
    use WithPagination;

    public ?\Illuminate\Contracts\Auth\Authenticatable $user = null;

    public bool $show = false;

    public int $perPage = 25;

    public string $search = '';

    public string $sortField = 'updated_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'show'   => ['except' => false],
    ];

    final public function mount(): void
    {
        $this->user = \auth()->user();
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

    final public function updatingShow(): void
    {
        $this->resetPage();
    }

    final public function toggleProperties($property): void
    {
        if ($property === 'show') {
            $this->show = ! $this->show;
        }
    }

    final public function getTicketsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($this->user->group->is_modo) {
            return Ticket::query()
                ->with(['user', 'category', 'priority'])
                ->when($this->show === false, fn ($query) => $query->whereNull('closed_at'))
                ->when($this->show, fn ($query) => $query->whereNotNull('closed_at')->orWhereNull('closed_at'))
                ->when($this->search, fn ($query) => $query->where('subject', 'LIKE', '%'.$this->search.'%'))
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        }

        return Ticket::query()
            ->with(['user', 'category', 'priority'])
            ->where('user_id', '=', $this->user->id)
            ->when($this->show === false, fn ($query) => $query->whereNull('closed_at'))
            ->when($this->show, fn ($query) => $query->whereNotNull('closed_at'))
            ->when($this->search, fn ($query) => $query->where('subject', 'LIKE', '%'.$this->search.'%'))
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
        return \view('livewire.ticket-search', [
            'tickets' => $this->tickets,
        ]);
    }
}
