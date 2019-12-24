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

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

final class PageController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private Redirector $redirector;

    public function __construct(Factory $viewFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Display All Pages.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $pages = Page::all();

        return $this->viewFactory->make('Staff.page.index', ['pages' => $pages]);
    }

    /**
     * Page Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): Factory
    {
        return $this->viewFactory->make('Staff.page.create');
    }

    /**
     * Store A New Page.
     *
     * @param \Illuminate\Http\Request $request
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
            return $this->redirector->route('staff.pages.index')
                ->withErrors($v->errors());
        } else {
            $page->save();

            return $this->redirector->route('staff.pages.index')
                ->withSuccess('Page has been created successfully');
        }
    }

    /**
     * Page Edit Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id): Factory
    {
        $page = Page::findOrFail($id);

        return $this->viewFactory->make('Staff.page.edit', ['page' => $page]);
    }

    /**
     * Edit A Page.
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id
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
            return $this->redirector->route('staff.pages.index')
                ->withErrors($v->errors());
        } else {
            $page->save();

            return $this->redirector->route('staff.pages.index')
                ->withSuccess('Page has been edited successfully');
        }
    }

    /**
     * Delete A Page.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        Page::findOrFail($id)->delete();

        return $this->redirector->route('staff.pages.index')
            ->withSuccess('Page has been deleted successfully');
    }
}
