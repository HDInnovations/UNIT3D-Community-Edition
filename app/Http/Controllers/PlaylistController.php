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

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use ErrorException;
use App\Models\Playlist;
use App\Models\PlaylistTorrent;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use App\Services\MovieScrapper;
use Illuminate\Http\Request;
use Image;

final class PlaylistController extends Controller
{
    private \App\Repositories\ChatRepository $chat;

    /**
     * PlaylistController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Display All Playlists.
     *
     * @return Factory|View
     */
    public function index()
    {
        $playlists = Playlist::with('user')->withCount('torrents')->where('is_private', '=', 0)->orderBy('name', 'ASC')->paginate(24);

        return view('playlist.index', ['playlists' => $playlists]);
    }

    /**
     * Show Playlist Create Form.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('playlist.create');
    }

    /**
     * Store A New Playlist.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $playlist = new Playlist();
        $playlist->user_id = $user->id;
        $playlist->name = $request->input('name');
        $playlist->description = $request->input('description');
        $playlist->cover_image = null;

        if ($request->hasFile('cover_image') && $request->file('cover_image')->getError() === 0) {
            $image = $request->file('cover_image');
            $filename = 'playlist-cover_'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
            $playlist->cover_image = $filename;
        }

        $playlist->position = $request->input('position');
        $playlist->is_private = $request->input('is_private');

        $v = validator($playlist->toArray(), [
            'user_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
            'is_private'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('playlists.create')
                ->withInput()
                ->withErrors($v->errors());
        }
        $playlist->save();
        // Announce To Shoutbox
        $appurl = config('app.url');
        if ($playlist->is_private != 1) {
            $this->chat->systemMessage(
                sprintf('User [url=%s/', $appurl).$user->username.'.'.$user->id.']'.$user->username.sprintf('[/url] has created a new playlist [url=%s/playlists/', $appurl).$playlist->id.']'.$playlist->name.'[/url] check it out now! :slight_smile:'
            );
        }
        return redirect()->route('playlists.show', ['id' => $playlist->id])
            ->withSuccess('Your Playlist Was Created Successfully!');
    }

    /**
     * Show A Playlist.
     *
     * @param \App\Playlist $id
     *
     * @throws ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return Factory|View
     */
    public function show($id)
    {
        $playlist = Playlist::findOrFail($id);
        $meta = null;

        $random = PlaylistTorrent::where('playlist_id', '=', $playlist->id)->inRandomOrder()->first();
        if (isset($random)) {
            $torrent = Torrent::where('id', '=', $random->torrent_id)->firstOrFail();
        }
        if (isset($random) && isset($torrent)) {
            $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
            if ($torrent->category_id == 2) {
                if ($torrent->tmdb || $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
            } elseif ($torrent->tmdb || $torrent->tmdb != 0) {
                $meta = $client->scrape('movie', null, $torrent->tmdb);
            } else {
                $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
            }
        }

        $torrents = PlaylistTorrent::with(['torrent'])->where('playlist_id', '=', $playlist->id)->get()->sortBy('name');

        return view('playlist.show', ['playlist' => $playlist, 'meta' => $meta, 'torrents' => $torrents]);
    }

    /**
     * Show Playlist Update Form.
     *
     * @param \App\Playlist $id
     *
     * @return Factory|View
     */
    public function edit($id)
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($id);

        abort_unless($user->id == $playlist->user_id || $user->group->is_modo, 403);

        return view('playlist.edit', ['playlist' => $playlist]);
    }

    /**
     * Update A Playlist.
     *
     * @param Request $request
     * @param \App\Playlist            $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($id);

        abort_unless($user->id == $playlist->user_id || $user->group->is_modo, 403);

        $playlist->name = $request->input('name');
        $playlist->description = $request->input('description');
        $playlist->cover_image = null;

        if ($request->hasFile('cover_image') && $request->file('cover_image')->getError() === 0) {
            $image = $request->file('cover_image');
            $filename = 'playlist-cover_'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
            $playlist->cover_image = $filename;
        }

        $playlist->position = $request->input('position');
        $playlist->is_private = $request->input('is_private');

        $v = validator($playlist->toArray(), [
            'name'        => 'required',
            'description' => 'required',
            'is_private'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('playlists.edit', ['id' => $playlist->id])
                ->withInput()
                ->withErrors($v->errors());
        }
        $playlist->save();
        return redirect()->route('playlists.show', ['id' => $playlist->id])
            ->withSuccess('Your Playlist Has Successfully Been Updated!');
    }

    /**
     * Delete A Playlist.
     *
     * @param \App\Playlist $id
     *
     * @return RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($id);

        abort_unless($user->id == $playlist->user_id || $user->group->is_modo, 403);

        $playlist->delete();

        return redirect()->route('playlists.index')
            ->withSuccess('Playlist Deleted!');
    }
}
