<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Group;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
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
        $groups = Group::orderBy('name', 'ASC')->get();

        return view('Staff.groups.index', ['groups' => $groups]);
    }

    /**
     * Add Group
     *
     *
     */
    public function add()
    {
        if (Request::isMethod('POST')) {
            $group = new Group();
            $group->name = Request::get('group_name');
            $group->color = Request::get('group_color');
            $group->icon = Request::get('group_icon');
            $group->is_modo = 0;
            $group->is_admin = 0;
            $group->is_trusted = 0;
            $group->is_immune = 0;
            $group->save();
            return Redirect::route('Staff.groups.index')->with(Toastr::success('Group Was Created Successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->back()->with(Toastr::warning('Something Went Wrong!', 'Error', ['options']));
        }
        return view('Staff.groups.add');
    }

    /**
     * Edit Group
     *
     *
     */
    public function edit($group, $id)
    {
        $group = Group::findOrFail($id);
        if (Request::isMethod('POST')) {
            $group->name = Request::get('group_name');
            $group->color = Request::get('group_color');
            $group->icon = Request::get('group_icon');
            $group->is_modo = Input::get('group_modo');
            $group->is_admin = Input::get('group_admin');
            $group->is_trusted = Input::get('group_trusted');
            $group->is_immune = Input::get('group_immune');
            $group->save();
            return Redirect::route('Staff.groups.index')->with(Toastr::success('Group Was Updated Successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->back()->with(Toastr::warning('Something Went Wrong!', 'Error', ['options']));
        }
        return view('Staff.groups.edit');
    }
}
