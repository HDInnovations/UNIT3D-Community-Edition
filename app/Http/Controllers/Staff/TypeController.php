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

use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    /**
     * Get All Types.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $types = Type::all()->sortBy('position');

        return view('Staff.type.index', ['types' => $types]);
    }

    /**
     * Type Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.type.add');
    }

    /**
     * Add A Type.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $type = new Type();
        $type->name = $request->input('name');
        $type->slug = Str::slug($type->name);
        $type->position = $request->input('position');

        $v = validator($type->toArray(), [
            'name'     => 'required',
            'slug'     => 'required',
            'position' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_type_index')
                ->withErrors($v->errors());
        } else {
            $type->save();

            return redirect()->route('staff_type_index')
                ->withSuccess('Type Successfully Added');
        }
    }

    /**
     * Type Edit Form.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $type = Type::findOrFail($id);

        return view('Staff.type.edit', ['type' => $type]);
    }

    /**
     * Edit A Type.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $type = Type::findOrFail($id);
        $type->name = $request->input('name');
        $type->slug = Str::slug($type->name);
        $type->position = $request->input('position');

        $v = validator($type->toArray(), [
            'name'     => 'required',
            'slug'     => 'required',
            'position' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_type_index')
                ->withErrors($v->errors());
        } else {
            $type->save();

            return redirect()->route('staff_type_index')
                ->withSuccess('Type Successfully Modified');
        }
    }

    /**
     * Delete A Type.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($slug, $id)
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return redirect()->route('staff_type_index')
            ->withSuccess('Type Successfully Deleted');
    }
}
