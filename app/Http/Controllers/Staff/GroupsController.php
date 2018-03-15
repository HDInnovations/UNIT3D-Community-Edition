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

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;
use \Toastr;

class GroupsController extends Controller
{

    /**
     * Groups Admin
     *
     *
     * @access public
     * @return view::make Admin.groups.index
     */
    public function index()
    {
        $groups = Group::all()->sortBy('position');

        return view('Staff.groups.index', ['groups' => $groups]);
    }

    /**
     * Add Group
     *
     *
     */
    public function add(Request $request)
    {
        if ($request->isMethod('POST')) {
            $group = new Group();
            $group->name = $request->get('group_name');
            $group->slug = str_slug($request->get('group_name'));
            $group->position = $request->get('group_postion');
            $group->color = $request->get('group_color');
            $group->icon = $request->get('group_icon');
            $group->effect = $request->get('group_effect');
            $group->is_internal = $request->get('group_internal', 0);
            $group->is_modo = $request->get('group_modo', 0);
            $group->is_admin = $request->get('group_admin', 0);
            $group->is_trusted = $request->get('group_trusted', 0);
            $group->is_immune = $request->get('group_immune', 0);
            $group->is_freeleech = $request->get('group_freeleech', 0);
            $group->autogroup = $request->get('autogroup', 0);
            $v = validator($group->toArray(), $group->rules);
            if ($v->fails()) {
                return redirect()->route('staff_groups_index')->with(Toastr::error('Something Went Wrong!', 'Whoops!', ['options']));
            } else {
                $group->save();
                return redirect()->route('staff_groups_index')->with(Toastr::success('Group Was Created Successfully!', 'Yay!', ['options']));
            }
        }
        return view('Staff.groups.add');
    }

    /**
     * Edit Group
     *
     *
     */
    public function edit(Request $request, $group, $id)
    {
        $group = Group::findOrFail($id);
        if ($request->isMethod('POST')) {
            $group->name = $request->get('group_name');
            $group->slug = str_slug($request->get('group_name'));
            $group->position = $request->get('group_postion');
            $group->color = $request->get('group_color');
            $group->icon = $request->get('group_icon');
            $group->effect = $request->get('group_effect');
            $group->is_internal = $request->get('group_internal', 0);
            $group->is_modo = $request->get('group_modo', 0);
            $group->is_admin = $request->get('group_admin', 0);
            $group->is_trusted = $request->get('group_trusted', 0);
            $group->is_immune = $request->get('group_immune', 0);
            $group->is_freeleech = $request->get('group_freeleech', 0);
            $group->autogroup = $request->get('autogroup', 0);
            $v = validator($group->toArray(), $group->rules);
            if ($v->fails()) {
                return redirect()->route('staff_groups_index')->with(Toastr::error('Something Went Wrong!', 'Whoops!', ['options']));
            } else {
                $group->save();
                return redirect()->route('staff_groups_index')->with(Toastr::success('Group Was Updated Successfully!', 'Yay!', ['options']));
            }
        }
        return view('Staff.groups.edit', ['group' => $group]);
    }
}
