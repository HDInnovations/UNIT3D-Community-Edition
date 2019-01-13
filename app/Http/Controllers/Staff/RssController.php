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
 * @author     singularity43
 */

namespace App\Http\Controllers\Staff;

use App\Rss;
use App\Type;
use App\Category;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TorrentFacetedRepository;

class RssController extends Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private $torrent_faceted;

    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * RssController Constructor.
     *
     * @param TorrentFacetedRepository $torrent_faceted
     * @param Toastr $toastr
     */
    public function __construct(TorrentFacetedRepository $torrent_faceted, Toastr $toastr)
    {
        $this->torrent_faceted = $torrent_faceted;
        $this->toastr = $toastr;
    }

    /**
     * Display a listing of the RSS resource.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function index($hash = null)
    {
        $public_rss = Rss::where('is_private', '=', 0)->orderBy('position', 'ASC')->get();

        return view('Staff.rss.index', [
            'hash' => $hash,
            'public_rss' => $public_rss,
        ]);
    }

    /**
     * Show the form for creating a new RSS resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $torrent_repository = $this->torrent_faceted;

        return view('Staff.rss.create', [
            'torrent_repository' => $torrent_repository,
            'categories'     => Category::all()->sortBy('position'),
            'types'          => Type::all()->sortBy('position'),
            'user'           => $user, ]);
    }

    /**
     * Store a newly created RSS resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $v = validator($request->all(), [
            'name' => 'required|min:3|max:255',
            'search' => 'max:255',
            'description' => 'max:255',
            'uploader' => 'max:255',
            'categories' => 'sometimes|array|max:999',
            'types' => 'sometimes|array|max:999',
            'genres' => 'sometimes|array|max:999',
            'position' => 'sometimes|integer|max:9999',
        ]);

        $params = $request->only(['type', 'name', 'position', 'search', 'description', 'uploader', 'imdb', 'tvdb', 'tmdb', 'mal', 'categories',
            'types', 'genres', 'freeleech', 'doubleupload', 'featured', 'stream', 'highspeed', 'sd', 'internal', 'alive', 'dying', 'dead', ]);

        $error = null;
        $success = null;

        if ($v->passes()) {
            $rss = new Rss();
            $rss->name = $request->input('name');
            $rss->user_id = $user->id;
            $expected = $rss->expected_fields;
            $rss->json_torrent = array_merge($expected, $params);
            $rss->is_private = 0;
            $rss->staff_id = $user->id;
            $rss->position = (int) $request->input('position');
            $rss->save();
            $success = 'Public RSS Feed Created';
        }
        if (! $success) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return redirect()->route('Staff.rss.create')
                ->with($this->toastr->error($error, 'Whoops!', ['options']));
        }

        return redirect()->route('Staff.rss.index')
            ->with($this->toastr->success($success, 'Yay!', ['options']));
    }

    /**
     * Show the form for editing the specified RSS resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        $rss = Rss::where('is_private', '=', 0)->findOrFail($id);
        $torrent_repository = $this->torrent_faceted;

        return view('Staff.rss.edit', [
            'torrent_repository' => $torrent_repository,
            'categories'     => Category::all()->sortBy('position'),
            'types'          => Type::all()->sortBy('position'),
            'user'           => $user,
            'rss'            => $rss,
        ]);
    }

    /**
     * Update the specified RSS resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $rss = Rss::where('is_private', '=', 0)->findOrFail($id);

        $v = validator($request->all(), [
            'name' => 'required|min:3|max:255',
            'search' => 'max:255',
            'description' => 'max:255',
            'uploader' => 'max:255',
            'categories' => 'sometimes|array|max:999',
            'types' => 'sometimes|array|max:999',
            'genres' => 'sometimes|array|max:999',
            'position' => 'sometimes|integer|max:9999',
        ]);

        $params = $request->only(['type', 'position', 'search', 'description', 'uploader', 'imdb', 'tvdb', 'tmdb', 'mal', 'categories',
            'types', 'genres', 'freeleech', 'doubleupload', 'featured', 'stream', 'highspeed', 'sd', 'internal', 'alive', 'dying', 'dead', ]);

        $error = null;
        $success = null;
        $redirect = null;

        if ($v->passes()) {
            $expected = $rss->expected_fields;
            $push = array_merge($expected, $params);
            $rss->json_torrent = array_merge($rss->json_torrent, $push);
            $rss->is_private = 0;
            $rss->name = $request->input('name');
            $rss->position = (int) $request->input('position');
            $rss->save();
            $success = 'Public RSS Feed Updated';
        }
        if (! $success) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return redirect()->route('Staff.rss.edit', ['id' => $id])
                ->with($this->toastr->error($error, 'Whoops!', ['options']));
        }

        return redirect()->route('Staff.rss.index')
            ->with($this->toastr->success($success, 'Yay!', ['options']));
    }

    /**
     * Remove the specified RSS resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rss = Rss::where('is_private', '=', 0)->findOrFail($id);
        $rss->delete();

        return redirect()->route('Staff.rss.index')
            ->with($this->toastr->success('RSS Feed Deleted!', 'Yay!', ['options']));
    }
}
