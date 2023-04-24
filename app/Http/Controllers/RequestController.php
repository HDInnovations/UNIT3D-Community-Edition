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

use App\Achievements\UserFilled100Requests;
use App\Achievements\UserFilled25Requests;
use App\Achievements\UserFilled50Requests;
use App\Achievements\UserFilled75Requests;
use App\Models\BonTransactions;
use App\Models\Category;
use App\Models\Movie;
use App\Models\Resolution;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Models\TorrentRequestClaim;
use App\Models\Tv;
use App\Models\Type;
use App\Models\User;
use App\Notifications\NewRequestFill;
use App\Notifications\NewRequestFillApprove;
use App\Notifications\NewRequestFillReject;
use App\Repositories\ChatRepository;
use App\Services\Tmdb\TMDBScraper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Game;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RequestControllerTest
 */
class RequestController extends Controller
{
    /**
     * RequestController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('requests.index');
    }

    /**
     * Display The Torrent Request.
     */
    public function show(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrentRequest = TorrentRequest::with('category')->findOrFail($id);
        $user = $request->user();
        $torrentRequestClaim = TorrentRequestClaim::where('request_id', '=', $id)->first();
        $voters = $torrentRequest->requestBounty()->get();
        $carbon = Carbon::now()->addDay();

        $meta = null;
        if ($torrentRequest->category->tv_meta && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $meta = Tv::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'networks',
                'seasons'
            ])
                ->where('id', '=', $torrentRequest->tmdb)
                ->first();
        }

        if ($torrentRequest->category->movie_meta && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $meta = Movie::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'companies',
                'collection'
            ])
                ->where('id', '=', $torrentRequest->tmdb)
                ->first();
        }

        if ($torrentRequest->category->game_meta && ($torrentRequest->igdb || $torrentRequest->igdb != 0)) {
            $meta = Game::with([
                'cover'    => ['url', 'image_id'],
                'artworks' => ['url', 'image_id'],
                'genres'   => ['name'],
                'videos'   => ['video_id', 'name'],
                'involved_companies.company',
                'involved_companies.company.logo',
                'platforms', ])
                ->find($torrentRequest->igdb);
        }

        return view('requests.show', [
            'torrentRequest'      => $torrentRequest,
            'voters'              => $voters,
            'user'                => $user,
            'carbon'              => $carbon,
            'meta'                => $meta,
            'torrentRequestClaim' => $torrentRequestClaim,
        ]);
    }

    /**
     * Torrent Request Add Form.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();

        return view('requests.create', [
            'categories'  => Category::all()->sortBy('position'),
            'types'       => Type::all()->sortBy('position'),
            'resolutions' => Resolution::all()->sortBy('position'),
            'user'        => $user,
            'category_id' => $request->category_id,
            'title'       => urldecode($request->title),
            'imdb'        => $request->imdb,
            'tmdb'        => $request->tmdb,
            'mal'         => $request->mal,
            'tvdb'        => $request->tvdb,
            'igdb'        => $request->igdb,
        ]);
    }

    /**
     * Store A New Torrent Request.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $category = Category::findOrFail($request->input('category_id'));
        $torrentRequest = new TorrentRequest();
        $torrentRequest->name = $request->input('name');
        $torrentRequest->description = $request->input('description');
        $torrentRequest->category_id = $category->id;
        $torrentRequest->user_id = $user->id;
        $torrentRequest->imdb = $request->input('imdb');
        $torrentRequest->tvdb = $request->input('tvdb');
        $torrentRequest->tmdb = $request->input('tmdb');
        $torrentRequest->mal = $request->input('mal');
        $torrentRequest->igdb = $request->input('igdb');
        $torrentRequest->type_id = $request->input('type_id');
        $torrentRequest->resolution_id = $request->input('resolution_id');
        $torrentRequest->bounty = $request->input('bounty');
        $torrentRequest->votes = 1;
        $torrentRequest->anon = $request->input('anon');

        $v = validator($torrentRequest->toArray(), [
            'name'          => 'required|max:180',
            'imdb'          => 'required|numeric',
            'tvdb'          => 'required|numeric',
            'tmdb'          => 'required|numeric',
            'mal'           => 'required|numeric',
            'igdb'          => 'required|numeric',
            'category_id'   => 'required|exists:categories,id',
            'type_id'       => 'required|exists:types,id',
            'resolution_id' => 'nullable|exists:resolutions,id',
            'description'   => 'required|string',
            'bounty'        => sprintf('required|numeric|min:0|max:%s', $user->seedbonus),
            'anon'          => 'required',
        ]);

        if ($v->fails()) {
            return to_route('requests.index')
                ->withErrors($v->errors())->withInput();
        }

        $torrentRequest->save();

        $tmdbScraper = new TMDBScraper();
        if ($torrentRequest->category->tv_meta !== 0 && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $tmdbScraper->tv($torrentRequest->tmdb);
        }

        if ($torrentRequest->category->movie_meta !== 0 && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $tmdbScraper->movie($torrentRequest->tmdb);
        }

        $torrentRequestBounty = new TorrentRequestBounty();
        $torrentRequestBounty->user_id = $user->id;
        $torrentRequestBounty->seedbonus = $request->input('bounty');
        $torrentRequestBounty->requests_id = $torrentRequest->id;
        $torrentRequestBounty->anon = $request->input('anon');
        $torrentRequestBounty->save();

        $BonTransactions = new BonTransactions();
        $BonTransactions->itemID = 0;
        $BonTransactions->name = 'request';
        $BonTransactions->cost = $request->input('bounty');
        $BonTransactions->sender = $user->id;
        $BonTransactions->comment = sprintf('new request - %s', $request->input('name'));
        $BonTransactions->save();
        $user->seedbonus -= $request->input('bounty');
        $user->save();
        $trUrl = href_request($torrentRequest);
        $profileUrl = href_profile($user);
        // Auto Shout
        if ($torrentRequest->anon == 0) {
            $this->chatRepository->systemMessage(
                sprintf('[url=%s]%s[/url] has created a new request [url=%s]%s[/url]', $profileUrl, $user->username, $trUrl, $torrentRequest->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                sprintf('An anonymous user has created a new request [url=%s]%s[/url]', $trUrl, $torrentRequest->name)
            );
        }

        return to_route('requests.index')
            ->withSuccess(trans('request.added-request'));
    }

    /**
     * Torrent Request Edit Form.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        return view('requests.edit', [
            'categories'     => Category::all()->sortBy('position'),
            'types'          => Type::all()->sortBy('position'),
            'resolutions'    => Resolution::all()->sortBy('position'),
            'user'           => $user,
            'torrentRequest' => $torrentRequest, ]);
    }

    /**
     * Edit A Torrent Request.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);
        abort_unless($user->group->is_modo || $user->id === $torrentRequest->user_id, 403);

        // Find the right category
        $name = $request->input('name');
        $imdb = $request->input('imdb');
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $igdb = $request->input('igdb');
        $category = $request->input('category_id');
        $type = $request->input('type_id');
        $resolution = $request->input('resolution_id');
        $description = $request->input('description');
        $anon = $request->input('anon');

        $torrentRequest->name = $name;
        $torrentRequest->imdb = $imdb;
        $torrentRequest->tvdb = $tvdb;
        $torrentRequest->tmdb = $tmdb;
        $torrentRequest->mal = $mal;
        $torrentRequest->igdb = $igdb;
        $torrentRequest->category_id = $category;
        $torrentRequest->type_id = $type;
        $torrentRequest->resolution_id = $resolution;
        $torrentRequest->description = $description;
        $torrentRequest->anon = $anon;

        $v = validator($torrentRequest->toArray(), [
            'name'          => 'required|max:180',
            'imdb'          => 'required|numeric',
            'tvdb'          => 'required|numeric',
            'tmdb'          => 'required|numeric',
            'mal'           => 'required|numeric',
            'igdb'          => 'required|numeric',
            'category_id'   => 'required|exists:categories,id',
            'type_id'       => 'required|exists:types,id',
            'resolution_id' => 'nullable|exists:resolutions,id',
            'description'   => 'required|string',
            'anon'          => 'required',
        ]);

        if ($v->fails()) {
            return to_route('requests.index')
                ->withErrors($v->errors());
        }

        $torrentRequest->save();

        $tmdbScraper = new TMDBScraper();
        if ($torrentRequest->category->tv_meta && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $tmdbScraper->tv($torrentRequest->tmdb);
        }

        if ($torrentRequest->category->movie_meta && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $tmdbScraper->movie($torrentRequest->tmdb);
        }

        return to_route('requests.show', ['id' => $torrentRequest->id])
            ->withSuccess(trans('request.edited-request'));
    }

    /**
     * Fill A Torrent Request.
     */
    public function fill(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $torrentRequest = TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = $user->id;
        $torrentRequest->torrent_id = basename($request->input('torrent_id'));
        $torrentRequest->filled_when = Carbon::now();
        $torrentRequest->filled_anon = $request->input('filled_anon');

        $v = validator($request->all(), [
            'request_id'  => 'required|exists:requests,id',
            'torrent_id'  => 'required|exists:torrents,id',
            'filled_anon' => 'required',
        ]);

        if ($v->fails()) {
            return to_route('requests.show', ['id' => $request->input('request_id')])
                ->withErrors($v->errors());
        }

        $torrent = Torrent::withAnyStatus()->where('id', '=', $torrentRequest->torrent_id)->first();
        if ($torrent->isApproved() === false) {
            return to_route('requests.show', ['id' => $request->input('request_id')])
                ->withErrors(trans('request.pending-moderation'));
        }

        $torrentRequest->save();
        // Send Private Message
        $sender = $request->input('filled_anon') == 1 ? 'Anonymous' : $user->username;
        $requester = $torrentRequest->user;
        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill')) {
            $requester->notify(new NewRequestFill('torrent', $sender, $torrentRequest));
        }

        return to_route('requests.show', ['id' => $request->input('request_id')])
            ->withSuccess(trans('request.pending-approval'));
    }

    /**
     * Approve A Torrent Request.
     */
    public function approve(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $tr = TorrentRequest::findOrFail($id);

        if ($user->id == $tr->user_id || $request->user()->group->is_modo) {
            if ($tr->approved_by != null) {
                return to_route('requests.show', ['id' => $id])
                    ->withErrors(trans('request.already-approved'));
            }

            $tr->approved_by = $user->id;
            $tr->approved_when = Carbon::now();
            $tr->save();

            //BON and torrent request hash code below
            $fillUser = User::findOrFail($tr->filled_by);
            $fillAmount = $tr->bounty;

            $BonTransactions = new BonTransactions();
            $BonTransactions->itemID = 0;
            $BonTransactions->name = 'request';
            $BonTransactions->cost = $fillAmount;
            $BonTransactions->receiver = $fillUser->id;
            $BonTransactions->comment = sprintf('%s has filled %s and has been awarded %s BONUS.', $fillUser->username, $tr->name, $fillAmount);
            $BonTransactions->save();

            $fillUser->seedbonus += $fillAmount;
            $fillUser->save();

            // Achievements
            $fillUser->addProgress(new UserFilled25Requests(), 1);
            $fillUser->addProgress(new UserFilled50Requests(), 1);
            $fillUser->addProgress(new UserFilled75Requests(), 1);
            $fillUser->addProgress(new UserFilled100Requests(), 1);

            $trUrl = href_request($tr);
            $profileUrl = href_profile($fillUser);

            // Auto Shout
            if ($tr->filled_anon == 0) {
                $this->chatRepository->systemMessage(
                    sprintf('[url=%s]%s[/url] has filled request, [url=%s]%s[/url]', $profileUrl, $fillUser->username, $trUrl, $tr->name)
                );
            } else {
                $this->chatRepository->systemMessage(
                    sprintf('An anonymous user has filled request, [url=%s]%s[/url]', $trUrl, $tr->name)
                );
            }

            $requester = $fillUser;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill_approve')) {
                $requester->notify(new NewRequestFillApprove('torrent', $user->username, $tr));
            }

            if ($tr->filled_anon == 0) {
                return to_route('requests.show', ['id' => $id])
                    ->withSuccess(sprintf(trans('request.approved-user'), $tr->name, $fillUser->username));
            }

            return to_route('requests.show', ['id' => $id])
                ->withSuccess(sprintf(trans('request.approved-anon'), $tr->name));
        }

        return to_route('requests.show', ['id' => $id])
            ->withErrors(trans('request.access-error'));
    }

    /**
     * Reject A Torrent Request.
     */
    public function reject(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->id == $torrentRequest->user_id) {
            if ($torrentRequest->approved_by != null) {
                return to_route('requests.show', ['id' => $id])
                    ->withErrors(trans('request.already-rejected'));
            }

            $requester = User::findOrFail($torrentRequest->filled_by);
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill_reject')) {
                $requester->notify(new NewRequestFillReject('torrent', $user->username, $torrentRequest));
            }

            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->torrent_id = null;
            $torrentRequest->save();

            return to_route('requests.show', ['id' => $id])
                ->withSuccess(trans('request.request-reset'));
        }

        return to_route('requests.show', ['id' => $id])
            ->withSuccess(trans('request.access-error'));
    }

    /**
     * Delete A Torrent Request.
     *
     * @throws Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->group->is_modo || $torrentRequest->user_id == $user->id) {
            $name = $torrentRequest->name;
            $torrentRequest->delete();

            return to_route('requests.index')
                ->withSuccess(sprintf(trans('request.deleted'), $name));
        }

        return to_route('requests.show', ['id' => $id])
            ->withErrors(trans('request.access-delete-error'));
    }

    /**
     * Resets the filled and approved attributes on a given request.
     */
    public function reset(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $torrentRequest = TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = null;
        $torrentRequest->filled_when = null;
        $torrentRequest->torrent_id = null;
        $torrentRequest->approved_by = null;
        $torrentRequest->approved_when = null;
        $torrentRequest->save();

        return to_route('requests.show', ['id' => $id])
            ->withSuccess(trans('request.request-reset'));
    }
}
