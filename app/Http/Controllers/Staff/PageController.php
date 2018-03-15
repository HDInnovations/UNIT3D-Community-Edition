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
use App\Page;
use \Toastr;

class PageController extends Controller
{

    /**
     * Affiche les pages d'index
     *
     *
     */
    public function index()
    {
        $pages = Page::all();

        return view('Staff.page.index', ['pages' => $pages]);
    }

    /**
     * Ajoute une page
     *
     *
     */
    public function add(Request $request)
    {
        if ($request->isMethod('POST')) {
            $page = new Page();
            $page->name = $request->input('name');
            $page->slug = str_slug($page->name);
            $page->content = $request->input('content');

            $v = validator($page->toArray(), ['name' => 'required', 'slug' => 'required', 'content' => 'required']);
            if ($v->passes()) {
                $page->save();
                return redirect()->route('staff_page_index')->with(Toastr::success('Page has been created successfully', 'Yay!', ['options']));
            } else {
                return redirect()->back()->with(Toastr::error('Page failed to save', 'Whoops!', ['options']));
            }
        }
        return view('Staff.page.add');
    }

    /**
     * Edit une page
     *
     *
     */
    public function edit(Request $request, $slug, $id)
    {
        $page = Page::findOrFail($id);
        if ($request->isMethod('POST')) {
            $page->name = $request->input('name');
            $page->slug = str_slug($page->name);
            $page->content = $request->input('content');

            $v = validator($page->toArray(), ['name' => 'required', 'slug' => 'required', 'content' => 'required']);
            if ($v->passes()) {
                $page->save();
                return redirect()->route('staff_page_index')->with(Toastr::success('Page has been edited successfully', 'Yay!', ['options']));
            } else {
                return redirect()->back()->with(Toastr::error('Page failed to save', 'Whoops!', ['options']));
            }
        }
        return view('Staff.page.edit', ['page' => $page]);
    }

    /**
     * Delete une page
     *
     *
     */
    public function delete($slug, $id)
    {
        Page::findOrFail($id)->delete();
        return redirect()->route('staff_page_index')->with(Toastr::success('Page has been deleted successfully', 'Yay!', ['options']));
    }
}
