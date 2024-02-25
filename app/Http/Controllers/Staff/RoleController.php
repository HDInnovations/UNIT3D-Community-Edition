<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreRoleRequest;
use App\Http\Requests\Staff\UpdateRoleRequest;
use App\Models\PermissionRole;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.role.index', [
            'roles' => Role::query()->orderBy('position')->get(),
        ]);
    }

    public function store(StoreRoleRequest $request): \Illuminate\Http\RedirectResponse
    {
        Role::create($request->validated());

        return to_route('staff.roles.index')
            ->withSuccess('Role has been created successfully');
    }

    public function edit(Role $role): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.role.edit', [
            'role'          => $role->loadExists('permissions'),
            'permissionIds' => PermissionRole::query()->whereBelongsTo($role)->pluck('authorized', 'permission_id'),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        $role->update($request->validated('role'));

        /** @var array<int, array{permission_id: int, role_id: int, authorized: bool}> $permissions */
        $permissions = $request->validated('permissions');

        $role->permissions()->sync(collect($permissions)->keyBy('permission_id'));

        return to_route('staff.roles.edit', ['role' => $role->id])
            ->withSuccess('Role permissions have been edited successfully');
    }

    public function destroy(Role $role): \Illuminate\Http\RedirectResponse
    {
        abort_if($role->system_required, 403);

        $role->delete();

        return to_route('staff.roles.index')
            ->withSuccess('Forum has been deleted successfully');
    }
}
