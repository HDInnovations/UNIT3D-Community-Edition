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
use App\Page;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
    public function add()
    {
        if (Request::getMethod() == 'POST') {
            $page = new Page();
            $page->name = Request::get('name');
            $page->slug = str_slug($page->name);
            $page->content = Request::get('content');

            $v = Validator::make($page->toArray(), ['name' => 'required', 'slug' => 'required', 'content' => 'required']);
            if ($v->passes()) {
                $page->save();
                return Redirect::route('staff_page_index');
            } else {
                Session::put('message', 'An error has occurred');
            }
        }
        return view('Staff.page.add');
    }

    /**
     * Edit une page
     *
     *
     */
    public function edit($slug, $id)
    {
        $page = Page::findOrFail($id);
        if (Request::getMethod() == 'POST') {
            $page->name = Request::get('name');
            $page->slug = str_slug($page->name);
            $page->content = Request::get('content');

            $v = Validator::make($page->toArray(), ['name' => 'required', 'slug' => 'required', 'content' => 'required']);
            if ($v->passes()) {
                $page->save();
                return Redirect::route('staff_page_index')->with('message', 'Page edited successfully');
            } else {
                Session::put('message', 'An error has occurred');
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
        return Redirect::route('staff_page_index')->with('message', 'Page successfully deleted');
    }
}
