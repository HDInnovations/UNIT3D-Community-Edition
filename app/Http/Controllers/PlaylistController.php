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

use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;
use App\Models\Movie;
use App\Models\Playlist;
use App\Models\Tv;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PlaylistControllerTest
 */
class PlaylistController extends Controller
{
    /**
     * PlaylistController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display All Playlists.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('playlist.index', [
            'playlists' => Playlist::with([
                'user:id,username,group_id,image',
                'user.group'
            ])
                ->withCount('torrents')
                ->where(function ($query): void {
                    $query->where('is_private', '=', 0)
                        ->orWhere(function ($query): void {
                            $query->where('is_private', '=', 1)->where('user_id', '=', auth()->id());
                        });
                })
                ->oldest('name')
                ->paginate(24),
        ]);
    }

    /**
     * Show Playlist Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('playlist.create');
    }

    /**
     * Store A New Playlist.
     */
    public function store(StorePlaylistRequest $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->hasFile('cover_image')) {
            abort_if(\is_array($request->file('cover_image')), 400);

            abort_unless($request->file('cover_image')->getError() === UPLOAD_ERR_OK, 500);

            $image = $request->file('cover_image');
            $filename = 'playlist-cover_'.uniqid('', true).'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
        }

        $playlist = Playlist::create([
            'user_id'     => $request->user()->id,
            'cover_image' => $filename ?? null
        ] + $request->validated());

        // Announce To Shoutbox
        if (!$playlist->is_private) {
            $this->chatRepository->systemMessage(
                sprintf('User [url=%s/', config('app.url')).$request->user()->username.'.'.$request->user()->id.']'.$request->user()->username.sprintf('[/url] has created a new playlist [url=%s/playlists/', config('app.url')).$playlist->id.']'.$playlist->name.'[/url] check it out now!'
            );
        }

        return to_route('playlists.show', ['playlist' => $playlist])
            ->withSuccess(trans('playlist.published-success'));
    }

    /**
     * Show A Playlist.
     */
    public function show(Request $request, Playlist $playlist): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_if($playlist->is_private && $playlist->user_id !== $request->user()->id, 403, trans('playlist.private-error'));

        $randomTorrent = $playlist->torrents()->inRandomOrder()->first();

        return view('playlist.show', [
            'playlist' => $playlist,
            'meta'     => match(true) {
                $randomTorrent?->category?->tv_meta    => Tv::with('genres', 'networks', 'seasons')->find($randomTorrent->tmdb),
                $randomTorrent?->category?->movie_meta => Movie::with('genres', 'companies', 'collection')->find($randomTorrent->tmdb),
                default                                => null,
            },
            'torrents' => $playlist->torrents()
                ->with(['category', 'resolution', 'type', 'user.group'])
                ->orderBy('name')
                ->paginate(26),
        ]);
    }

    /**
     * Show Playlist Update Form.
     */
    public function edit(Request $request, Playlist $playlist): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->id === $playlist->user_id || $request->user()->group->is_modo, 403);

        return view('playlist.edit', ['playlist' => $playlist]);
    }

    /**
     * Update A Playlist.
     */
    public function update(UpdatePlaylistRequest $request, Playlist $playlist): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->id == $playlist->user_id || $request->user()->group->is_modo, 403);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');

            abort_if(\is_array($image), 400);

            abort_unless($image->getError() === UPLOAD_ERR_OK, 500);

            $filename = 'playlist-cover_'.uniqid('', true).'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
        }

        $playlist->update(['cover_image' => $filename ?? null] + $request->validated());

        return to_route('playlists.show', ['playlist' => $playlist])
            ->withSuccess(trans('playlist.update-success'));
    }

    /**
     * Delete A Playlist.
     *
     * @throws Exception
     */
    public function destroy(Request $request, Playlist $playlist): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->id == $playlist->user_id || $request->user()->group->is_modo, 403);

        $playlist->torrents()->detach();
        $playlist->delete();

        return to_route('playlists.index')
            ->withSuccess(trans('playlist.deleted'));
    }
}
