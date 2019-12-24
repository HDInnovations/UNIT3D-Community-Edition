<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

final class TagController extends Controller
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
     * Display All Tags.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $tags = Tag::all()->sortBy('name');

        return $this->viewFactory->make('Staff.tag.index', ['tags' => $tags]);
    }

    /**
     * Tag Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): Factory
    {
        return $this->viewFactory->make('Staff.tag.create');
    }

    /**
     * Store A New Tag.
     *
     * @param \Illuminate\Http\Request $request
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
            return $this->redirector->route('staff.tags.index')
                ->withErrors($v->errors());
        } else {
            $tag->save();

            return $this->redirector->route('staff.tags.index')
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
    public function edit($id): Factory
    {
        $tag = Tag::findOrFail($id);

        return $this->viewFactory->make('Staff.tag.edit', ['tag' => $tag]);
    }

    /**
     * Edit A Tag.
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id
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
            return $this->redirector->route('staff.tags.index')
                ->withErrors($v->errors());
        } else {
            $tag->save();

            return $this->redirector->route('staff.tags.index')
                ->withSuccess('Tag Successfully Modified');
        }
    }
}
