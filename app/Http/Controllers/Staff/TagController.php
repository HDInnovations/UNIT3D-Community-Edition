<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Get All Tags.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $tags = Tag::all()->sortBy('name');

        return view('Staff.tag.index', ['tags' => $tags]);
    }

    /**
     * Tag Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.tag.add');
    }

    /**
     * Add A Tag.
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->slug = Str::slug($tag->name);

        $v = validator($tag->toArray(), [
            'name' => 'required|unique:tags',
            'slug' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_tag_index')
                ->withErrors($v->errors());
        } else {
            $tag->save();

            return redirect()->route('staff_tag_index')
                ->withSuccess('Tag Successfully Added');
        }
    }

    /**
     * Tag Edit Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($id)
    {
        $tag = Tag::findOrFail($id);

        return view('Staff.tag.edit', ['tag' => $tag]);
    }

    /**
     * Edit A Tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param                            $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->name = $request->input('name');
        $tag->slug = Str::slug($tag->name);

        $v = validator($tag->toArray(), [
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_tag_index')
                ->withErrors($v->errors());
        } else {
            $tag->save();

            return redirect()->route('staff_tag_index')
                ->withSuccess('Tag Successfully Modified');
        }
    }
}
