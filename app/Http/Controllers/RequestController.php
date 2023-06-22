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
                ->find($torrentRequest->tmdb);
        }

        if ($torrentRequest->category->movie_meta && ($torrentRequest->tmdb || $torrentRequest->tmdb != 0)) {
            $meta = Movie::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'companies',
                'collection'
            ])
                ->find($torrentRequest->tmdb);
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
        return view('requests.create', [
            'categories'  => Category::orderBy('position')->get(),
            'types'       => Type::orderBy('position')->get(),
            'resolutions' => Resolution::orderBy('position')->get(),
            'user'        => $request->user(),
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

        $request->validate([
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

        $category = Category::findOrFail($request->category_id);

        $torrentRequest = TorrentRequest::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'category_id'   => $category->id,
            'user_id'       => $user->id,
            'imdb'          => $request->imdb,
            'tvdb'          => $request->tvdb,
            'tmdb'          => $request->tmdb,
            'mal'           => $request->mal,
            'igdb'          => $request->igdb,
            'type_id'       => $request->type_id,
            'resolution_id' => $request->resolution_id,
            'bounty'        => $request->bounty,
            'votes'         => 1,
            'anon'          => $request->anon,
        ]);

        $tmdbScraper = new TMDBScraper();

        if ($torrentRequest->category->tv_meta !== 0 && $torrentRequest->tmdb != 0) {
            $tmdbScraper->tv($torrentRequest->tmdb);
        }

        if ($torrentRequest->category->movie_meta !== 0 && $torrentRequest->tmdb != 0) {
            $tmdbScraper->movie($torrentRequest->tmdb);
        }

        TorrentRequestBounty::create([
            'user_id'     => $user->id,
            'seedbonus'   => $request->bounty,
            'requests_id' => $torrentRequest->id,
            'anon'        => $request->anon,
        ]);

        BonTransactions::create([
            'itemID'  => 0,
            'name'    => 'request',
            'cost'    => $request->bounty,
            'sender'  => $user->id,
            'comment' => sprintf('new request - %s', $request->name),
        ]);

        $user->decrement('seedbonus', $request->bounty);

        $requestUrl = href_request($torrentRequest);
        $userUrl = href_profile($user);

        // Auto Shout
        if ($torrentRequest->anon == 0) {
            $this->chatRepository->systemMessage(
                sprintf('[url=%s]%s[/url] has created a new request [url=%s]%s[/url]', $userUrl, $user->username, $requestUrl, $torrentRequest->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                sprintf('An anonymous user has created a new request [url=%s]%s[/url]', $requestUrl, $torrentRequest->name)
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
        return view('requests.edit', [
            'categories'     => Category::orderBy('position')->get(),
            'types'          => Type::orderBy('position')->get(),
            'resolutions'    => Resolution::orderBy('position')->get(),
            'user'           => $request->user(),
            'torrentRequest' => TorrentRequest::findOrFail($id),
        ]);
    }

    /**
     * Edit A Torrent Request.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $torrentRequest->user_id, 403);

        $request->validate([
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

        $torrentRequest->update([
            'name'          => $request->name,
            'imdb'          => $request->imdb,
            'tvdb'          => $request->tvdb,
            'tmdb'          => $request->tmdb,
            'mal'           => $request->mal,
            'igdb'          => $request->igdb,
            'category_id'   => $request->category_id,
            'type_id'       => $request->type_id,
            'resolution_id' => $request->resolution_id,
            'description'   => $request->description,
            'anon'          => $request->anon,
        ]);

        $tmdbScraper = new TMDBScraper();

        if ($torrentRequest->category->tv_meta && $torrentRequest->tmdb != 0) {
            $tmdbScraper->tv($torrentRequest->tmdb);
        }

        if ($torrentRequest->category->movie_meta && $torrentRequest->tmdb != 0) {
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

        $request->validate([
            'request_id'  => 'required|exists:requests,id',
            'torrent_id'  => 'required',
            'filled_anon' => 'required',
        ]);

        $torrent_id = basename($request->torrent_id);
        $torrent = Torrent::withAnyStatus()->find($torrent_id);

        if ($torrent->isApproved() === false) {
            return to_route('requests.show', ['id' => $request->input('request_id')])
                ->withErrors(trans('request.pending-moderation'));
        }

        $torrentRequest = TorrentRequest::findOrFail($id);

        $torrentRequest->update([
            'filled_by'   => $user->id,
            'torrent_id'  => $torrent_id,
            'filled_when' => Carbon::now(),
            'filled_anon' => $request->filled_anon,
        ]);

        // Send Private Message
        $senderUsername = $request->filled_anon ? 'Anonymous' : $user->username;
        $requester = $torrentRequest->user;

        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill')) {
            $requester->notify(new NewRequestFill('torrent', $senderUsername, $torrentRequest));
        }

        return to_route('requests.show', ['id' => $request->input('request_id')])
            ->withSuccess(trans('request.pending-approval'));
    }

    /**
     * Approve A Torrent Request.
     */
    public function approve(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $approver = $request->user();
        $tr = TorrentRequest::findOrFail($id);

        abort_unless($approver->id == $tr->user_id || $request->user()->group->is_modo, 403);

        if ($tr->approved_by !== null) {
            return to_route('requests.show', ['id' => $id])
                ->withErrors(trans('request.already-approved'));
        }

        $tr->update([
            'approved_by'   => $approver->id,
            'approved_when' => Carbon::now(),
        ]);

        $filler = User::findOrFail($tr->filled_by);
        $fillAmount = $tr->bounty;

        BonTransactions::create([
            'itemID'   => 0,
            'name'     => 'request',
            'cost'     => $fillAmount,
            'receiver' => $filler->id,
            'comment'  => sprintf('%s has filled %s and has been awarded %s BONUS.', $filler->username, $tr->name, $fillAmount),
        ]);

        $filler->increment('seedbonus', $fillAmount);

        // Achievements
        if (! $tr->filled_anon) {
            $filler->addProgress(new UserFilled25Requests(), 1);
            $filler->addProgress(new UserFilled50Requests(), 1);
            $filler->addProgress(new UserFilled75Requests(), 1);
            $filler->addProgress(new UserFilled100Requests(), 1);
        }

        $requestUrl = href_request($tr);
        $userUrl = href_profile($filler);

        // Auto Shout
        if ($tr->filled_anon) {
            $this->chatRepository->systemMessage(
                sprintf('An anonymous user has filled request, [url=%s]%s[/url]', $requestUrl, $tr->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                sprintf('[url=%s]%s[/url] has filled request, [url=%s]%s[/url]', $userUrl, $filler->username, $requestUrl, $tr->name)
            );
        }

        if ($filler->acceptsNotification($approver, $filler, 'request', 'show_request_fill_approve')) {
            $filler->notify(new NewRequestFillApprove('torrent', $approver->username, $tr));
        }

        if ($tr->filled_anon) {
            return to_route('requests.show', ['id' => $id])
                ->withSuccess(sprintf(trans('request.approved-anon'), $tr->name));
        }

        return to_route('requests.show', ['id' => $id])
            ->withSuccess(sprintf(trans('request.approved-user'), $tr->name, $filler->username));
    }

    /**
     * Reject A Torrent Request.
     */
    public function reject(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        abort_unless($user->id === $torrentRequest->user_id || $user->group->is_modo, 403);

        if ($torrentRequest->approved_by !== null) {
            return to_route('requests.show', ['id' => $id])
                ->withErrors(trans('request.already-rejected'));
        }

        $filler = User::findOrFail($torrentRequest->filled_by);

        if ($filler->acceptsNotification($request->user(), $filler, 'request', 'show_request_fill_reject')) {
            $filler->notify(new NewRequestFillReject('torrent', $user->username, $torrentRequest));
        }

        $torrentRequest->update([
            'filled_by'   => null,
            'filled_when' => null,
            'torrent_id'  => null,
        ]);

        return to_route('requests.show', ['id' => $id])
            ->withSuccess(trans('request.request-reset'));
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

        abort_unless($user->group->is_modo || $torrentRequest->user_id === $user->id, 403);

        $torrentRequest->delete();

        return to_route('requests.index')
            ->withSuccess(sprintf(trans('request.deleted'), $torrentRequest->name));
    }

    /**
     * Resets the filled and approved attributes on a given request.
     */
    public function reset(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        TorrentRequest::whereKey($id)->update([
            'filled_by'     => null,
            'filled_when'   => null,
            'torrent_id'    => null,
            'approved_by'   => null,
            'approved_when' => null,
        ]);

        return to_route('requests.show', ['id' => $id])
            ->withSuccess(trans('request.request-reset'));
    }
}
