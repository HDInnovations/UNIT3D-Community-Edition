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

use Livewire\Component;
use App\Models\Watchlist;
use Livewire\WithPagination;

class WatchlistSearch extends Component
{
    use WithPagination;

    public $user;
    public $perPage = 25;
    public $searchTerm = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    final public function paginationView()
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    final public function mount()
    {
        $this->user = \auth()->user();
    }

    final public function getUsersProperty()
    {
        return Watchlist::query()
            ->with(['user', 'author'])
            ->when($this->searchTerm, function ($query) {
                return $query->where('message', 'LIKE', '%'.$this->searchTerm.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View | \Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.watchlist-search', [
            'watchedUsers' => $this->users
        ]);
    }
}
