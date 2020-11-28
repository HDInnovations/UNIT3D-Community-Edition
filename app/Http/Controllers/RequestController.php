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

use App\Models\TorrentRequest;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RequestControllerTest
 */
class RequestController extends \App\Http\Controllers\Controller
{
    /**
     * RequestController Constructor.
     *
     * @param \App\Repositories\RequestFacetedRepository $requestFacetedRepository
     * @param \App\Repositories\ChatRepository           $chatRepository
     */
    public function __construct(private \App\Repositories\RequestFacetedRepository $requestFacetedRepository, private \App\Repositories\ChatRepository $chatRepository)
    {
    }

    /**
     * Displays Requests List View.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requests(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        $requests = \Illuminate\Support\Facades\DB::table('requests')->selectRaw('count(*) as total')->selectRaw('count(case when filled_by != null then 1 end) as filled')->selectRaw('count(case when filled_by = null then 1 end) as unfilled')->first();
        $bounties = \Illuminate\Support\Facades\DB::table('requests')->selectRaw('coalesce(sum(bounty), 0) as total')->selectRaw('coalesce(sum(case when filled_by != null then 1 end), 0) as claimed')->selectRaw('coalesce(sum(case when filled_by = null then 1 end), 0) as unclaimed')->first();
        $torrentRequests = \App\Models\TorrentRequest::with(['user', 'category', 'type'])->paginate(25);
        $repository = $this->requestFacetedRepository;

        return \view('requests.requests', ['torrentRequests' => $torrentRequests, 'repository' => $repository, 'user' => $user, 'requests' => $requests, 'bounties' => $bounties]);
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
    public function faceted(\Illuminate\Http\Request $request, \App\Models\TorrentRequest $torrentRequest)
    {
        $user = $request->user();
        $search = $request->input('search');
        $imdb_id = \Illuminate\Support\Str::startsWith($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');
        $imdb = \str_replace('tt', '', $imdb_id);
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $igdb = $request->input('igdb');
        $categories = $request->input('categories');
        $types = $request->input('types');
        $resolutions = $request->input('resolutions');
        $myrequests = $request->input('myrequests');
        $terms = \explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }
        $torrentRequest = $torrentRequest->with(['user', 'category', 'type']);
        if ($request->has('search') && $request->input('search') != null) {
            $torrentRequest->where('name', 'like', $search);
        }
        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrentRequest->where('imdb', '=', \str_replace('tt', '', $imdb));
        }
        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrentRequest->orWhere('tvdb', '=', $tvdb);
        }
        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrentRequest->orWhere('tmdb', '=', $tmdb);
        }
        if ($request->has('mal') && $request->input('mal') != null) {
            $torrentRequest->orWhere('mal', '=', $mal);
        }
        if ($request->has('igdb') && $request->input('igdb') != null) {
            $torrentRequest->orWhere('igdb', '=', $igdb);
        }
        if ($request->has('categories') && $request->input('categories') != null) {
            $torrentRequest->whereIn('category_id', $categories);
        }
        if ($request->has('types') && $request->input('types') != null) {
            $torrentRequest->whereIn('type_id', $types);
        }
        if ($request->has('resolutions') && $request->input('resolutions') != null) {
            $torrentRequest->whereIn('resolution_id', $resolutions);
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
            $requestCliams = \App\Models\TorrentRequestClaim::where('username', '=', $user->username)->pluck('request_id');
            $torrentRequest->whereIn('id', $requestCliams);
        }
        if ($request->has('myvoted') && $request->input('myvoted') != null) {
            $requestVotes = \App\Models\TorrentRequestBounty::where('user_id', '=', $user->id)->pluck('requests_id');
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

        return \view('requests.results', ['user' => $user, 'torrentRequests' => $torrentRequests])->render();
    }

    /**
     * Display The Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function request(\Illuminate\Http\Request $request, $id)
    {
        // Find the torrent in the database
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        $user = $request->user();
        $torrentRequestClaim = \App\Models\TorrentRequestClaim::where('request_id', '=', $id)->first();
        $voters = $torrentRequest->requestBounty()->get();
        $comments = $torrentRequest->comments()->latest('created_at')->paginate(6);
        $carbon = \Carbon\Carbon::now()->addDay();
        $meta = null;
        if ($torrentRequest->category->tv_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $meta = \App\Models\Tv::with('genres', 'networks', 'seasons')->where('id', '=', $torrentRequest->tmdb)->first();
            }
        }
        if ($torrentRequest->category->movie_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $meta = \App\Models\Movie::with('genres', 'cast', 'companies', 'collection')->where('id', '=', $torrentRequest->tmdb)->first();
            }
        }
        if ($torrentRequest->category->game_meta) {
            if ($torrentRequest->igdb || $torrentRequest->igdb != 0) {
                $meta = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($torrentRequest->igdb);
            }
        }

        return \view('requests.request', ['torrentRequest' => $torrentRequest, 'voters' => $voters, 'user' => $user, 'comments' => $comments, 'carbon' => $carbon, 'meta' => $meta, 'torrentRequestClaim' => $torrentRequestClaim]);
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
    public function addRequestForm(\Illuminate\Http\Request $request, $title = '', $imdb = 0, $tmdb = 0)
    {
        $user = $request->user();

        return \view('requests.add_request', ['categories' => \App\Models\Category::all()->sortBy('position'), 'types' => \App\Models\Type::all()->sortBy('position'), 'resolutions' => \App\Models\Resolution::all()->sortBy('position'), 'user' => $user, 'title' => $title, 'imdb' => \str_replace('tt', '', $imdb), 'tmdb' => $tmdb]);
    }

    /**
     * Store A New Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addrequest(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        $category = \App\Models\Category::findOrFail($request->input('category_id'));
        $torrentRequest = new \App\Models\TorrentRequest();
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
        $v = \validator($torrentRequest->toArray(), ['name' => 'required|max:180', 'imdb' => 'required|numeric', 'tvdb' => 'required|numeric', 'tmdb' => 'required|numeric', 'mal' => 'required|numeric', 'igdb' => 'required|numeric', 'category_id' => 'required|exists:categories,id', 'type_id' => 'required|exists:types,id', 'resolution_id' => 'nullable|exists:resolutions,id', 'description' => 'required|string', 'bounty' => \sprintf('required|numeric|min:0|max:%s', $user->seedbonus), 'anon' => 'required']);
        if ($v->fails()) {
            return \redirect()->route('requests')->withErrors($v->errors())->withInput();
        }
        $torrentRequest->save();
        $client = new \App\Services\Tmdb\TMDBScraper();
        if ($torrentRequest->category->tv_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $client->tv($torrentRequest->tmdb);
            }
        }
        if ($torrentRequest->category->movie_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $client->movie($torrentRequest->tmdb);
            }
        }
        $torrentRequestBounty = new \App\Models\TorrentRequestBounty();
        $torrentRequestBounty->user_id = $user->id;
        $torrentRequestBounty->seedbonus = $request->input('bounty');
        $torrentRequestBounty->requests_id = $torrentRequest->id;
        $torrentRequestBounty->anon = $request->input('anon');
        $torrentRequestBounty->save();
        $BonTransactions = new \App\Models\BonTransactions();
        $BonTransactions->itemID = 0;
        $BonTransactions->name = 'request';
        $BonTransactions->cost = $request->input('bounty');
        $BonTransactions->sender = $user->id;
        $BonTransactions->receiver = 0;
        $BonTransactions->comment = \sprintf('new request - %s', $request->input('name'));
        $BonTransactions->save();
        $user->seedbonus -= $request->input('bounty');
        $user->save();
        $tr_url = \href_request($torrentRequest);
        $profile_url = \href_profile($user);
        // Auto Shout
        if ($torrentRequest->anon == 0) {
            $this->chatRepository->systemMessage(\sprintf('[url=%s]%s[/url] has created a new request [url=%s]%s[/url]', $profile_url, $user->username, $tr_url, $torrentRequest->name));
        } else {
            $this->chatRepository->systemMessage(\sprintf('An anonymous user has created a new request [url=%s]%s[/url]', $tr_url, $torrentRequest->name));
        }

        return \redirect()->route('requests')->withSuccess('Request Added.');
    }

    /**
     * Torrent Request Edit Form.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRequestForm(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);

        return \view('requests.edit_request', ['categories' => \App\Models\Category::all()->sortBy('position'), 'types' => \App\Models\Type::all()->sortBy('position'), 'resolutions' => \App\Models\Resolution::all()->sortBy('position'), 'user' => $user, 'torrentRequest' => $torrentRequest]);
    }

    /**
     * Edit A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editrequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        \abort_unless($user->group->is_modo || $user->id === $torrentRequest->user_id, 403);
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
        $v = \validator($torrentRequest->toArray(), ['name' => 'required|max:180', 'imdb' => 'required|numeric', 'tvdb' => 'required|numeric', 'tmdb' => 'required|numeric', 'mal' => 'required|numeric', 'igdb' => 'required|numeric', 'category_id' => 'required|exists:categories,id', 'type_id' => 'required|exists:types,id', 'resolution_id' => 'nullable|exists:resolutions,id', 'description' => 'required|string', 'anon' => 'required']);
        if ($v->fails()) {
            return \redirect()->route('requests')->withErrors($v->errors());
        }
        $torrentRequest->save();
        $client = new \App\Services\Tmdb\TMDBScraper();
        if ($torrentRequest->category->tv_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $client->tv($torrentRequest->tmdb);
            }
        }
        if ($torrentRequest->category->movie_meta) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $client->movie($torrentRequest->tmdb);
            }
        }

        return \redirect()->route('requests', ['id' => $torrentRequest->id])->withSuccess('Request Edited Successfully.');
    }

    /**
     * Add Bounty To A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addBonus(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $tr = \App\Models\TorrentRequest::with('user')->findOrFail($id);
        $tr->votes++;
        $tr->bounty += $request->input('bonus_value');
        $tr->created_at = \Carbon\Carbon::now();
        $v = \validator($request->all(), ['bonus_value' => \sprintf('required|numeric|min:100|max:%s', $user->seedbonus)]);
        if ($v->fails()) {
            return \redirect()->route('request', ['id' => $tr->id])->withErrors($v->errors());
        }
        $tr->save();
        $torrentRequestBounty = new \App\Models\TorrentRequestBounty();
        $torrentRequestBounty->user_id = $user->id;
        $torrentRequestBounty->seedbonus = $request->input('bonus_value');
        $torrentRequestBounty->requests_id = $tr->id;
        $torrentRequestBounty->anon = $request->input('anon');
        $torrentRequestBounty->save();
        $BonTransactions = new \App\Models\BonTransactions();
        $BonTransactions->itemID = 0;
        $BonTransactions->name = 'request';
        $BonTransactions->cost = $request->input('bonus_value');
        $BonTransactions->sender = $user->id;
        $BonTransactions->receiver = 0;
        $BonTransactions->comment = \sprintf('adding bonus to %s', $tr->name);
        $BonTransactions->save();
        $user->seedbonus -= $request->input('bonus_value');
        $user->save();
        $tr_url = \href_request($tr);
        $profile_url = \href_profile($user);
        // Auto Shout
        if ($torrentRequestBounty->anon == 0) {
            $this->chatRepository->systemMessage(\sprintf('[url=%s]%s[/url] has added %s BON bounty to request [url=%s]%s[/url]', $profile_url, $user->username, $request->input('bonus_value'), $tr_url, $tr->name));
        } else {
            $this->chatRepository->systemMessage(\sprintf('An anonymous user added %s BON bounty to request [url=%s]%s[/url]', $request->input('bonus_value'), $tr_url, $tr->name));
        }
        $sender = $request->input('anon') == 1 ? 'Anonymous' : $user->username;
        $requester = $tr->user;
        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_bounty')) {
            $requester->notify(new \App\Notifications\NewRequestBounty('torrent', $sender, $request->input('bonus_value'), $tr));
        }

        return \redirect()->route('request', ['id' => $request->input('request_id')])->withSuccess('Your bonus has been successfully added.');
    }

    /**
     * Fill A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fillRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = $user->id;
        $torrentRequest->filled_hash = $request->input('info_hash');
        $torrentRequest->filled_when = \Carbon\Carbon::now();
        $torrentRequest->filled_anon = $request->input('filled_anon');
        $v = \validator($request->all(), ['request_id' => 'required|exists:requests,id', 'info_hash' => 'required|exists:torrents,info_hash', 'filled_anon' => 'required']);
        $torrent = \App\Models\Torrent::withAnyStatus()->where('info_hash', '=', $torrentRequest->filled_hash)->first();
        if ($torrent->isApproved() === false) {
            return \redirect()->route('request', ['id' => $request->input('request_id')])->withErrors('The torrent info_hash you are trying to use is valid in our database but is still pending moderation. Please wait for your torrent to be approved and then try again.');
        }
        if ($v->fails()) {
            return \redirect()->route('request', ['id' => $request->input('request_id')])->withErrors($v->errors());
        }
        $torrentRequest->save();
        // Send Private Message
        $appurl = \config('app.url');
        $sender = $request->input('filled_anon') == 1 ? 'Anonymous' : $user->username;
        $requester = $torrentRequest->user;
        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill')) {
            $requester->notify(new \App\Notifications\NewRequestFill('torrent', $sender, $torrentRequest));
        }

        return \redirect()->route('request', ['id' => $request->input('request_id')])->withSuccess('Your request fill is pending approval by the Requester.');
    }

    /**
     * Approve A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $tr = \App\Models\TorrentRequest::findOrFail($id);
        if ($user->id == $tr->user_id || $request->user()->group->is_modo) {
            if ($tr->approved_by != null) {
                return \redirect()->route('request', ['id' => $id])->withErrors('Seems this request was already approved');
            }
            $tr->approved_by = $user->id;
            $tr->approved_when = \Carbon\Carbon::now();
            $tr->save();
            //BON and torrent request hash code below
            $fill_user = \App\Models\User::findOrFail($tr->filled_by);
            $fill_amount = $tr->bounty;
            $BonTransactions = new \App\Models\BonTransactions();
            $BonTransactions->itemID = 0;
            $BonTransactions->name = 'request';
            $BonTransactions->cost = $fill_amount;
            $BonTransactions->sender = 0;
            $BonTransactions->receiver = $fill_user->id;
            $BonTransactions->comment = \sprintf('%s has filled %s and has been awarded %s BONUS.', $fill_user->username, $tr->name, $fill_amount);
            $BonTransactions->save();
            $fill_user->seedbonus += $fill_amount;
            $fill_user->save();
            // Achievements
            $fill_user->addProgress(new \App\Achievements\UserFilled25Requests(), 1);
            $fill_user->addProgress(new \App\Achievements\UserFilled50Requests(), 1);
            $fill_user->addProgress(new \App\Achievements\UserFilled75Requests(), 1);
            $fill_user->addProgress(new \App\Achievements\UserFilled100Requests(), 1);
            $tr_url = \href_request($tr);
            $profile_url = \href_profile($fill_user);
            // Auto Shout
            if ($tr->filled_anon == 0) {
                $this->chatRepository->systemMessage(\sprintf('[url=%s]%s[/url] has filled request, [url=%s]%s[/url]', $profile_url, $fill_user->username, $tr_url, $tr->name));
            } else {
                $this->chatRepository->systemMessage(\sprintf('An anonymous user has filled request, [url=%s]%s[/url]', $tr_url, $tr->name));
            }
            $requester = $fill_user;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill_approve')) {
                $requester->notify(new \App\Notifications\NewRequestFillApprove('torrent', $user->username, $tr));
            }
            if ($tr->filled_anon == 0) {
                return \redirect()->route('request', ['id' => $id])->withSuccess(\sprintf('You have approved %s and the bounty has been awarded to %s', $tr->name, $fill_user->username));
            }

            return \redirect()->route('request', ['id' => $id])->withSuccess(\sprintf('You have approved %s and the bounty has been awarded to a anonymous user', $tr->name));
        }

        return \redirect()->route('request', ['id' => $id])->withErrors("You don't have access to approve this request");
    }

    /**
     * Reject A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $appurl = \config('app.url');
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        if ($user->id == $torrentRequest->user_id) {
            if ($torrentRequest->approved_by != null) {
                return \redirect()->route('request', ['id' => $id])->withErrors('Seems this request was already rejected');
            }
            $requester = \App\Models\User::findOrFail($torrentRequest->filled_by);
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_fill_reject')) {
                $requester->notify(new \App\Notifications\NewRequestFillReject('torrent', $user->username, $torrentRequest));
            }
            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->save();

            return \redirect()->route('request', ['id' => $id])->withSuccess('This request has been reset.');
        }

        return \redirect()->route('request', ['id' => $id])->withSuccess("You don't have access to approve this request");
    }

    /**
     * Delete A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        if ($user->group->is_modo || $torrentRequest->user_id == $user->id) {
            $name = $torrentRequest->name;
            $torrentRequest->delete();

            return \redirect()->route('requests')->withSuccess(\sprintf('You have deleted %s', $name));
        }

        return \redirect()->route('request', ['id' => $id])->withErrors("You don't have access to delete this request.");
    }

    /**
     * Claim A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function claimRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = \App\Models\TorrentRequest::with('user')->findOrFail($id);
        if ($torrentRequest->claimed == null) {
            $torrentRequestClaim = new \App\Models\TorrentRequestClaim();
            $torrentRequestClaim->request_id = $id;
            $torrentRequestClaim->username = $user->username;
            $torrentRequestClaim->anon = $request->input('anon');
            $torrentRequestClaim->save();
            $torrentRequest->claimed = 1;
            $torrentRequest->save();
            $sender = $request->input('anon') == 1 ? 'Anonymous' : $user->username;
            $requester = $torrentRequest->user;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_claim')) {
                $requester->notify(new \App\Notifications\NewRequestClaim('torrent', $sender, $torrentRequest));
            }

            return \redirect()->route('request', ['id' => $id])->withSuccess('Request Successfully Claimed');
        }

        return \redirect()->route('request', ['id' => $id])->withErrors('Someone else has already claimed this request buddy.');
    }

    /**
     * Uncliam A Torrent Request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unclaimRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        $claimer = \App\Models\TorrentRequestClaim::where('request_id', '=', $id)->first();
        \abort_unless($user->group->is_modo || $user->username == $claimer->username, 403);
        if ($torrentRequest->claimed == 1) {
            $requestClaim = \App\Models\TorrentRequestClaim::where('request_id', '=', $id)->firstOrFail();
            $isAnon = $requestClaim->anon;
            $requestClaim->delete();
            $torrentRequest->claimed = null;
            $torrentRequest->save();
            $sender = $isAnon == 1 ? 'Anonymous' : $user->username;
            $requester = $torrentRequest->user;
            if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_unclaim')) {
                $requester->notify(new \App\Notifications\NewRequestUnclaim('torrent', $sender, $torrentRequest));
            }

            return \redirect()->route('request', ['id' => $id])->withSuccess('Request Successfully Un-Claimed');
        }

        return \redirect()->route('request', ['id' => $id])->withErrors('Nothing To Unclaim.');
    }

    /**
     * Resets the filled and approved attributes on a given request.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Models\TorrentRequest $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        \abort_unless($user->group->is_modo, 403);
        $torrentRequest = \App\Models\TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = null;
        $torrentRequest->filled_when = null;
        $torrentRequest->filled_hash = null;
        $torrentRequest->approved_by = null;
        $torrentRequest->approved_when = null;
        $torrentRequest->save();

        return \redirect()->route('request', ['id' => $id])->withSuccess('The request has been reset!');
    }
}
