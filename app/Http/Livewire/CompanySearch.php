<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Company;
use Livewire\WithPagination;

class CompanySearch extends Component
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

        return view('livewire.company-search', [
            'companies' => Company::withCount('tv', 'movie')->where('name', 'LIKE', $search_term)->orderBy('name', 'asc')->paginate(30)
        ]);
    }
}
