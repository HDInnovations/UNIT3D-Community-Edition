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
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display All Tags.
     *
     * @return Factory|View
     */
    public function index()
    {
        $tags = Tag::all()->sortBy('name');

        return view('Staff.tag.index', ['tags' => $tags]);
    }

    /**
     * Tag Add Form.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('Staff.tag.create');
    }

    /**
     * Store A New Tag.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->slug = Str::slug($tag->name);

        $v = validator($tag->toArray(), [
            'name' => 'required|unique:tags',
            'slug' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.tags.index')
                ->withErrors($v->errors());
        }
        $tag->save();
        return redirect()->route('staff.tags.index')
            ->withSuccess('Tag Successfully Added');
    }

    /**
     * Tag Edit Form.
     *
     * @param $id
     *
     * @return Factory|View
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);

        return view('Staff.tag.edit', ['tag' => $tag]);
    }

    /**
     * Edit A Tag.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->name = $request->input('name');
        $tag->slug = Str::slug($tag->name);

        $v = validator($tag->toArray(), [
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.tags.index')
                ->withErrors($v->errors());
        }
        $tag->save();
        return redirect()->route('staff.tags.index')
            ->withSuccess('Tag Successfully Modified');
    }
}
