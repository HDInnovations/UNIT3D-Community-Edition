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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GraveyardController extends Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private $faceted;

    /**
     * GraveyardController Constructor.
     *
     * @param TorrentFacetedRepository $faceted
     */
    public function __construct(TorrentFacetedRepository $faceted)
    {
        $this->faceted = $faceted;
    }

    /**
     * Show The Graveyard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $current = Carbon::now();
        $user = $request->user();
        $torrents = Torrent::with('category')->where('created_at', '<', $current->copy()->subDays(30)->toDateTimeString())->paginate(25);
        $repository = $this->faceted;
        $deadcount = Torrent::where('seeders', '=', 0)->where('created_at', '<', $current->copy()->subDays(30)->toDateTimeString())->count();

        return view('graveyard.index', [
            'user'       => $user,
            'torrents'   => $torrents,
            'repository' => $repository,
            'deadcount'  => $deadcount,
        ]);
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
    public function faceted(Request $request, Torrent $torrent)
    {
        $current = Carbon::now();
        $user = $request->user();
        $search = $request->input('search');
        $imdb_id = Str::startsWith($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');
        $imdb = str_replace('tt', '', $imdb_id);
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $categories = $request->input('categories');
        $types = $request->input('types');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }

        $torrent = $torrent->with('category')->where('seeders', '=', 0)->where('created_at', '<', $current->copy()->subDays(30)->toDateTimeString());

        if ($request->has('search') && $request->input('search') != null) {
            $torrent->where('name', 'like', $search);
        }

        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrent->where('imdb', '=', str_replace('tt', '', $imdb));
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
            $torrent->whereIn('type', $types);
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

        return view('graveyard.results', [
            'user'     => $user,
            'torrents' => $torrents,
        ])->render();
    }

    /**
     * Resurrect A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::findOrFail($id);
        $resurrected = Graveyard::where('torrent_id', '=', $torrent->id)->first();

        if ($resurrected) {
            return redirect()->route('graveyard.index')
                ->withErrors('Torrent Resurrection Failed! This torrent is already pending a resurrection.');
        }

        if ($user->id === $torrent->user_id) {
            return redirect()->route('graveyard.index')
                ->withErrors('Torrent Resurrection Failed! You cannot resurrect your own uploads.');
        }

        $resurrection = new Graveyard();
        $resurrection->user_id = $user->id;
        $resurrection->torrent_id = $torrent->id;
        $resurrection->seedtime = $request->input('seedtime');

        $v = validator($resurrection->toArray(), [
            'user_id'    => 'required',
            'torrent_id' => 'required',
            'seedtime'   => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('graveyard.index')
                ->withErrors($v->errors());
        }
        $resurrection->save();

        return redirect()->route('graveyard.index')
            ->withSuccess('Torrent Resurrection Complete! You will be rewarded automatically once seedtime requirements are met.');
    }

    /**
     * Cancel A Ressurection.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $resurrection = Graveyard::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $resurrection->user_id, 403);
        $resurrection->delete();

        return redirect()->route('graveyard.index')
            ->withSuccess('Resurrection Successfully Canceled!');
    }
}
