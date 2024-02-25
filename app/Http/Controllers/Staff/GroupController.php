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
use App\Models\ForumPermission;
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
            ForumPermission::create([
                'forum_id'    => $collection,
                'group_id'    => $group->id,
                'read_topic'  => false,
                'reply_topic' => false,
                'start_topic' => false,
            ]);
        }

        Unit3dAnnounce::addGroup($group);

        return to_route('staff.groups.index')
            ->withSuccess('Group Was Created Successfully!');
    }

    /**
     * Group Edit Form.
     */
    public function edit(Group $group): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.group.edit', [
            'group' => $group,
        ]);
    }

    /**
     * Edit A Group.
     */
    public function update(UpdateGroupRequest $request, Group $group): \Illuminate\Http\RedirectResponse
    {
        $group->update(['slug' => Str::slug($request->name)] + $request->validated());

        cache()->forget('group:'.$group->id);

        Unit3dAnnounce::addGroup($group);

        return to_route('staff.groups.index')
            ->withSuccess('Group Was Updated Successfully!');
    }
}
