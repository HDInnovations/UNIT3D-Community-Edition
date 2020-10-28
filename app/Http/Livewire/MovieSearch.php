<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MovieSearch extends Component
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
        $search_term = '%'.$this->searchTerm.'%';

        return view('livewire.movie-search', [
            'movies' => Movie::with('companies', 'genres')->withCount('torrents')->where('title', 'LIKE', $search_term)->orderBy('title', 'asc')->paginate(30),
        ]);
    }
}
