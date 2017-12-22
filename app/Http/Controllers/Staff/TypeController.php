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

use App\Type;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{

    /**
     * Get Types
     *
     *
     */
    public function index()
    {
        $types = Type::all();

        return view('Staff.type.index', ['types' => $types]);
    }

    /**
     * Add A Type
     *
     *
     */
    public function add()
    {
        if (Request::isMethod('post')) {
            $type = new Type();
            $type->name = Request::get('name');
            $type->slug = str_slug($type->name);
            $v = Validator::make($type->toArray(), $type->rules);
            if ($v->fails()) {
                Session::put('message', 'An error has occurred');
            } else {
                $type->save();
                return Redirect::route('staff_type_index')->with('message', 'Type sucessfully added');
            }
        }
        return view('Staff.type.add');
    }

    /**
     * Edit A Type
     *
     *
     */
    public function edit($slug, $id)
    {
        $type = Type::findOrFail($id);
        if (Request::isMethod('post')) {
            $type->name = Request::get('name');
            $type->slug = str_slug($type->name);
            $v = Validator::make($type->toArray(), $type->rules);
            if ($v->fails()) {
                Session::put('message', 'An error has occurred');
            } else {
                $type->save();
                return Redirect::route('staff_type_index')->with('message', 'Type sucessfully modified');
            }
        }

        return view('Staff.type.edit', ['type' => $type]);
    }

    /**
     * Delete A Type
     *
     *
     */
    public function delete($slug, $id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        return Redirect::route('staff_type_index')->with('message', 'Type successfully deleted');
    }
}
