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
    public $searchTerm = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $user;

    public function paginationView()
    {
        return 'vendor.pagination.livewire-pagination';
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        $user = User::where('username', '=', $this->user->username)->firstOrFail();

        $bookmarks = $user->bookmarks()
            ->when($this->searchTerm, function ($query) {
                return $query->where('name', 'like', '%'.$this->searchTerm.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $personalFreeleech = PersonalFreeleech::where('user_id', '=', $this->user->id)->first();

        return view('livewire.bookmark-search', [
            'user'               => $user,
            'personal_freeleech' => $personalFreeleech,
            'bookmarks'          => $bookmarks,
        ]);
    }
}
