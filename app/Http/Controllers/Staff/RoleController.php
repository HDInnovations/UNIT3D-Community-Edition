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
use App\Models\Forum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display All Roles.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_viewAll_role'), 403);

        $roles = Role::orderBy('position', 'desc')->get();

        return \view('Staff.role.index', ['roles' => $roles]);
    }

    /**
     * Show A Specific Role.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_view_role'), 403);

        $role = Role::findOrFail($id);

        return \view('Staff.role.show', ['role' => $role]);
    }

    /**
     * Show The Page To Edit A Role.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_update_role'), 403);

        $role = Role::findOrFail($id);

        return \view('Staff.role.edit', ['role' => $role]);
    }

    /**
     * Update A Role.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_update_role'), 403);

        $role = Role::findOrFail($id);

        $role->position = $request->input('position');
        $role->name = $request->input('name');
        $role->slug = Str::slug($request->input('name'));
        $role->description = $request->input('description');
        $role->color = $request->input('color');
        $role->icon = $request->input('icon');
        $role->effect = $request->input('effect');
        $role->rule_id = $request->input('rule_id');
        $role->system_required = $request->input('system_required');

        $v = \validator($role->toArray(), [
            'position'        => 'required|numeric',
            'name'            => 'required',
            'slug'            => 'required',
            'description'     => 'nullable|max:255',
            'color'           => 'required',
            'icon'            => 'required',
            'effect'          => 'nullable',
            'rule_id'         => 'nullable|numeric',
            'system_required' => 'required|boolean',
        ]);

        if ($v->fails()) {
            return \redirect()->route('staff.roles.index')
                ->withErrors($v->errors());
        }
        $role->save();

        return \redirect()->route('staff.roles.index')
            ->withSuccess('Role Was Updated Successfully!');
    }

    /**
     * Show The Page To Create A New Role.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_create_role'), 403);

        return \view('Staff.role.create');
    }

    /**
     * Store The New Role In Database.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_create_role'), 403);

        $role = new Role();
        $role->position = $request->input('position');
        $role->name = $request->input('name');
        $role->slug = Str::slug($request->input('name'));
        $role->description = $request->input('description');
        $role->color = $request->input('color');
        $role->icon = $request->input('icon');
        $role->effect = $request->input('effect');
        $role->rule_id = $request->input('rule_id');
        $role->system_required = $request->input('system_required');

        $v = \validator($role->toArray(), [
            'position'        => 'required|numeric',
            'name'            => 'required|unique:groups',
            'slug'            => 'required|unique:groups',
            'description'     => 'nullable|max:255',
            'color'           => 'required',
            'icon'            => 'required',
            'effect'          => 'nullable',
            'rule_id'         => 'nullable|numeric',
            'system_required' => 'required|boolean',
        ]);

        if ($v->fails()) {
            return \redirect()->route('staff.roles.index')
                ->withErrors($v->errors());
        }
        $role->save();

        foreach (Forum::all()->pluck('id') as $forum_id) {
            $permission = new Permission();
            $permission->forum_id = $forum_id;
            $permission->group_id = $role->id;
            $permission->show_forum = 1;
            $permission->read_topic = 1;
            $permission->reply_topic = 1;
            $permission->start_topic = 1;
            $permission->save();
        }

        return \redirect()->route('staff.roles.index')
            ->withSuccess('Role Was Created Successfully!');
    }

    /**
     * Delete A Specific Role.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        \abort_unless($request->user()->hasPermissionTo('dashboard_can_delete_role'), 403);

        $role = Role::findOrFail($id);

        if ($role->system_required) {
            return \redirect()->route('staff.roles.index')
                ->withErrors('Role Is System Required And Cannot Be Deleted!');
        }

        $role->delete();

        return \redirect()->route('staff.roles.index')
            ->withSuccess('Role Successfully Deleted!');
    }
}
