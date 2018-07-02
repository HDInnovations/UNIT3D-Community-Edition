<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TorrentFacetedRepository;
use App\Torrent;
use App\Graveyard;
use Carbon\Carbon;
use \Toastr;

class GraveyardController extends Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private $faceted;

    public function __construct(TorrentFacetedRepository $faceted)
    {
        $this->faceted = $faceted;
    }

    /**
     * Show The Graveyard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $torrents = Torrent::query()->paginate(25);
        $repository = $this->faceted;
        $deadcount = Torrent::where('seeders', 0)->count();

        return view('graveyard.index', [
            'user' => $user,
            'torrents' => $torrents,
            'repository' => $repository,
            'deadcount' => $deadcount,
        ]);
    }

    /**
     * Uses Input's To Put Together A Search
     *
     * @param \Illuminate\Http\Request $request
     * @param $torrent Torrent
     * @return array
     */
    public function faceted(Request $request, Torrent $torrent)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $imdb = $request->input('imdb');
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $categories = $request->input('categories');
        $types = $request->input('types');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%' . $term . '%';
        }

        $torrent = $torrent->where('seeders', 0);

        if ($request->has('search') && $request->input('search') != null) {
            $torrent->where('name', 'like', $search);
        }

        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrent->where('imdb', $imdb);
        }

        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrent->where('tvdb', $tvdb);
        }

        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrent->where('tmdb', $tmdb);
        }

        if ($request->has('mal') && $request->input('mal') != null) {
            $torrent->where('mal', $mal);
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

        if($request->has('qty')){
            $qty = $request->get('qty');
            $torrents = $torrent->paginate($qty);
        }else{
            $torrents = $torrent->paginate(25);
        }

        return view('graveyard.results', [
            'user' => $user,
            'torrents' => $torrents
        ])->render();
    }

    /**
     * Resurrect A Torrent
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function resurrect(Request $request, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::findOrFail($id);
        $resurrected = Graveyard::where('torrent_id', $torrent->id)->first();

        if ($resurrected) {
            return redirect()->route('graveyard')
                ->with(Toastr::error('Torrent Resurrection Failed! This torrent is already pending a resurrection.', 'Whoops!', ['options']));
        }

        if ($user->id === $torrent->user_id) {
            return redirect()->route('graveyard')
                ->with(Toastr::error('Torrent Resurrection Failed! You cannot resurrect your own uploads.', 'Whoops!', ['options']));
        }

        $resurrection = new Graveyard();
        $resurrection->user_id = $user->id;
        $resurrection->torrent_id = $torrent->id;
        $resurrection->seedtime = $request->input('seedtime');

        $v = validator($resurrection->toArray(), [
            'user_id' => 'required',
            'torrent_id' => 'required',
            'seedtime' => 'required'
        ]);

        if ($v->fails()) {
            return redirect()->route('graveyard')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $resurrection->save();
            return redirect()->route('graveyard')
                ->with(Toastr::success('Torrent Resurrection Complete! You will be rewarded automatically once seedtime requirements are met.', 'Yay!', ['options']));
        }
    }
}
