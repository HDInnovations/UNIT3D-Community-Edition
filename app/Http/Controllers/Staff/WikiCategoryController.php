<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\WikiCategory;
use Illuminate\Http\Request;

class WikiCategoryController extends Controller
{
    /**
     * Display All Categories.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $categories = WikiCategory::all()->sortBy('position');

        return view('Staff.wiki_category.index', ['categories' => $categories]);
    }

    /**
     * Show Form For Creating A New Category.
     */
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Staff.wiki_category.create');
    }

    /**
     * Store A Category.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $category = new WikiCategory();
        $category->name = $request->input('name');
        $category->position = $request->input('position');
        $category->icon = $request->input('icon');

        $v = validator($category->toArray(), [
            'name'     => 'required',
            'position' => 'required',
            'icon'     => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.wiki_categories.index')
                ->withErrors($v->errors());
        }

        $category->save();

        return redirect()->route('staff.wiki_categories.index')
            ->withSuccess('Wiki Category Successfully Added');
    }

    /**
     * Category Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $category = WikiCategory::findOrFail($id);

        return view('Staff.wiki_category.edit', ['category' => $category]);
    }

    /**
     * Update A Category.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $category = WikiCategory::findOrFail($id);
        $category->name = $request->input('name');
        $category->position = $request->input('position');
        $category->icon = $request->input('icon');

        $v = validator($category->toArray(), [
            'name'     => 'required',
            'position' => 'required',
            'icon'     => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.wiki_categories.index')
                ->withErrors($v->errors());
        }

        $category->save();

        return redirect()->route('staff.wiki_categories.index')
            ->withSuccess('Wiki Category Successfully Modified');
    }

    /**
     * Destroy A Category.
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $category = WikiCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('staff.wiki_categories.index')
            ->withSuccess('Wiki Category Successfully Deleted');
    }
}
