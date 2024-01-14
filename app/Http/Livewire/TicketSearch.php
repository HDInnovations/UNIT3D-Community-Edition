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
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator $tickets
 */
class TicketSearch extends Component
{
    use WithPagination;

    public ?User $user = null;

    public string $tab = 'open';

    public int $perPage = 25;

    public string $search = '';

    public string $sortField = 'updated_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'tab'    => ['except' => 'open'],
    ];

    final public function mount(): void
    {
        $this->user = auth()->user();
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function updatingTab(): void
    {
        $this->resetPage();
    }

    final public function getTicketsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Ticket::query()
            ->with(['user.group', 'staff.group', 'category', 'priority'])
            ->when(!$this->user->group->is_modo, fn ($query) => $query->where('user_id', '=', $this->user->id))
            ->when(
                $this->tab === 'open',
                fn ($query) => $query->whereNull('closed_at'),
                fn ($query) => $query->whereNotNull('closed_at')
            )
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
        return view('livewire.ticket-search', [
            'tickets' => $this->tickets,
        ]);
    }
}
