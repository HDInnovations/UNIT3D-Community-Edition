<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;
use App\Models\Collection;
use Livewire\WithPagination;

class CollectionSearch extends Component
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

        return view('livewire.collection-search', [
            'collections' => Collection::withCount('movie')->with('movie')->where('name', 'LIKE', $search_term)->orderBy('name', 'asc')->paginate(25)
        ]);
    }
}
