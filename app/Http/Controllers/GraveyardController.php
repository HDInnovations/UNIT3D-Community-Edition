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

use App\Models\Graveyard;
use App\Models\Torrent;
use App\Repositories\TorrentFacetedRepository;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\GraveyardControllerTest
 */
class GraveyardController extends \App\Http\Controllers\Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private $torrentFacetedRepository;

    /**
     * GraveyardController Constructor.
     *
     * @param \App\Repositories\TorrentFacetedRepository $torrentFacetedRepository
     */
    public function __construct(private \App\Repositories\TorrentFacetedRepository $torrentFacetedRepository)
    {
        $this->torrentFacetedRepository = $torrentFacetedRepository;
    }

    /**
     * Show The Graveyard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $current = \Carbon\Carbon::now();
        $user = $request->user();
        $torrents = \App\Models\Torrent::with('category', 'type')->where('created_at', '<', $current->copy()->subDays(30)->toDateTimeString())->paginate(25);
        $repository = $this->torrentFacetedRepository;
        $deadcount = \App\Models\Torrent::where('seeders', '=', 0)->where('created_at', '<', $current->copy()->subDays(30)->toDateTimeString())->count();

        return \view('graveyard.index', ['user' => $user, 'torrents' => $torrents, 'repository' => $repository, 'deadcount' => $deadcount]);
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param \Illuminate\Http\Request $request
     * @param Torrent                  $torrent
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function faceted(\Illuminate\Http\Request $request, \App\Models\Torrent $torrent)
    {
        $current = \Carbon\Carbon::now();
        $user = $request->user();
        $search = $request->input('search');
        $imdb_id = \Illuminate\Support\Str::startsWith($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');
        $imdb = \str_replace('tt', '', $imdb_id);
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $categories = $request->input('categories');
        $types = $request->input('types');
        $terms = \explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }
        $torrent = $torrent->with('category', 'type')->where('seeders', '=', 0)->where('created_at', '<', $current->copy()->subDays(30)->toDateTimeString());
        if ($request->has('search') && $request->input('search') != null) {
            $torrent->where('name', 'like', $search);
        }
        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrent->where('imdb', '=', \str_replace('tt', '', $imdb));
        }
        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrent->where('tvdb', '=', $tvdb);
        }
        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrent->where('tmdb', '=', $tmdb);
        }
        if ($request->has('mal') && $request->input('mal') != null) {
            $torrent->where('mal', '=', $mal);
        }
        if ($request->has('categories') && $request->input('categories') != null) {
            $torrent->whereIn('category_id', $categories);
        }
        if ($request->has('types') && $request->input('types') != null) {
            $torrent->whereIn('type_id', $types);
        }
        if ($request->has('sorting') && $request->input('sorting') != null) {
            $sorting = $request->input('sorting');
            $order = $request->input('direction');
            $torrent->orderBy($sorting, $order);
        }
        if ($request->has('qty')) {
            $qty = $request->get('qty');
            $torrents = $torrent->paginate($qty);
        } else {
            $torrents = $torrent->paginate(25);
        }

        return \view('graveyard.results', ['user' => $user, 'torrents' => $torrents])->render();
    }

    /**
     * Resurrect A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrent = \App\Models\Torrent::findOrFail($id);
        $resurrected = \App\Models\Graveyard::where('torrent_id', '=', $torrent->id)->first();
        if ($resurrected) {
            return \redirect()->route('graveyard.index')->withErrors('Torrent Resurrection Failed! This torrent is already pending a resurrection.');
        }
        if ($user->id === $torrent->user_id) {
            return \redirect()->route('graveyard.index')->withErrors('Torrent Resurrection Failed! You cannot resurrect your own uploads.');
        }
        $graveyard = new \App\Models\Graveyard();
        $graveyard->user_id = $user->id;
        $graveyard->torrent_id = $torrent->id;
        $graveyard->seedtime = $request->input('seedtime');
        $v = \validator($graveyard->toArray(), ['user_id' => 'required', 'torrent_id' => 'required', 'seedtime' => 'required']);
        if ($v->fails()) {
            return \redirect()->route('graveyard.index')->withErrors($v->errors());
        }
        $graveyard->save();

        return \redirect()->route('graveyard.index')->withSuccess('Torrent Resurrection Complete! You will be rewarded automatically once seedtime requirements are met.');
    }

    /**
     * Cancel A Ressurection.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $resurrection = \App\Models\Graveyard::findOrFail($id);
        \abort_unless($user->group->is_modo || $user->id === $resurrection->user_id, 403);
        $resurrection->delete();

        return \redirect()->route('graveyard.index')->withSuccess('Resurrection Successfully Canceled!');
    }
}
