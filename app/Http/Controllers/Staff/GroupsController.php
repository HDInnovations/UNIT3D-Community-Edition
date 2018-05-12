<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Forum;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use App\Group;
use \Toastr;

class GroupsController extends Controller
{

    /**
     * Get All Groups
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $groups = Group::all()->sortBy('position');

        return view('Staff.groups.index', ['groups' => $groups]);
    }

    /**
     * Group Add Form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.groups.add');
    }

    /**
     * Add Group
     *
     */
    public function add(Request $request)
    {
        $data = $request->except(['_token']);
        // Additional data not passed by this form like the slug :/
        $data = array_merge([
            'slug' => str_slug($request->get('name'))
        ], $data);

        // todo: these validation rules should be in here and not in the Model and should be validated BEFORE
        // the creation of the resource!
        // The model is strictly for interacting with the database !!!
        // The controller handles the http/ajax requests
        // The view handles how to present the data to the users screen
        // The Repository/Concrete Classes are to handle the application specific logic
        $v = validator($data, [
            'name' => 'required|unique:groups',
            'slug' => 'required|unique:groups',
            'position' => 'required',
            'color' => 'required',
            'icon' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_groups_index')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $group = Group::create($data);

            // todo: we should be creating permissions for this group for every forum
            // this is just due to the way this system is designed and is the solution for this bug
            foreach(Forum::all()->pluck('id') as $forum_id) {
                Permission::create([
                    'forum_id' => $forum_id,
                    'group_id' => $group->id,
                    'show_forum' => 1,
                    'read_topic' => 1,
                    'reply_topic' => 1,
                    'start_topic' => 1,
                ]);
            }

            return redirect()->route('staff_groups_index')
                ->with(Toastr::success('Group Was Created Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Group Edit Form
     *
     * @param $group
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($group, $id)
    {
        $group = Group::findOrFail($id);

        return view('Staff.groups.edit', ['group' => $group]);
    }

    /**
     * Edit Group
     *
     * @param $group
     * @param $id
     */
    public function edit(Request $request, $group, $id)
    {
        $group = Group::findOrFail($id);
        $group->name = $request->get('group_name');
        $group->slug = str_slug($request->get('group_name'));
        $group->position = $request->get('group_postion');
        $group->color = $request->get('group_color');
        $group->icon = $request->get('group_icon');
        $group->effect = $request->get('group_effect');
        $group->is_internal = $request->get('group_internal');
        $group->is_modo = $request->get('group_modo');
        $group->is_admin = $request->get('group_admin');
        $group->is_trusted = $request->get('group_trusted');
        $group->is_immune = $request->get('group_immune');
        $group->is_freeleech = $request->get('group_freeleech');
        $group->can_upload = $request->get('group_upload');
        $group->autogroup = $request->get('autogroup');
        $v = validator($group->toArray(), $group->rules);
        if ($v->fails()) {
            return redirect()->route('staff_groups_index')->with(Toastr::error('Something Went Wrong!', 'Whoops!', ['options']));
        } else {
            $group->save();
            return redirect()->route('staff_groups_index')->with(Toastr::success('Group Was Updated Successfully!', 'Yay!', ['options']));
        }
    }
}
