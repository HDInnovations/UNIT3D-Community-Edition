<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class GroupController extends Controller
{
    /**
     * Display All Groups.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $groups = Group::all()->sortBy('position');

        return view('Staff.group.index', ['groups' => $groups]);
    }

    /**
     * Group Add Form.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        return view('Staff.group.create');
    }

    /**
     * Store A New Group.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
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

        if (!$request->user()->group->is_owner && $request->input('is_owner') == 1) {
            return redirect()->route('staff.groups.index')
                ->withErrors('You are not permitted to create a group with owner permissions!');
        }

        if ($v->fails()) {
            return redirect()->route('staff.groups.index')
                ->withErrors($v->errors());
        }
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
        return redirect()->route('staff.groups.index')
            ->withSuccess('Group Was Created Successfully!');
    }

    /**
     * Group Edit Form.
     *
     * @param Request $request
     * @param $id
     *
     * @return Factory|View
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_admin, 403);

        $group = Group::findOrFail($id);

        return view('Staff.group.edit', ['group' => $group]);
    }

    /**
     * Edit A Group.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
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

        if (!$request->user()->group->is_owner && $request->input('is_owner') == 1) {
            return redirect()->route('staff.groups.index')
                ->withErrors('You are not permitted to give a group owner permissions!');
        }

        if ($v->fails()) {
            return redirect()->route('staff.groups.index')
                ->withErrors($v->errors());
        }
        $group->save();
        return redirect()->route('staff.groups.index')
            ->withSuccess('Group Was Updated Successfully!');
    }
}
