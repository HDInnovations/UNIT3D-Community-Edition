<?php

namespace App\Http\Livewire\Staff;

use App\Http\Livewire\CollectionSearch;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PrivilegePanel extends Component
{
    use WithPagination;
    public $perPage = 25;
    public $userSearch;
    public $sortField;
    public $sortDirection;

    public function mount()
    {
        $this->userSearch = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        return \view('livewire.staff.privilege-panel',[
            'roles' => Role::query()
            ->with('Privileges')->get(),
            'privileges' => Privilege::all(),
            'users' => User::query()
                ->with(['primaryRole', 'privileges', 'roles'])
                ->when($this->userSearch, function ($query) {
                    return $query->where('username', 'LIKE', '%'.$this->userSearch.'%')->orWhere('email', 'LIKE', '%'.$this->userSearch.'%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }
    public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
