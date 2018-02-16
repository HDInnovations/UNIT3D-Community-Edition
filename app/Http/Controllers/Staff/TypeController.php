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

use App\Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use \Toastr;

class TypeController extends Controller
{

    /**
     * Get Types
     *
     *
     */
    public function index()
    {
        $types = Type::all()->sortBy('position');

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
            $type->position = Request::get('position');
            $v = Validator::make($type->toArray(), $type->rules);
            if ($v->fails()) {
                Toastr::error('Something Went Wrong!', 'Error', ['options']);
            } else {
                $type->save();
                return redirect()->route('staff_type_index')->with(Toastr::info('Type Sucessfully Added', 'Yay!', ['options']));
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
            $type->position = Request::get('position');
            $v = Validator::make($type->toArray(), $type->rules);
            if ($v->fails()) {
                Toastr::error('Something Went Wrong!', 'Error', ['options']);
            } else {
                $type->save();
                return redirect()->route('staff_type_index')->with(Toastr::info('Type Sucessfully Modified', 'Yay!', ['options']));
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
        return redirect()->route('staff_type_index')->with(Toastr::warning('Type Sucessfully Deleted', 'Yay!', ['options']));
    }
}
