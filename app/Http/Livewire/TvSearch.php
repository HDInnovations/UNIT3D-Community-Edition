<?php

namespace App\Http\Livewire;

use App\Models\Tv;
use Livewire\Component;
use Livewire\WithPagination;

class TvSearch extends Component
{
    use WithPagination;

    protected $updatesQueryString = ['searchTerm'];

    public $searchTerm;

    public function mount()
    {
        $this->searchTerm = request()->query('searchTerm', $this->searchTerm);
    }

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
        $search_term = '%' . $this->searchTerm . '%';

        return view('livewire.tv-search', [
            'shows' => Tv::with('networks', 'genres', 'torrents')->withCount('torrents', 'seasons')->where('name', 'LIKE', $search_term)->orderBy('name', 'asc')->paginate(30)
        ]);
    }
}
