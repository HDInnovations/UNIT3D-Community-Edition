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
 * @author     singularity43
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Rss;
use App\Models\Type;
use App\Repositories\TorrentFacetedRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

final class RssController extends Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private TorrentFacetedRepository $torrent_faceted;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    /**
     * RssController Constructor.
     *
     * @param  TorrentFacetedRepository  $torrent_faceted
     * @param  \Illuminate\Contracts\View\Factory  $viewFactory
     * @param  \Illuminate\Routing\Redirector  $redirector
     */
    public function __construct(TorrentFacetedRepository $torrent_faceted, Factory $viewFactory, Redirector $redirector)
    {
        $this->torrent_faceted = $torrent_faceted;
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Display a listing of the RSS resource.
     *
     * @param  string  $hash
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index(string $hash = null): Factory
    {
        $public_rss = Rss::where('is_private', '=', 0)->orderBy('position', 'ASC')->get();

        return $this->viewFactory->make('Staff.rss.index', [
            'hash' => $hash,
            'public_rss' => $public_rss,
        ]);
    }

    /**
     * Show the form for creating a new RSS resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function create(Request $request): Factory
    {
        $user = $request->user();
        $torrent_repository = $this->torrent_faceted;

        return $this->viewFactory->make('Staff.rss.create', [
            'torrent_repository' => $torrent_repository,
            'categories'     => Category::all()->sortBy('position'),
            'types'          => Type::all()->sortBy('position'),
            'user'           => $user, ]);
    }

    /**
     * Store a newly created RSS resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $user = $request->user();

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
            $rss->json_torrent = [...$expected, ...$params];
            $rss->is_private = 0;
            $rss->staff_id = $user->id;
            $rss->position = (int) $request->input('position');
            $rss->save();
            $success = 'Public RSS Feed Created';
        }
        if ($success === null) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return $this->redirector->route('staff.rss.create')
                ->withErrors($error);
        }

        return $this->redirector->route('staff.rss.index')
            ->withSuccess($success);
    }

    /**
     * Show the form for editing the specified RSS resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory
     */
    public function edit(Request $request, int $id): Factory
    {
        $user = $request->user();
        $rss = Rss::where('is_private', '=', 0)->findOrFail($id);
        $torrent_repository = $this->torrent_faceted;

        return $this->viewFactory->make('Staff.rss.edit', [
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
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, int $id)
    {
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
        if ($success === null) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return $this->redirector->route('staff.rss.edit', ['id' => $id])
                ->withErrors($error);
        }

        return $this->redirector->route('staff.rss.index')
            ->withSuccess($success);
    }

    /**
     * Remove the specified RSS resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): Response
    {
        $rss = Rss::where('is_private', '=', 0)->findOrFail($id);
        $rss->delete();

        return $this->redirector->route('staff.rss.index')
            ->withSuccess('RSS Feed Deleted!');
    }
}
