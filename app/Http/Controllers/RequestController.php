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
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Models\TorrentRequestClaim;
use App\Models\Type;
use App\Models\User;
use App\Notifications\NewRequestBounty;
use App\Notifications\NewRequestClaim;
use App\Notifications\NewRequestFill;
use App\Notifications\NewRequestFillApprove;
use App\Notifications\NewRequestFillReject;
use App\Notifications\NewRequestUnclaim;
use App\Repositories\ChatRepository;
use App\Repositories\RequestFacetedRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

class RequestController extends Controller
{
    /**
     * @var RequestFacetedRepository
     */
    private $faceted;

    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * RequestController Constructor.
     *
     * @param RequestFacetedRepository $faceted
     * @param ChatRepository           $chat
     */
    public function __construct(RequestFacetedRepository $faceted, ChatRepository $chat)
    {
        $this->faceted = $faceted;
        $this->chat = $chat;
    }

    /**
     * Displays Torrent List View.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requests(Request $request)
    {
        $user = $request->user();
        $num_req = TorrentRequest::count();
        $num_fil = TorrentRequest::whereNotNull('filled_by')->count();
        $num_unfil = TorrentRequest::whereNull('filled_by')->count();
        $total_bounty = TorrentRequest::all()->sum('bounty');
        $claimed_bounty = TorrentRequest::whereNotNull('filled_by')->sum('bounty');
        $unclaimed_bounty = TorrentRequest::whereNull('filled_by')->sum('bounty');

        $torrentRequests = TorrentRequest::with(['user', 'category'])->paginate(25);
        $repository = $this->faceted;

        return view('requests.requests', [
            'torrentRequests'  => $torrentRequests,
            'repository'       => $repository,
            'user'             => $user,
            'num_req'          => $num_req,
            'num_fil'          => $num_fil,
            'num_unfil'        => $num_unfil,
            'total_bounty'     => $total_bounty,
            'claimed_bounty'   => $claimed_bounty,
            'unclaimed_bounty' => $unclaimed_bounty,
        ]);
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param \Illuminate\Http\Request $request
     * @param TorrentRequest           $torrentRequest
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function faceted(Request $request, TorrentRequest $torrentRequest)
    {
        $user = $request->user();
        $search = $request->input('search');
        $imdb_id = Str::startsWith($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');
        $imdb = str_replace('tt', '', $imdb_id);
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $igdb = $request->input('igdb');
        $categories = $request->input('categories');
        $types = $request->input('types');
        $myrequests = $request->input('myrequests');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }

        $torrentRequest = $torrentRequest->with(['user', 'category']);

        if ($request->has('search') && $request->input('search') != null) {
            $torrentRequest->where('name', 'like', $search);
        }

        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrentRequest->where('imdb', '=', str_replace('tt', '', $imdb));
        }

        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrentRequest->where('tvdb', '=', $tvdb);
        }

        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrentRequest->where('tmdb', '=', $tmdb);
        }

        if ($request->has('mal') && $request->input('mal') != null) {
            $torrentRequest->where('mal', '=', $mal);
        }

        if ($request->has('igdb') && $request->input('igdb') != null) {
            $torrentRequest->where('igdb', '=', $igdb);
        }

        if ($request->has('categories') && $request->input('categories') != null) {
            $torrentRequest->whereIn('category_id', $categories);
        }

        if ($request->has('types') && $request->input('types') != null) {
            $torrentRequest->whereIn('type', $types);
        }

        if ($request->has('unfilled') && $request->input('unfilled') != null) {
            $torrentRequest->where('filled_hash', '=', null);
        }

        if ($request->has('claimed') && $request->input('claimed') != null) {
            $torrentRequest->where('claimed', '!=', null)->where('filled_hash', '=', null);
        }

        if ($request->has('pending') && $request->input('pending') != null) {
            $torrentRequest->where('filled_hash', '!=', null)->where('approved_by', '=', null);
        }

        if ($request->has('filled') && $request->input('filled') != null) {
            $torrentRequest->where('filled_hash', '!=', null)->where('approved_by', '!=', null);
        }

        if ($request->has('myrequests') && $request->input('myrequests') != null) {
            $torrentRequest->where('user_id', '=', $myrequests);
        }

        if ($request->has('myclaims') && $request->input('myclaims') != null) {
            $requestCliams = TorrentRequestClaim::where('username', '=', $user->username)->pluck('request_id');
            $torrentRequest->whereIn('id', $requestCliams);
        }

        if ($request->has('myvoted') && $request->input('myvoted') != null) {
            $requestVotes = TorrentRequestBounty::where('user_id', '=', $user->id)->pluck('requests_id');
            $torrentRequest->whereIn('id', $requestVotes);
        }

        if ($request->has('myfiled') && $request->input('myfiled') != null) {
            $torrentRequest->where('filled_by', '=', $user->id);
        }

        if ($request->has('sorting')) {
            $sorting = $request->input('sorting');
            $order = $request->input('direction');
            $torrentRequest->orderBy($sorting, $order);
        }

        if ($request->has('qty')) {
            $qty = $request->get('qty');
            $torrentRequests = $torrentRequest->paginate($qty);
        } else {
            $torrentRequests = $torrentRequest->paginate(25);
        }

        return view('requests.results', [
            'user'            => $user,
            'torrentRequests' => $torrentRequests,
        ])->render();
    }

    /**
     * Display The Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function request(Request $request, $id)
    {
        // Find the torrent in the database
        $torrentRequest = TorrentRequest::findOrFail($id);
        $user = $request->user();
        $torrentRequestClaim = TorrentRequestClaim::where('request_id', '=', $id)->first();
        $voters = $torrentRequest->requestBounty()->get();
        $comments = $torrentRequest->comments()->latest('created_at')->paginate(6);
        $carbon = Carbon::now()->addDay();

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        $meta = null;
        if ($torrentRequest->category->tv_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $meta = $client->scrape('tv', null, $torrentRequest->tmdb);
            } else {
                $meta = $client->scrape('tv', 'tt'.$torrentRequest->imdb);
            }
        }
        if ($torrentRequest->category->movie_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $meta = $client->scrape('movie', null, $torrentRequest->tmdb);
            } else {
                $meta = $client->scrape('movie', 'tt'.$torrentRequest->imdb);
            }
        }
        if ($torrentRequest->category->game_meta) {
            $meta = Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($torrentRequest->igdb);
        }

        return view('requests.request', [
            'torrentRequest'      => $torrentRequest,
            'voters'              => $voters, 'user' => $user,
            'comments'            => $comments,
            'carbon'              => $carbon,
            'meta'                => $meta,
            'torrentRequestClaim' => $torrentRequestClaim,
        ]);
    }

    /**
     * Torrent Request Add Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $title
     * @param int                      $imdb
     * @param int                      $tmdb
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addRequestForm(Request $request, $title = '', $imdb = 0, $tmdb = 0)
    {
        $user = $request->user();

        return view('requests.add_request', [
            'categories' => Category::all()->sortBy('position'),
            'types'      => Type::all()->sortBy('position'),
            'user'       => $user,
            'title'      => $title,
            'imdb'       => str_replace('tt', '', $imdb),
            'tmdb'       => $tmdb,
        ]);
    }

    /**
     * Store A New Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function addrequest(Request $request)
    {
        $user = $request->user();

        $category = Category::findOrFail($request->input('category_id'));
        $tr = new TorrentRequest();
        $tr->name = $request->input('name');
        $tr->description = $request->input('description');
        $tr->category_id = $category->id;
        $tr->user_id = $user->id;
        $tr->imdb = $request->input('imdb');
        $tr->tvdb = $request->input('tvdb');
        $tr->tmdb = $request->input('tmdb');
        $tr->mal = $request->input('mal');
        $tr->igdb = $request->input('igdb');
        $tr->type = $request->input('type');
        $tr->bounty = $request->input('bounty');
        $tr->votes = 1;
        $tr->anon = $request->input('anon');

        $v = validator($tr->toArray(), [
            'name'        => 'required|max:180',
            'imdb'        => 'required|numeric',
            'tvdb'        => 'required|numeric',
            'tmdb'        => 'required|numeric',
            'mal'         => 'required|numeric',
            'igdb'        => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required',
            'description' => 'required|string',
            'bounty'      => "required|numeric|min:0|max:{$user->seedbonus}",
            'anon'        => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('requests')
                ->withErrors($v->errors())->withInput();
        }
        $tr->save();
        $requestsBounty = new TorrentRequestBounty();
        $requestsBounty->user_id = $user->id;
        $requestsBounty->seedbonus = $request->input('bounty');
        $requestsBounty->requests_id = $tr->id;
        $requestsBounty->anon = $request->input('anon');
        $requestsBounty->save();
        $BonTransactions = new BonTransactions();
        $BonTransactions->itemID = 0;
        $BonTransactions->name = 'request';
        $BonTransactions->cost = $request->input('bounty');
        $BonTransactions->sender = $user->id;
        $BonTransactions->receiver = 0;
        $BonTransactions->comment = "new request - {$request->input('name')}";
        $BonTransactions->save();
        $user->seedbonus -= $request->input('bounty');
        $user->save();
        $tr_url = hrefRequest($tr);
        $profile_url = hrefProfile($user);
        // Auto Shout
        if ($tr->anon == 0) {
            $this->chat->systemMessage(
                "[url={$profile_url}]{$user->username}[/url] has created a new request [url={$tr_url}]{$tr->name}[/url]"
            );
        } else {
            $this->chat->systemMessage(
                "An anonymous user has created a new request [url={$tr_url}]{$tr->name}[/url]"
            );
        }

        return redirect()->route('requests')
            ->withSuccess('Request Added.');
    }

    /**
     * Torrent Request Edit Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRequestForm(Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        return view('requests.edit_request', [
            'categories'     => Category::all()->sortBy('position'),
            'types'          => Type::all()->sortBy('position'),
            'user'           => $user,
            'torrentRequest' => $torrentRequest, ]);
    }

    /**
     * Edit A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function editrequest(Request $request, $id)
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
        $type = $request->input('type');
        $description = $request->input('description');
        $anon = $request->input('anon');

        $torrentRequest->name = $name;
        $torrentRequest->imdb = $imdb;
        $torrentRequest->tvdb = $tvdb;
        $torrentRequest->tmdb = $tmdb;
        $torrentRequest->mal = $mal;
        $torrentRequest->igdb = $igdb;
        $torrentRequest->category_id = $category;
        $torrentRequest->type = $type;
        $torrentRequest->description = $description;
        $torrentRequest->anon = $anon;

        $v = validator($torrentRequest->toArray(), [
            'name'        => 'required|max:180',
            'imdb'        => 'required|numeric',
            'tvdb'        => 'required|numeric',
            'tmdb'        => 'required|numeric',
            'mal'         => 'required|numeric',
            'igdb'        => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required',
            'description' => 'required|string',
            'anon'        => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('requests')
                ->withErrors($v->errors());
        }
        $torrentRequest->save();

        return redirect()->route('requests', ['id' => $torrentRequest->id])
            ->withSuccess('Request Edited Successfully.');
    }

    /**
     * Add Bounty To A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function addBonus(Request $request, $id)
    {
        $user = $request->user();

        $tr = TorrentRequest::with('user')->findOrFail($id);
        $tr->votes += 1;
        $tr->bounty += $request->input('bonus_value');
        $tr->created_at = Carbon::now();

        $v = validator($request->all(), [
            'bonus_value' => "required|numeric|min:100|max:{$user->seedbonus}",
        ]);

        if ($v->fails()) {
            return redirect()->route('request', ['id' => $tr->id])
                ->withErrors($v->errors());
        }
        $tr->save();
        $requestsBounty = new TorrentRequestBounty();
        $requestsBounty->user_id = $user->id;
        $requestsBounty->seedbonus = $request->input('bonus_value');
        $requestsBounty->requests_id = $tr->id;
        $requestsBounty->anon = $request->input('anon');
        $requestsBounty->save();
        $BonTransactions = new BonTransactions();
        $BonTransactions->itemID = 0;
        $BonTransactions->name = 'request';
        $BonTransactions->cost = $request->input('bonus_value');
        $BonTransactions->sender = $user->id;
        $BonTransactions->receiver = 0;
        $BonTransactions->comment = "adding bonus to {$tr->name}";
        $BonTransactions->save();
        $user->seedbonus -= $request->input('bonus_value');
        $user->save();
        $tr_url = hrefRequest($tr);
        $profile_url = hrefProfile($user);
        // Auto Shout
        if ($requestsBounty->anon == 0) {
            $this->chat->systemMessage(
                "[url={$profile_url}]{$user->username}[/url] has added {$request->input('bonus_value')} BON bounty to request [url={$tr_url}]{$tr->name}[/url]"
            );
        } else {
            $this->chat->systemMessage(
                "An anonymous user added {$request->input('bonus_value')} BON bounty to request [url={$tr_url}]{$tr->name}[/url]"
            );
        }
        $sender = $request->input('anon') == 1 ? 'Anonymous' : $user->username;
        $requester = $tr->user;
        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_bounty')) {
            $requester->notify(new NewRequestBounty('torrent', $sender, $request->input('bonus_value'), $tr));
        }

        return redirect()->route('request', ['id' => $request->input('request_id')])
            ->withSuccess('Your bonus has been successfully added.');
    }

    /**
     * Fill A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function fillRequest(Request $request, $id)
    {
        $user = $request->user();

        $torrentRequest = TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = $user->id;
        $torrentRequest->filled_hash = $request->input('info_hash');
        $torrentRequest->filled_when = Carbon::now();
        $torrentRequest->filled_anon = $request->input('filled_anon');

        $v = validator($request->all(), [
            'request_id'  => 'required|exists:requests,id',
            'info_hash'   => 'required|exists:torrents,info_hash',
            'filled_anon' => 'required',
        ]);

        $torrent = Torrent::where('info_hash', '=', $torrentRequest->filled_hash)->first();
        if ($torrent && !$torrent->isApproved()) {
            return redirect()->route('request', ['id' => $request->input('request_id')])
                ->withErrors('The torrent info_hash you are trying to use is valid in our database but is still pending moderation. Please wait for your torrent to be approved and then try again.');
        }

        if ($v->fails()) {
            return redirect()->route('request', ['id' => $request->input('request_id')])
                ->withErrors($v->errors());
        }
        $torrentRequest->save();
        // Send Private Message
        $appurl = config('app.url');
        $sender = $request->input('filled_anon') == 1 ? 'Anonymous' : $user->username;
        $requester = $torrentRequest->user;
        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill')) {
            $requester->notify(new NewRequestFill('torrent', $sender, $torrentRequest));
        }

        return redirect()->route('request', ['id' => $request->input('request_id')])
            ->withSuccess('Your request fill is pending approval by the Requester.');
    }

    /**
     * Approve A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approveRequest(Request $request, $id)
    {
        $user = $request->user();

        $tr = TorrentRequest::findOrFail($id);

        if ($user->id == $tr->user_id || $request->user()->group->is_modo) {
            if ($tr->approved_by != null) {
                return redirect()->route('request', ['id' => $id])
                    ->withErrors('Seems this request was already approved');
            }
            $tr->approved_by = $user->id;
            $tr->approved_when = Carbon::now();
            $tr->save();

            //BON and torrent request hash code below
            $fill_user = User::findOrFail($tr->filled_by);
            $fill_amount = $tr->bounty;

            $BonTransactions = new BonTransactions();
            $BonTransactions->itemID = 0;
            $BonTransactions->name = 'request';
            $BonTransactions->cost = $fill_amount;
            $BonTransactions->sender = 0;
            $BonTransactions->receiver = $fill_user->id;
            $BonTransactions->comment = "{$fill_user->username} has filled {$tr->name} and has been awarded {$fill_amount} BONUS.";
            $BonTransactions->save();

            $fill_user->seedbonus += $fill_amount;
            $fill_user->save();

            // Achievements
            $fill_user->addProgress(new UserFilled25Requests(), 1);
            $fill_user->addProgress(new UserFilled50Requests(), 1);
            $fill_user->addProgress(new UserFilled75Requests(), 1);
            $fill_user->addProgress(new UserFilled100Requests(), 1);

            $tr_url = hrefRequest($tr);
            $profile_url = hrefProfile($fill_user);

            // Auto Shout
            if ($tr->filled_anon == 0) {
                $this->chat->systemMessage(
                    "[url={$profile_url}]{$fill_user->username}[/url] has filled request, [url={$tr_url}]{$tr->name}[/url]"
                );
            } else {
                $this->chat->systemMessage(
                    "An anonymous user has filled request, [url={$tr_url}]{$tr->name}[/url]"
                );
            }

            $requester = $fill_user;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill_approve')) {
                $requester->notify(new NewRequestFillApprove('torrent', $user->username, $tr));
            }

            if ($tr->filled_anon == 0) {
                return redirect()->route('request', ['id' => $id])
                    ->withSuccess("You have approved {$tr->name} and the bounty has been awarded to {$fill_user->username}");
            }

            return redirect()->route('request', ['id' => $id])
                ->withSuccess("You have approved {$tr->name} and the bounty has been awarded to a anonymous user");
        } else {
            return redirect()->route('request', ['id' => $id])
                ->withErrors("You don't have access to approve this request");
        }
    }

    /**
     * Reject A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function rejectRequest(Request $request, $id)
    {
        $user = $request->user();
        $appurl = config('app.url');
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->id == $torrentRequest->user_id) {
            if ($torrentRequest->approved_by != null) {
                return redirect()->route('request', ['id' => $id])
                    ->withErrors('Seems this request was already rejected');
            }

            $requester = User::findOrFail($torrentRequest->filled_by);
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill_reject')) {
                $requester->notify(new NewRequestFillReject('torrent', $user->username, $torrentRequest));
            }

            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->save();

            return redirect()->route('request', ['id' => $id])
                ->withSuccess('This request has been reset.');
        }

        return redirect()->route('request', ['id' => $id])
            ->withSuccess("You don't have access to approve this request");
    }

    /**
     * Delete A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteRequest(Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->group->is_modo || $torrentRequest->user_id == $user->id) {
            $name = $torrentRequest->name;
            $torrentRequest->delete();

            return redirect()->route('requests')
                ->withSuccess("You have deleted {$name}");
        }

        return redirect()->route('request', ['id' => $id])
            ->withErrors("You don't have access to delete this request.");
    }

    /**
     * Claim A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function claimRequest(Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::with('user')->findOrFail($id);

        if ($torrentRequest->claimed == null) {
            $requestClaim = new TorrentRequestClaim();
            $requestClaim->request_id = $id;
            $requestClaim->username = $user->username;
            $requestClaim->anon = $request->input('anon');
            $requestClaim->save();

            $torrentRequest->claimed = 1;
            $torrentRequest->save();

            $sender = $request->input('anon') == 1 ? 'Anonymous' : $user->username;

            $requester = $torrentRequest->user;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_claim')) {
                $requester->notify(new NewRequestClaim('torrent', $sender, $torrentRequest));
            }

            return redirect()->route('request', ['id' => $id])
                ->withSuccess('Request Successfully Claimed');
        }

        return redirect()->route('request', ['id' => $id])
            ->withErrors('Someone else has already claimed this request buddy.');
    }

    /**
     * Uncliam A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unclaimRequest(Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = TorrentRequest::findOrFail($id);
        $claimer = TorrentRequestClaim::where('request_id', '=', $id)->first();

        abort_unless($user->group->is_modo || $user->username == $claimer->username, 403);

        if ($torrentRequest->claimed == 1) {
            $requestClaim = TorrentRequestClaim::where('request_id', '=', $id)->firstOrFail();
            $isAnon = $requestClaim->anon;
            $requestClaim->delete();

            $torrentRequest->claimed = null;
            $torrentRequest->save();

            $sender = $isAnon == 1 ? 'Anonymous' : $user->username;

            $requester = $torrentRequest->user;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_unclaim')) {
                $requester->notify(new NewRequestUnclaim('torrent', $sender, $torrentRequest));
            }

            return redirect()->route('request', ['id' => $id])
                ->withSuccess('Request Successfully Un-Claimed');
        }

        return redirect()->route('request', ['id' => $id])
            ->withErrors('Nothing To Unclaim.');
    }

    /**
     * Resets the filled and approved attributes on a given request.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function resetRequest(Request $request, $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $torrentRequest = TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = null;
        $torrentRequest->filled_when = null;
        $torrentRequest->filled_hash = null;
        $torrentRequest->approved_by = null;
        $torrentRequest->approved_when = null;
        $torrentRequest->save();

        return redirect()->route('request', ['id' => $id])
            ->withSuccess('The request has been reset!');
    }
}
