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

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Toastr;

class CategoryController extends Controller
{

    /**
     * Get the categories
     *
     *
     */
    public function index()
    {
        $categories = Category::all()->sortBy('position');

        return view('Staff.category.index', ['categories' => $categories]);
    }

    /**
     * Add a category
     *
     *
     */
    public function add(Request $request)
    {
        if ($request->isMethod('POST')) {
            $category = new Category();
            $category->name = $request->input('name');
            $category->slug = str_slug($category->name);
            $category->position = $request->input('position');
            $category->icon = $request->input('icon');
            $category->meta = $request->input('meta');
            $v = validator($category->toArray(), $category->rules);
            if ($v->fails()) {
                return redirect()->back()->with(Toastr::error('Something Went Wrong!', 'Error', ['options']));
            } else {
                $category->save();
                return redirect()->route('staff_category_index')->with(Toastr::success('Category Sucessfully Added', 'Yay!', ['options']));
            }
        }
        return view('Staff.category.add');
    }

    /**
     * Edit a category
     *
     *
     */
    public function edit(Request $request, $slug, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->isMethod('POST')) {
            $category->name = $request->input('name');
            $category->slug = str_slug($category->name);
            $category->icon = $request->input('icon');
            $category->meta = $request->input('meta');
            $v = validator($category->toArray(), $category->rules);
            if ($v->fails()) {
                return redirect()->back()->with(Toastr::error('Something Went Wrong!', 'Error', ['options']));
            } else {
                $category->save();
                return redirect()->route('staff_category_index')->with(Toastr::success('Category Sucessfully Modified', 'Yay!', ['options']));
            }
        }

        return view('Staff.category.edit', ['category' => $category]);
    }

    /**
     * Delete a category
     *
     *
     */
    public function delete($slug, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('staff_category_index')->with(Toastr::success('Category Sucessfully Deleted', 'Yay!', ['options']));
    }
}
