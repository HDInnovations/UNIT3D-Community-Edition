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

namespace App\Http\Controllers;

use Image;
use Carbon\Carbon;
use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Clients\OmdbClient;

class AlbumController extends Controller
{
    /**
     * @var OmdbClient
     */
    private $client;

    /**
     * AlbumController Constructor.
     *
     * @param OmdbClient $client
     */
    public function __construct(OmdbClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get All Albums.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $albums = Album::withCount('images')->get();

        return view('gallery.index')->with('albums', $albums);
    }

    /**
     * Get A Album.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAlbum($id)
    {
        $album = Album::with('images')->find($id);
        $albums = Album::with('images')->get();

        return view('gallery.album', ['album' => $album, 'albums' => $albums]);
    }

    /**
     * Album Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('gallery.createalbum');
    }

    /**
     * Add A Album.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $imdb = Str::startsWith($request->input('imdb'), 'tt') ? $request->input('imdb') : 'tt'.$request->input('imdb');
        $omdb = $this->client->find(['imdb' => $imdb]);

        if ($omdb === null || $omdb === false) {
            return redirect()->route('create_album_form')
                ->withErrors('Bad IMDB Request!');
        }

        $album = new Album();
        $album->user_id = $request->user()->id;
        $album->name = $omdb['Title'].' ('.$omdb['Year'].')';
        $album->description = $request->input('description');
        $album->imdb = $request->input('imdb');

        $image = $request->file('cover_image');
        $filename = 'album-cover_'.uniqid().'.'.$image->getClientOriginalExtension();
        $path = public_path('/files/img/'.$filename);
        Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
        $album->cover_image = $filename;

        $v = validator($album->toArray(), [
            'user_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
            'imdb'        => 'required',
            'cover_image' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('create_album_form')
                ->withInput()
                ->withErrors($v->errors());
        } else {
            $album->save();

            return redirect()->route('show_album', ['id' => $album->id])
                ->withSuccess('Your album has successfully published!');
        }
    }

    /**
     * Delete A Album.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $album = Album::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $album->user_id && Carbon::now()->lt($album->created_at->addDay()), 403);
        $album->delete();

        return redirect()->route('home')
            ->withSuccess('Album has successfully been deleted');
    }
}
