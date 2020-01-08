<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display All Pages.
     *
     * @return Factory|View
     */
    public function index()
    {
        $pages = Page::all();

        return view('Staff.page.index', ['pages' => $pages]);
    }

    /**
     * Page Add Form.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('Staff.page.create');
    }

    /**
     * Store A New Page.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $page = new Page();
        $page->name = $request->input('name');
        $page->slug = Str::slug($page->name);
        $page->content = $request->input('content');

        $v = validator($page->toArray(), [
            'name'    => 'required',
            'slug'    => 'required',
            'content' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.pages.index')
                ->withErrors($v->errors());
        }
        $page->save();
        return redirect()->route('staff.pages.index')
            ->withSuccess('Page has been created successfully');
    }

    /**
     * Page Edit Form.
     *
     * @param $id
     *
     * @return Factory|View
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('Staff.page.edit', ['page' => $page]);
    }

    /**
     * Edit A Page.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->name = $request->input('name');
        $page->slug = Str::slug($page->name);
        $page->content = $request->input('content');

        $v = validator($page->toArray(), [
            'name'    => 'required',
            'slug'    => 'required',
            'content' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.pages.index')
                ->withErrors($v->errors());
        }
        $page->save();
        return redirect()->route('staff.pages.index')
            ->withSuccess('Page has been edited successfully');
    }

    /**
     * Delete A Page.
     *
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Page::findOrFail($id)->delete();

        return redirect()->route('staff.pages.index')
            ->withSuccess('Page has been deleted successfully');
    }
}
