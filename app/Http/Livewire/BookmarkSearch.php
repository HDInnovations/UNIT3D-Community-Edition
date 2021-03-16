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

use App\Models\PersonalFreeleech;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class BookmarkSearch extends Component
{
    use WithPagination;

    public $perPage = 25;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $user;

    final public function paginationView()
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatingSearch()
    {
        $this->resetPage();
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

    final public function getOwnerProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return User::where('username', '=', $this->user->username)->firstOrFail();
    }

    final public function getBookmarksProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->owner->bookmarks()
            ->when($this->search, function ($query) {
                return $query->where('name', 'LIKE', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function getPersonalFreeleechProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return PersonalFreeleech::where('user_id', '=', $this->user->id)->first();
    }

    public function render()
    {
        return \view('livewire.bookmark-search', [
            'user'               => $this->owner,
            'personal_freeleech' => $this->personalFreeleech,
            'bookmarks'          => $this->bookmarks,
        ]);
    }
}
