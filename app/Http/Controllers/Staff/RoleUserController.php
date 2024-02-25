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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreRoleUserRequest;
use App\Http\Requests\Staff\UpdateRoleUserRequest;
use App\Models\RoleUser;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\UserControllerTest
 */
class RoleUserController extends Controller
{
    public function store(StoreRoleUserRequest $request): \Illuminate\Http\RedirectResponse
    {
        RoleUser::create($request->validated());

        cache()->forget('rbac-user-roles');

        return back()->withSuccess('Role overrided successfully.');
    }

    public function update(UpdateRoleUserRequest $request, RoleUser $roleUser): \Illuminate\Http\RedirectResponse
    {
        $roleUser->update($request->validated());

        cache()->forget('rbac-user-roles');

        return back()->withSuccess('Role override edited succesfully');
    }

    protected function destroy(RoleUser $roleUser): \Illuminate\Http\RedirectResponse
    {
        $roleUser->delete();

        cache()->forget('rbac-user-roles');

        return back()->withSuccess('Role override deleted successfully');
    }
}
