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
use App\Models\User;
use App\Services\Unit3dAnnounce;
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
        return view('Staff.group.index', [
            'groups' => Group::orderBy('position')->get(),
        ]);
    }

    /**
     * Group Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.group.create');
    }

    /**
     * Store A New Group.
     */
    public function store(StoreGroupRequest $request): \Illuminate\Http\RedirectResponse
    {
        $group = Group::create(['slug' => Str::slug($request->name)] + $request->validated());

        foreach (Forum::pluck('id') as $collection) {
            $permission = new Permission();
            $permission->forum_id = $collection;
            $permission->group_id = $group->id;
            $permission->show_forum = 0;
            $permission->read_topic = 0;
            $permission->reply_topic = 0;
            $permission->start_topic = 0;
            $permission->save();
        }

        return to_route('staff.groups.index')
            ->withSuccess('Group Was Created Successfully!');
    }

    /**
     * Group Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.group.edit', [
            'group' => Group::findOrFail($id),
        ]);
    }

    /**
     * Edit A Group.
     */
    public function update(UpdateGroupRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Group::findOrFail($id)->update(['slug' => Str::slug($request->name)] + $request->validated());

        cache()->forget('group:'.$id);

        foreach (User::where('group_id', '=', $id)->get() as $user) {
            Unit3dAnnounce::addUser($user);
        }

        return to_route('staff.groups.index')
            ->withSuccess('Group Was Updated Successfully!');
    }
}
