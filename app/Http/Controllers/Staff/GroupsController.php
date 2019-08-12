<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Models\Forum;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    /**
     * Get All Groups.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $groups = Group::all()->sortBy('position');

        return view('Staff.groups.index', ['groups' => $groups]);
    }

    /**
     * Group Add Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        return view('Staff.groups.add');
    }

    /**
     * Add A Group.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $group = new Group();
        $group->name = $request->input('name');
        $group->slug = Str::slug($request->input('name'));
        $group->position = $request->input('position');
        $group->level = $request->input('level');
        $group->color = $request->input('color');
        $group->icon = $request->input('icon');
        $group->effect = $request->input('effect');
        $group->is_internal = $request->input('is_internal');
        $group->is_modo = $request->input('is_modo');
        $group->is_admin = $request->input('is_admin');
        $group->is_owner = $request->input('is_owner');
        $group->is_trusted = $request->input('is_trusted');
        $group->is_immune = $request->input('is_immune');
        $group->is_freeleech = $request->input('is_freeleech');
        $group->is_incognito = $request->input('is_incognito');
        $group->can_upload = $request->input('can_upload');
        $group->autogroup = $request->input('autogroup');

        $v = validator($group->toArray(), [
            'name'     => 'required|unique:groups',
            'slug'     => 'required|unique:groups',
            'position' => 'required',
            'color'    => 'required',
            'icon'     => 'required',
        ]);

        if (! $request->user()->group->is_owner && $request->input('is_owner') == 1) {
            return redirect()->route('staff_groups_index')
                ->withErrors('You are not permitted to create a group with owner permissions!');
        }

        if ($v->fails()) {
            return redirect()->route('staff_groups_index')
                ->withErrors($v->errors());
        } else {
            $group->save();

            foreach (Forum::all()->pluck('id') as $forum_id) {
                $permission = new Permission();
                $permission->forum_id = $forum_id;
                $permission->group_id = $group->id;
                $permission->show_forum = 1;
                $permission->read_topic = 1;
                $permission->reply_topic = 1;
                $permission->start_topic = 1;
                $permission->save();
            }

            return redirect()->route('staff_groups_index')
                ->withSuccess('Group Was Created Successfully!');
        }
    }

    /**
     * Group Edit Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param $group
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm(Request $request, $group, $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $group = Group::findOrFail($id);

        return view('Staff.groups.edit', ['group' => $group]);
    }

    /**
     * Edit A Group.
     *
     * @param \Illuminate\Http\Request $request
     * @param $group
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $group, $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $group = Group::findOrFail($id);

        $group->name = $request->input('name');
        $group->slug = Str::slug($request->input('name'));
        $group->position = $request->input('position');
        $group->level = $request->input('level');
        $group->color = $request->input('color');
        $group->icon = $request->input('icon');
        $group->effect = $request->input('effect');
        $group->is_internal = $request->input('is_internal');
        $group->is_modo = $request->input('is_modo');
        $group->is_admin = $request->input('is_admin');
        $group->is_owner = $request->input('is_owner');
        $group->is_trusted = $request->input('is_trusted');
        $group->is_immune = $request->input('is_immune');
        $group->is_freeleech = $request->input('is_freeleech');
        $group->is_incognito = $request->input('is_incognito');
        $group->can_upload = $request->input('can_upload');
        $group->autogroup = $request->input('autogroup');

        $v = validator($group->toArray(), [
            'name'     => 'required',
            'slug'     => 'required',
            'position' => 'required',
            'color'    => 'required',
            'icon'     => 'required',
        ]);

        if (! $request->user()->group->is_owner && $request->input('is_owner') == 1) {
            return redirect()->route('staff_groups_index')
                ->withErrors('You are not permitted to give a group owner permissions!');
        }

        if ($v->fails()) {
            return redirect()->route('staff_groups_index')
                ->withErrors($v->errors());
        } else {
            $group->save();

            return redirect()->route('staff_groups_index')
                ->withSuccess('Group Was Updated Successfully!');
        }
    }
}
