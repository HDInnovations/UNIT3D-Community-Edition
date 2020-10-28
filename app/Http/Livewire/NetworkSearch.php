<?php

namespace App\Http\Livewire;

use App\Models\Network;
use Livewire\Component;
use Livewire\WithPagination;

class NetworkSearch extends Component
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

        return view('livewire.network-search', [
            'networks' => Network::withCount('tv')->where('name', 'LIKE', $search_term)->orderBy('name', 'asc')->paginate(30),
        ]);
    }
}
