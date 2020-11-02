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

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MovieSearch extends Component
{
    use WithPagination;

    protected $queryString = ['searchTerm'];

    public $searchTerm;

    public function paginationView()
    {
        return 'vendor.pagination.livewire-pagination';
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search_term = '%'.$this->searchTerm.'%';

        return view('livewire.movie-search', [
            'movies' => Movie::with('companies', 'genres')->withCount('torrents')->where('title', 'LIKE', $search_term)->orderBy('title', 'asc')->paginate(30),
        ]);
    }
}
