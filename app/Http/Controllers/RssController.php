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

use App\Models\Category;
use App\Models\Group;
use App\Models\Rss;
use App\Models\TagTorrent;
use App\Models\Torrent;
use App\Models\Type;
use App\Models\User;
use App\Repositories\TorrentFacetedRepository;
use Illuminate\Http\Request;

class RssController extends Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private $torrent_faceted;

    /**
     * RssController Constructor.
     *
     * @param TorrentFacetedRepository $torrent_faceted
     */
    public function __construct(TorrentFacetedRepository $torrent_faceted)
    {
        $this->torrent_faceted = $torrent_faceted;
    }

    /**
     * Display a listing of the RSS resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $hash
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $hash = null)
    {
        $user = $request->user();

        $public_rss = Rss::where('is_private', '=', 0)->orderBy('position', 'ASC')->get();
        $private_rss = Rss::where('is_private', '=', 1)->where('user_id', '=', $user->id)->latest()->get();

        return view('rss.index', [
            'hash'        => $hash,
            'public_rss'  => $public_rss,
            'private_rss' => $private_rss,
            'user'        => $user,
        ]);
    }

    /**
     * Show the form for creating a new RSS resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $torrent_repository = $this->torrent_faceted;

        return view('rss.create', [
            'torrent_repository' => $torrent_repository,
            'categories'         => Category::all()->sortBy('position'),
            'types'              => Type::all()->sortBy('position'),
            'user'               => $user,
        ]);
    }

    /**
     * Store a newly created RSS resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $v = validator($request->all(), [
            'name'        => 'required|min:3|max:255',
            'search'      => 'max:255',
            'description' => 'max:255',
            'uploader'    => 'max:255',
            'categories'  => 'sometimes|array|max:999',
            'types'       => 'sometimes|array|max:999',
            'genres'      => 'sometimes|array|max:999',
            'position'    => 'sometimes|integer|max:9999',
        ]);

        $params = $request->only(['name', 'search', 'description', 'uploader', 'imdb', 'tvdb', 'tmdb', 'mal', 'categories',
            'types', 'genres', 'freeleech', 'doubleupload', 'featured', 'stream', 'highspeed', 'sd', 'internal', 'alive', 'dying', 'dead', ]);

        $error = null;
        $success = null;

        if ($v->passes()) {
            $rss = new Rss();
            $rss->name = $request->input('name');
            $rss->user_id = $user->id;
            $expected = $rss->expected_fields;
            $rss->json_torrent = array_merge($expected, $params);
            $rss->is_private = 1;
            $rss->save();
            $success = 'Private RSS Feed Created';
        }
        if (!$success) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return redirect()->route('rss.create')
                ->withErrors($error);
        }

        return redirect()->route('rss.index', ['hash' => 'private'])
            ->withSuccess($success);
    }

    /**
     * Display the specified RSS resource.
     *
     * @param int    $id
     * @param string $rsskey
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, $rsskey)
    {
        $user = User::where('rsskey', '=', (string) $rsskey)->firstOrFail();
        $rss = Rss::where('id', '=', (int) $id)->whereRaw('(user_id = ? OR is_private != ?)', [$user->id, 1])->firstOrFail();

        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $disabled_group = cache()->rememberForever('disabled_group', function () {
            return Group::where('slug', '=', 'disabled')->pluck('id');
        });

        if ($user->group->id == $banned_group[0]) {
            abort(404);
        }
        if ($user->group->id == $disabled_group[0]) {
            abort(404);
        }
        if ($user->active == 0) {
            abort(404);
        }

        $search = $rss->object_torrent->search;
        $description = $rss->object_torrent->description;
        $uploader = $rss->object_torrent->uploader;
        $imdb = $rss->object_torrent->imdb;
        $tvdb = $rss->object_torrent->tvdb;
        $tmdb = $rss->object_torrent->tmdb;
        $mal = $rss->object_torrent->mal;
        $categories = $rss->object_torrent->categories;
        $types = $rss->object_torrent->types;
        $genres = $rss->object_torrent->genres;
        $freeleech = $rss->object_torrent->freeleech;
        $doubleupload = $rss->object_torrent->doubleupload;
        $featured = $rss->object_torrent->featured;
        $stream = $rss->object_torrent->stream;
        $highspeed = $rss->object_torrent->highspeed;
        $sd = $rss->object_torrent->sd;
        $internal = $rss->object_torrent->internal;
        $alive = $rss->object_torrent->alive;
        $dying = $rss->object_torrent->dying;
        $dead = $rss->object_torrent->dead;

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }

        $usernames = explode(' ', $uploader);
        $uploader = '';
        foreach ($usernames as $username) {
            $uploader .= '%'.$username.'%';
        }

        $keywords = explode(' ', $description);
        $description = '';
        foreach ($keywords as $keyword) {
            $description .= '%'.$keyword.'%';
        }

        $torrent = Torrent::with(['user', 'category']);

        if ($rss->object_torrent->search) {
            $torrent->where(function ($query) use ($search) {
                $query->where('name', 'like', $search);
            });
        }

        if ($rss->object_torrent->description) {
            $torrent->where(function ($query) use ($description) {
                $query->where('description', 'like', $description)->orWhere('mediainfo', 'like', $description);
            });
        }

        if ($rss->object_torrent->uploader && $rss->object_torrent->uploader != null) {
            $match = User::where('username', 'like', $uploader)->first();
            if (null === $match) {
                return ['result' => [], 'count' => 0];
            }
            $torrent->where('user_id', '=', $match->id)->where('anon', '=', 0);
        }

        if ($rss->object_torrent->imdb && $rss->object_torrent->imdb != null) {
            $torrent->where('imdb', '=', $imdb);
        }

        if ($rss->object_torrent->tvdb && $rss->object_torrent->tvdb != null) {
            $torrent->where('tvdb', '=', $tvdb);
        }

        if ($rss->object_torrent->tmdb && $rss->object_torrent->tmdb != null) {
            $torrent->where('tmdb', '=', $tmdb);
        }

        if ($rss->object_torrent->mal && $rss->object_torrent->mal != null) {
            $torrent->where('mal', '=', $mal);
        }

        if ($rss->object_torrent->categories && is_array($rss->object_torrent->categories)) {
            $torrent->whereIn('category_id', $categories);
        }

        if ($rss->object_torrent->types && is_array($rss->object_torrent->types)) {
            $torrent->whereIn('type', $types);
        }

        if ($rss->object_torrent->genres && is_array($rss->object_torrent->genres)) {
            $genreID = TagTorrent::select(['torrent_id'])->distinct()->whereIn('tag_name', $genres)->get();
            $torrent->whereIn('id', $genreID)->cursor();
        }

        if ($rss->object_torrent->freeleech && $rss->object_torrent->freeleech != null) {
            $torrent->where('free', '=', $freeleech);
        }

        if ($rss->object_torrent->doubleupload && $rss->object_torrent->doubleupload != null) {
            $torrent->where('doubleup', '=', $doubleupload);
        }

        if ($rss->object_torrent->featured && $rss->object_torrent->featured != null) {
            $torrent->where('featured', '=', $featured);
        }

        if ($rss->object_torrent->stream && $rss->object_torrent->stream != null) {
            $torrent->where('stream', '=', $stream);
        }

        if ($rss->object_torrent->highspeed && $rss->object_torrent->highspeed != null) {
            $torrent->where('highspeed', '=', $highspeed);
        }

        if ($rss->object_torrent->sd && $rss->object_torrent->sd != null) {
            $torrent->where('sd', '=', $sd);
        }

        if ($rss->object_torrent->internal && $rss->object_torrent->internal != null) {
            $torrent->where('internal', '=', $internal);
        }

        if ($rss->object_torrent->alive && $rss->object_torrent->alive != null) {
            $torrent->where('seeders', '>=', $alive);
        }

        if ($rss->object_torrent->dying && $rss->object_torrent->dying != null) {
            $torrent->where('seeders', '=', $dying)->where('times_completed', '>=', 3);
        }

        if ($rss->object_torrent->dead && $rss->object_torrent->dead != null) {
            $torrent->where('seeders', '=', $dead);
        }

        $torrents = $torrent->latest()->take(50)->get();

        return response()->view('rss.show', ['torrents' => $torrents, 'rsskey' => $user->rsskey])->header('Content-Type', 'text/xml');
    }

    /**
     * Show the form for editing the specified RSS resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $rss = Rss::where('is_private', '=', 1)->findOrFail($id);
        $torrent_repository = $this->torrent_faceted;

        return view('rss.edit', [
            'torrent_repository' => $torrent_repository,
            'categories'         => Category::all()->sortBy('position'),
            'types'              => Type::all()->sortBy('position'),
            'user'               => $user,
            'rss'                => $rss,
        ]);
    }

    /**
     * Update the specified RSS resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rss = Rss::where('is_private', '=', 1)->findOrFail($id);

        $v = validator($request->all(), [
            'search'      => 'max:255',
            'description' => 'max:255',
            'uploader'    => 'max:255',
            'categories'  => 'sometimes|array|max:999',
            'types'       => 'sometimes|array|max:999',
            'genres'      => 'sometimes|array|max:999',
            'position'    => 'sometimes|integer|max:9999',
        ]);

        $params = $request->only(['search', 'description', 'uploader', 'imdb', 'tvdb', 'tmdb', 'mal', 'categories',
            'types', 'genres', 'freeleech', 'doubleupload', 'featured', 'stream', 'highspeed', 'sd', 'internal', 'alive', 'dying', 'dead', ]);

        $error = null;
        $success = null;
        $redirect = null;
        if ($v->passes()) {
            $expected = $rss->expected_fields;
            $push = array_merge($expected, $params);
            $rss->json_torrent = array_merge($rss->json_torrent, $push);
            $rss->is_private = 1;
            $rss->save();
            $success = 'Private RSS Feed Updated';
        }
        if (!$success) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return redirect()->route('rss.edit', ['id' => $id])
                ->withErrors($error);
        }

        return redirect()->route('rss.index', ['hash' => 'private'])
            ->withSuccess($success);
    }

    /**
     * Remove the specified RSS resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rss = Rss::where('is_private', '=', 1)->findOrFail($id);
        $rss->delete();

        return redirect()->route('rss.index', ['hash' => 'private'])
            ->withSuccess('RSS Feed Deleted!');
    }
}
