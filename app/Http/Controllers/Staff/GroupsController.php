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
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * GroupsController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get All Groups.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $groups = Group::all()->sortBy('position');

        return view('Staff.groups.index', ['groups' => $groups]);
    }

    /**
     * Group Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
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
        $group = new Group();
        $group->name = $request->input('name');
        $group->slug = str_slug($request->input('name'));
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

        if ($v->fails()) {
            return redirect()->route('staff_groups_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
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
                ->with($this->toastr->success('Group Was Created Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Group Edit Form.
     *
     * @param $group
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($group, $id)
    {
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
        $group = Group::findOrFail($id);

        $group->name = $request->input('name');
        $group->slug = str_slug($request->input('name'));
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

        if ($v->fails()) {
            return redirect()->route('staff_groups_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $group->save();

            return redirect()->route('staff_groups_index')
                ->with($this->toastr->success('Group Was Updated Successfully!', 'Yay!', ['options']));
        }
    }
}
