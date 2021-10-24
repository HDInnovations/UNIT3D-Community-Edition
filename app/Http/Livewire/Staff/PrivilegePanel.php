<?php

namespace App\Http\Livewire\Staff;

use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class PrivilegePanel extends Component
{
    use WithPagination;
    public $perPage = 25;
    public $userSearch;
    public $sortField;
    public $sortDirection;
    public $RolesPrivileges = null;
    public $RolesRestrictions = null;
    public $role;
    public User $ActiveUser;

    public function mount()
    {
        $this->userSearch = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        return \view('livewire.staff.privilege-panel', [
            'roles' => Role::query()
            ->with('Privileges')->get(),
            'privileges' => Privilege::all(),
            'users'      => User::query()
                ->with(['primaryRole', 'privileges', 'roles'])
                ->when($this->userSearch, function ($query) {
                    return $query->where('username', 'LIKE', '%'.$this->userSearch.'%')->orWhere('email', 'LIKE', '%'.$this->userSearch.'%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage),
            'RolesPrivileges' => $this->RolesPrivileges,
            'RolesRestrictions' => $this->RolesRestrictions,
            'Role' => $this->role

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

    final public function GetRolesPrivileges($roleSlug)
    {
        $role = Role::where('slug', '=', $roleSlug)->with(['privileges', 'RoleRestrictedPrivileges'])->first();
        $this->role = $role;
        $this->RolesRestrictions = $role->RoleRestrictedPrivileges;
        $this->RolesPrivileges = $role->privileges->whereNotIn('id', $role->RoleRestrictedPrivileges->pluck('privilege_id')->toArray());
    }

    public function GiveRolePrivilege($roleSlug, $privSlug)
    {
        $role = Role::where('slug', '=', $roleSlug)->first();
        $priv = Privilege::where('slug', '=', $privSlug)->first();
        $role->privileges()->attach($priv);
        $role->RoleRestrictedPrivileges()->detach($priv);
        $this->GetRolesPrivileges($roleSlug);
    }

    public function RemoveRolePrivilege($roleSlug, $privSlug)
    {
        $role = Role::where('slug', '=', $roleSlug)->first();
        $priv = Privilege::where('slug', '=', $privSlug)->first();
        $role->privileges()->detach($priv);
        $role->RoleRestrictedPrivileges()->detach($priv);
        $this->GetRolesPrivileges($roleSlug);
    }

    public function RestrictRolePrivilege($roleSlug, $privSlug)
    {
        $role = Role::where('slug', '=', $roleSlug)->first();
        $priv = Privilege::where('slug', '=', $privSlug)->first();
        $role->privileges()->detach($priv);
        $role->RoleRestrictedPrivileges()->attach($priv);
        $this->GetRolesPrivileges($roleSlug);
    }

    final public function GetUser(User $user)
    {
        $this->ActiveUser = $user;
    }

    public function GiveUserPrivilege(User $ActiveUser, $privSlug)
    {
        $priv = Privilege::where('slug', '=', $privSlug)->first();
        $ActiveUser->privileges()->attach($priv);
        $ActiveUser->UserRestrictedPrivileges()->detach($priv);
        Cache::forget('priv-'.$ActiveUser->id.'-'.$priv->slug);
        $this->GetUser($ActiveUser);
    }
    public function RemoveUserPrivilege(User $ActiveUser, $privSlug)
    {
        $priv = Privilege::where('slug', '=', $privSlug)->first();
        $ActiveUser->privileges()->detach($priv);
        $ActiveUser->UserRestrictedPrivileges()->detach($priv);
        Cache::forget('priv-'.$ActiveUser->id.'-'.$priv->slug);
        $this->GetUser($ActiveUser);
    }
    public function RestrictUserPrivilege(User $ActiveUser, $privSlug)
    {
        $priv = Privilege::where('slug', '=', $privSlug)->first();
        $ActiveUser->privileges()->detach($priv);
        $ActiveUser->UserRestrictedPrivileges()->attach($priv);
        Cache::forget('priv-'.$ActiveUser->id.'-'.$priv->slug);
        $this->GetUser($ActiveUser);

    }
    public function GiveUserRole(User $ActiveUser, $roleSlug)
    {
        $role = Role::where('slug', '=', $roleSlug)->first();
        $ActiveUser->roles()->attach($role);
        $this->GetUser($ActiveUser);
    }
    public function RemoveUserRole(User $ActiveUser, $roleSlug)
    {
        $role = Role::where('slug', '=', $roleSlug)->first();
        $ActiveUser->roles()->detach($role);
        $this->GetUser($ActiveUser);
    }
}
