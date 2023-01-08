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
use App\Http\Requests\Staff\StoreGroupRequest;
use App\Http\Requests\Staff\UpdateGroupRequest;
use App\Models\Forum;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Support\Str;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\GroupControllerTest
 */
class GroupController extends Controller
{
    /**
     * Display All Groups.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $groups = Group::all()->sortBy('position');

        return \view('Staff.group.index', ['groups' => $groups]);
    }

    /**
     * Group Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.group.create');
    }

    /**
     * Store A New Group.
     */
    public function store(StoreGroupRequest $request): \Illuminate\Http\RedirectResponse
    {
        $group = Group::create(['slug' => Str::slug($request->name)] + $request->validated());

        foreach (Forum::all()->pluck('id') as $collection) {
            $permission = new Permission();
            $permission->forum_id = $collection;
            $permission->group_id = $group->id;
            $permission->show_forum = 1;
            $permission->read_topic = 1;
            $permission->reply_topic = 1;
            $permission->start_topic = 1;
            $permission->save();
        }

        return \to_route('staff.groups.index')
            ->withSuccess('Group Was Created Successfully!');
    }

    /**
     * Group Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $group = Group::findOrFail($id);

        return \view('Staff.group.edit', ['group' => $group]);
    }

    /**
     * Edit A Group.
     */
    public function update(UpdateGroupRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Group::where('id', '=', $id)->update(['slug' => Str::slug($request->name)] + $request->validated());

        \cache()->forget('group:'.$id);

        return \to_route('staff.groups.index')
            ->withSuccess('Group Was Updated Successfully!');
    }
}
