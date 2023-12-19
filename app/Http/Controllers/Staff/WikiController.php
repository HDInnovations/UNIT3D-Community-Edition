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

use App\Models\WikiCategory;
use App\Http\Controllers\Controller;
use App\Models\Wiki;
use Illuminate\Http\Request;

class WikiController extends Controller
{
    /**
     * Display All Pages.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Staff.wiki.index', ['wikis' => Wiki::with(['category'])->get()]);
    }

    /**
     * Page Add Form.
     */
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Staff.wiki.create', ['categories' => WikiCategory::all()->sortBy('position')]);
    }

    /**
     * Store A New Page.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $wiki = new Wiki();
        $wiki->name = $request->input('name');
        $wiki->category_id = $request->input('category_id');
        $wiki->content = $request->input('content');

        $v = validator($wiki->toArray(), [
            'name'        => 'required',
            'category_id' => 'required',
            'content'     => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.wikis.index')
                ->withErrors($v->errors());
        }

        $wiki->save();

        return redirect()->route('staff.wikis.index')
            ->withSuccess('Wiki has been created successfully');
    }

    /**
     * Page Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Staff.wiki.edit', [
            'wiki'       => Wiki::findOrFail($id),
            'categories' => WikiCategory::all()->sortBy('position')
        ]);
    }

    /**
     * Edit A Page.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $wiki = Wiki::findOrFail($id);
        $wiki->name = $request->input('name');
        $wiki->category_id = $request->input('category_id');
        $wiki->content = $request->input('content');

        $v = validator($wiki->toArray(), [
            'name'        => 'required',
            'category_id' => 'required',
            'content'     => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.wikis.index')
                ->withErrors($v->errors());
        }

        $wiki->save();

        return redirect()->route('staff.wikis.index')
            ->withSuccess('Wiki has been edited successfully');
    }

    /**
     * Delete A Page.
     */
    public function destroy(int $id): Illuminate\Http\RedirectResponse
    {
        Wiki::findOrFail($id)->delete();

        return redirect()->route('staff.wikis.index')
            ->withSuccess('Wiki has been deleted successfully');
    }
}
