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

use App\Category;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * CategoryController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get The Categories.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all()->sortBy('position');

        return view('Staff.category.index', ['categories' => $categories]);
    }

    /**
     * Category Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.category.add');
    }

    /**
     * Add A Category.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = str_slug($category->name);
        $category->position = $request->input('position');
        $category->icon = $request->input('icon');
        $category->movie_meta = $request->input('movie_meta');
        $category->tv_meta = $request->input('tv_meta');
        $category->game_meta = $request->input('game_meta');
        $category->music_meta = $request->input('music_meta');
        $category->no_meta = $request->input('no_meta');

        $v = validator($category->toArray(), [
            'name'          => 'required',
            'slug'          => 'required',
            'position'      => 'required',
            'icon'          => 'required',
            'movie_meta'    => 'required',
            'tv_meta'       => 'required',
            'game_meta'     => 'required',
            'music_meta'    => 'required',
            'no_meta'       => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_category_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $category->save();

            return redirect()->route('staff_category_index')
                ->with($this->toastr->success('Category Successfully Added', 'Yay!', ['options']));
        }
    }

    /**
     * Category Edit Form.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $category = Category::findOrFail($id);

        return view('Staff.category.edit', ['category' => $category]);
    }

    /**
     * Edit A Category.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->slug = str_slug($category->name);
        $category->position = $request->input('position');
        $category->icon = $request->input('icon');
        $category->movie_meta = $request->input('movie_meta');
        $category->tv_meta = $request->input('tv_meta');
        $category->game_meta = $request->input('game_meta');
        $category->music_meta = $request->input('music_meta');
        $category->no_meta = $request->input('no_meta');

        $v = validator($category->toArray(), [
            'name'          => 'required',
            'slug'          => 'required',
            'position'      => 'required',
            'icon'          => 'required',
            'movie_meta'    => 'required',
            'tv_meta'       => 'required',
            'game_meta'     => 'required',
            'music_meta'    => 'required',
            'no_meta'       => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_category_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $category->save();

            return redirect()->route('staff_category_index')
                ->with($this->toastr->success('Category Successfully Modified', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Category.
     *
     * @param $id
     * @param $slug
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($slug, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('staff_category_index')
            ->with($this->toastr->success('Category Successfully Deleted', 'Yay!', ['options']));
    }
}
