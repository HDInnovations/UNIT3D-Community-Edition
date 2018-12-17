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

use App\Page;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * PageController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get All Pages.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page = Page::all();

        return view('Staff.page.index', ['page' => $page]);
    }

    /**
     * Page Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.page.add');
    }

    /**
     * Add A Page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $page = new Page();
        $page->name = $request->input('name');
        $page->slug = str_slug($page->name);
        $page->content = $request->input('content');

        $v = validator($page->toArray(), [
            'name'    => 'required',
            'slug'    => 'required',
            'content' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_page_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $page->save();

            return redirect()->route('staff_page_index')
                ->with($this->toastr->success('Page has been created successfully', 'Yay!', ['options']));
        }
    }

    /**
     * Page Edit Form.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $page = Page::findOrFail($id);

        return view('Staff.page.edit', ['page' => $page]);
    }

    /**
     * Edit A Page.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $page = Page::findOrFail($id);
        $page->name = $request->input('name');
        $page->slug = str_slug($page->name);
        $page->content = $request->input('content');

        $v = validator($page->toArray(), [
            'name'    => 'required',
            'slug'    => 'required',
            'content' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_page_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $page->save();

            return redirect()->route('staff_page_index')
                ->with($this->toastr->success('Page has been edited successfully', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Page.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($slug, $id)
    {
        Page::findOrFail($id)->delete();

        return redirect()->route('staff_page_index')
            ->with($this->toastr->success('Page has been deleted successfully', 'Yay!', ['options']));
    }
}
