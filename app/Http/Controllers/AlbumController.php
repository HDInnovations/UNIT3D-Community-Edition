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

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * @see \Tests\Feature\Http\Controllers\AlbumControllerTest
 */
class AlbumController extends Controller
{
    /**
     * Display All Albums.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $albums = Album::withCount('images')->get();

        return \view('album.index')->with('albums', $albums);
    }

    /**
     * Show Album Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('album.create');
    }

    /**
     * Store A New Album.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $imdb = Str::startsWith($request->input('imdb'), 'tt') ? $request->input('imdb') : 'tt'.$request->input('imdb');
        $meta = Movie::where('imdb_id', '=', $imdb)->first();

        if (! $meta) {
            return \redirect()->route('albums.create')
                ->withErrors(\trans('gallery.no-meta'));
        }

        $album = new Album();
        $album->user_id = $request->user()->id;
        $album->name = $meta->title.' ('.$meta->release_date.')';
        $album->description = $request->input('description');
        $album->imdb = $request->input('imdb');

        $image = $request->file('cover_image');
        $filename = 'album-cover_'.\uniqid('', true).'.'.$image->getClientOriginalExtension();
        $path = \public_path('/files/img/'.$filename);
        Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
        $album->cover_image = $filename;

        $v = \validator($album->toArray(), [
            'user_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
            'imdb'        => 'required',
            'cover_image' => 'required',
        ]);

        if ($v->fails()) {
            return \redirect()->route('albums.create')
                ->withInput()
                ->withErrors($v->errors());
        }

        $album->save();

        return \redirect()->route('albums.show', ['id' => $album->id])
            ->withSuccess(\trans('gallery.success'));
    }

    /**
     * Show A Album.
     */
    public function show(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $album = Album::with('images')->find($id);
        $albums = Album::with('images')->get();

        return \view('album.show', ['album' => $album, 'albums' => $albums]);
    }

    /**
     * Delete A Album.
     *
     * @throws \Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $album = Album::findOrFail($id);

        \abort_unless($user->group->is_modo || ($user->id === $album->user_id && Carbon::now()->lt($album->created_at->addDay())), 403);
        $album->delete();

        return \redirect()->route('albums.index')
            ->withSuccess(\trans('gallery.deleted'));
    }
}
