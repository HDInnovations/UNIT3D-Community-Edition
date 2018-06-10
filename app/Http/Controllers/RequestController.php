<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Http\Controllers;

use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use App\BonTransactions;
use App\Category;
use App\Type;
use App\TorrentRequest;
use App\TorrentRequestBounty;
use App\TorrentRequestClaim;
use App\Torrent;
use App\User;
use App\PrivateMessage;
use App\Helpers\RequestViewHelper;
use App\Repositories\RequestFacetedRepository;
use App\Achievements\UserFilled25Requests;
use App\Achievements\UserFilled50Requests;
use App\Achievements\UserFilled75Requests;
use App\Achievements\UserFilled100Requests;
use Carbon\Carbon;
use Decoda\Decoda;
use \Toastr;

class RequestController extends Controller
{
    /**
     * @var RequestFacetedRepository
     */
    private $repository;

    /**
     * @var ChatRepository
     */
    private $chat;

    public function __construct(RequestFacetedRepository $repository, ChatRepository $chat)
    {
        $this->repository = $repository;
        $this->chat = $chat;
    }

    /**
     * Displays Torrent List View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requests()
    {
        $user = auth()->user();
        $num_req = TorrentRequest::count();
        $num_fil = TorrentRequest::whereNotNull('filled_by')->count();
        $num_unfil = TorrentRequest::whereNull('filled_by')->count();
        $total_bounty = TorrentRequest::all()->sum('bounty');
        $claimed_bounty = TorrentRequest::whereNotNull('filled_by')->sum('bounty');
        $unclaimed_bounty = TorrentRequest::whereNull('filled_by')->sum('bounty');

        $torrentRequest = TorrentRequest::query();
        $repository = $this->repository;

        return view('requests.requests', [
            'torrentRequest' => $torrentRequest,
            'repository' => $repository,
            'user' => $user,
            'num_req' => $num_req,
            'num_fil' => $num_fil,
            'num_unfil' => $num_unfil,
            'total_bounty' => $total_bounty,
            'claimed_bounty' => $claimed_bounty,
            'unclaimed_bounty' => $unclaimed_bounty
        ]);
    }

    /**
     * Uses Input's To Put Together A Search
     *
     * @param \Illuminate\Http\Request $request
     * @param TorrentRequest $torrentRequest
     * @return array
     */
    public function faceted(Request $request, TorrentRequest $torrentRequest)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $imdb = $request->input('imdb');
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $categories = $request->input('categories');
        $types = $request->input('types');
        $myrequests = $request->input('myrequests');
        $unfilled = $request->input('unfilled');
        $claimed = $request->input('claimed');
        $pending = $request->input('pending');
        $filled = $request->input('filled');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%' . $term . '%';
        }

        $torrentRequest = $torrentRequest->newQuery();

        if ($request->has('search') && $request->input('search') != null) {
            $torrentRequest->where('name', 'like', $search);
        }

        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrentRequest->where('imdb', $imdb);
        }

        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrentRequest->where('tvdb', $tvdb);
        }

        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrentRequest->where('tmdb', $tmdb);
        }

        if ($request->has('mal') && $request->input('mal') != null) {
            $torrentRequest->where('mal', $mal);
        }

        if ($request->has('categories') && $request->input('categories') != null) {
            $torrentRequest->whereIn('category_id', $categories);
        }

        if ($request->has('types') && $request->input('types') != null) {
            $torrentRequest->whereIn('type', $types);
        }

        if ($request->has('myrequests') && $request->input('myrequests') != null) {
            $torrentRequest->where('user_id', $myrequests);
        }

        if ($request->has('unfilled') && $request->input('unfilled') != null) {
            $torrentRequest->where('filled_hash', null);
        }

        if ($request->has('claimed') && $request->input('claimed') != null) {
            $torrentRequest->where('claimed', '!=', null)->where('filled_hash', null);
        }

        if ($request->has('pending') && $request->input('pending') != null) {
            $torrentRequest->where('filled_hash', '!=', null)->where('approved_by', null);
        }

        if ($request->has('filled') && $request->input('filled') != null) {
            $torrentRequest->where('filled_hash', '!=', null)->where('approved_by', '!=', null);
        }

        // pagination query starts
        $rows = $torrentRequest->count();

        if ($request->has('page')) {
            $page = $request->input('page');
            $qty = $request->input('qty');
            $torrentRequest->skip(($page - 1) * $qty);
            $active = $page;
        } else {
            $active = 1;
        }

        if ($request->has('qty')) {
            $qty = $request->input('qty');
            $torrentRequest->take($qty);
        } else {
            $qty = 6;
            $torrentRequest->take($qty);
        }
        // pagination query ends

        if ($request->has('sorting')) {
            $sorting = $request->input('sorting');
            $order = $request->input('direction');
            $torrentRequest->orderBy($sorting, $order);
        }

        $listings = $torrentRequest->get();

        $helper = new RequestViewHelper();
        $result = $helper->view($listings);

        return ['result' => $result, 'rows' => $rows, 'qty' => $qty, 'active' => $active];
    }

    /**
     * Display The Torrent Request
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function request($id)
    {
        // Find the torrent in the database
        $torrentRequest = TorrentRequest::findOrFail($id);
        $user = auth()->user();
        $torrentRequestClaim = TorrentRequestClaim::where('request_id', $id)->first();
        $voters = $torrentRequest->requestBounty()->get();
        $comments = $torrentRequest->comments()->latest('created_at')->paginate(6);
        $carbon = Carbon::now()->addDay();
        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        if ($torrentRequest->category_id == 2) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $movie = $client->scrape('tv', null, $torrentRequest->tmdb);
            } else {
                $movie = $client->scrape('tv', 'tt' . $torrentRequest->imdb);
            }
        } else {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $movie = $client->scrape('movie', null, $torrentRequest->tmdb);
            } else {
                $movie = $client->scrape('movie', 'tt' . $torrentRequest->imdb);
            }
        }

        return view('requests.request', [
            'torrentRequest' => $torrentRequest,
            'voters' => $voters, 'user' => $user,
            'comments' => $comments,
            'carbon' => $carbon,
            'movie' => $movie,
            'torrentRequestClaim' => $torrentRequestClaim
        ]);
    }

    /**
     * Torrent Request Add Form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addRequestForm()
    {
        $user = auth()->user();

        return view('requests.add_request', [
            'categories' => Category::all()->sortBy('position'),
            'types' => Type::all()->sortBy('position'),
            'user' => $user
        ]);
    }

    /**
     * Add A Torrent Request
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function addrequest(Request $request)
    {
        $user = auth()->user();

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
        $tr->type = $request->input('type');
        $tr->bounty = $request->input('bounty');
        $tr->votes = 1;

        $v = validator($tr->toArray(), [
            "name" => "required|max:180",
            "imdb" => "required|numeric",
            "tvdb" => "required|numeric",
            "tmdb" => "required|numeric",
            "mal" => "required|numeric",
            "category_id" => "required|exists:categories,id",
            "type" => "required",
            "description" => "required|string",
            "bounty" => "required|numeric|min:0|max:{$user->seedbonus}"
        ]);

        if ($v->fails()) {
            return redirect()->route('requests')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {

            $tr->save();

            $requestsBounty = new TorrentRequestBounty();
            $requestsBounty->user_id = $user->id;
            $requestsBounty->seedbonus = $request->input('bounty');
            $requestsBounty->requests_id = $tr->id;
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

            $tr_url = hrefTorrentRequest($tr);
            $profile_url = hrefProfile($user);

            // Auto Shout
            $this->chat->systemMessage(
                "[url={$profile_url}]{$user->username}[/url] has created a new request [url={$tr_url}]{$tr->name}[/url]"
            );

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has made a new torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            return redirect('/requests')
                ->with(Toastr::success('Request Added.', 'Yay!', ['options']));
        }
    }

    /**
     * Torrent Request Edit Form
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRequestForm($id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        return view('requests.edit_request', [
            'categories' => Category::all()->sortBy('position'),
            'types' => Type::all()->sortBy('position'),
            'user' => $user,
            'torrentRequest' => $torrentRequest]);
    }

    /**
     * Edit A Torrent Request
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function editrequest(Request $request, $id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);
        if ($user->group->is_modo || $user->id == $torrentRequest->user_id) {
            // Find the right category
            $name = $request->input('name');
            $imdb = $request->input('imdb');
            $tvdb = $request->input('tvdb');
            $tmdb = $request->input('tmdb');
            $mal = $request->input('mal');
            $category = $request->input('category_id');
            $type = $request->input('type');
            $description = $request->input('description');

            $torrentRequest->name = $name;
            $torrentRequest->imdb = $imdb;
            $torrentRequest->tvdb = $tvdb;
            $torrentRequest->tmdb = $tmdb;
            $torrentRequest->mal = $mal;
            $torrentRequest->category_id = $category;
            $torrentRequest->type = $type;
            $torrentRequest->description = $description;

            $v = validator($torrentRequest->toArray(), [
                "name" => "required|max:180",
                "imdb" => "required|numeric",
                "tvdb" => "required|numeric",
                "tmdb" => "required|numeric",
                "mal" => "required|numeric",
                "category_id" => "required|exists:categories,id",
                "type" => "required",
                "description" => "required|string"

            ]);

            if ($v->fails()) {
                return redirect()->route('requests')
                    ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
            } else {
                $torrentRequest->save();

                if ($user->group->is_modo) {
                    // Activity Log
                    \LogActivity::addToLog("Staff Member {$user->username} has edited torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");
                } else {
                    // Activity Log
                    \LogActivity::addToLog("Member {$user->username} has edited torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");
                }

                return redirect()->route('requests', ['id' => $torrentRequest->id])
                    ->with(Toastr::success('Request Edited Successfully.', 'Yay!', ['options']));
            }
        }
    }

    /**
     * Add Bounty To A Torrent Request
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function addBonus(Request $request, $id)
    {
        $user = auth()->user();

        $tr = TorrentRequest::findOrFail($id);
        $tr->votes += 1;
        $tr->bounty += $request->input('bonus_value');
        $tr->created_at = Carbon::now();

        $v = validator($request->all(), [
            'bonus_value' => "required|numeric|min:100|max:{$user->seedbonus}",
        ]);

        if ($v->fails()) {
            return redirect()->route('request', ['id' => $tr->id])
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $tr->save();

            $requestsBounty = new TorrentRequestBounty();
            $requestsBounty->user_id = $user->id;
            $requestsBounty->seedbonus = $request->input('bonus_value');
            $requestsBounty->requests_id = $tr->id;
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

            $tr_url = hrefTorrentRequest($tr);
            $profile_url = hrefProfile($user);

            // Auto Shout
            $this->chat->systemMessage(
                "[url={$profile_url}]{$user->username}[/url] has addded {$request->input('bonus_value')} BON bounty to request [url={$tr_url}]{$tr->name}[/url]"
            );

            // Send Private Message
            $pm = new PrivateMessage;
            $pm->sender_id = 1;
            $pm->receiver_id = $tr->user_id;
            $pm->subject = "Your Request " . $tr->name . " Has A New Bounty!";
            $pm->message = $user->username . " Has Added A Bounty To " . "[url={$tr_url}]" . $tr->name . "[/url]";
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has added a BON bounty to torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            return redirect()->route('request', ['id' => $request->input('request_id')])
                ->with(Toastr::success('Your bonus has been successfully added.', 'Yay!', ['options']));
        }
    }

    /**
     * Fill A Torrent Request
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function fillRequest(Request $request, $id)
    {
        $user = auth()->user();

            $v = validator($request->all(), [
                'request_id' => "required|exists:requests,id",
                'info_hash' => "required|exists:torrents,info_hash",
            ]);

            if ($v->passes()) {
                $torrent = Torrent::where('info_hash', $request->input('info_hash'))->firstOrFail();

                if ($user->id == $torrent->user_id) {
                    $this->addRequestModeration($request->input('request_id'), $request->input('info_hash'));

                    return redirect()->route('request', ['id' => $request->input('request_id')])
                        ->with(Toastr::success('Your request fill is pending approval by the Requestor.', 'Yay!', ['options']));
                } elseif ($user->id != $torrent->user_id && Carbon::now()->addDay() > $torrent->created_at) {
                    $this->addRequestModeration($request->input('request_id'), $request->input('info_hash'));

                    return redirect()->route('request', ['id' => $request->input('request_id')])
                        ->with(Toastr::success('Your request fill is pending approval by the Requestor.', 'Yay!', ['options']));
                } else {
                    return redirect()->route('request', ['id' => $request->input('request_id')])
                        ->with(Toastr::error('You cannot fill this request for some weird reason', 'Whoops!', ['options']));
                }
            } else {
                return redirect()->route('request', ['id' => $request->input('request_id')])
                    ->with(Toastr::error('You failed to adhere to the requirements.', 'Whoops!', ['options']));
            }
    }

    /**
     * Function that handles the actual filling of requests
     * @method addRequestModeration
     *
     * @param $request_id ID of the Request being handled
     * @param $info_hash Hash of the torrent filling the hash
     */
    public function addRequestModeration($request_id, $info_hash)
    {
        $user = auth()->user();

        $torrentRequest = TorrentRequest::findOrFail($request_id);

        $torrentRequest->filled_by = $user->id;
        $torrentRequest->filled_hash = $info_hash;
        $torrentRequest->filled_when = Carbon::now();

        $torrentRequest->save();

        // Send Private Message
        $appurl = config('app.url');

        $pm = new PrivateMessage;
        $pm->sender_id = 1;
        $pm->receiver_id = $torrentRequest->user_id;
        $pm->subject = "Your Request " . $torrentRequest->name . " Has Been Filled!";
        $pm->message = $torrentRequest->filled_by . " Has Filled Your Request [url={$appurl}/request/" . $torrentRequest->id . "]" . $torrentRequest->name . "[/url]" . " Please Approve or Decline The FullFill! ";
        $pm->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has added a BON bounty to torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} . It is now pending approval.");
    }

    /**
     * Approve A Torrent Request
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function approveRequest($id)
    {
        $user = auth()->user();

        $tr = TorrentRequest::findOrFail($id);

        if ($user->id == $tr->user_id || auth()->user()->group->is_modo) {
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
            $BonTransactions->comment = "{$fill_user->username} has filled {$tr->name} and has been awared {$fill_amount} BONUS.";
            $BonTransactions->save();

            $fill_user->seedbonus += $fill_amount;
            $fill_user->save();
            //End BON code here.

            // Achievements
            $fill_user->addProgress(new UserFilled25Requests(), 1);
            $fill_user->addProgress(new UserFilled50Requests(), 1);
            $fill_user->addProgress(new UserFilled75Requests(), 1);
            $fill_user->addProgress(new UserFilled100Requests(), 1);

            $tr_url = hrefTorrentRequest($tr);
            $profile_url = hrefProfile($fill_user);

            // Auto Shout
            $this->chat->systemMessage(
                "[url={$profile_url}]{$fill_user->username}[/url] has filled [url={$tr_url}]{$tr->name}[/url]"
            );

            // Send Private Message
            $pm = new PrivateMessage;
            $pm->sender_id = 1;
            $pm->receiver_id = $tr->filled_by;
            $pm->subject = "Your Request Fulfill On " . $tr->name . " Has Been Approved!";
            $pm->message = $tr->approved_by . " Has Approved Your Fulfillment On [url={$tr_url}]" . $tr->name . "[/url] Enjoy The " . $tr->bounty . " Bonus Points!";
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has approved {$fill_user->username} fill on torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::success("You have approved {$tr->name} and the bounty has been awarded to {$fill_user->username}", "Yay!", ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::error("You don't have access to approve this request", 'Whoops!', ['options']));
        }
    }

    /**
     * Reject A Torrent Request
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function rejectRequest($id)
    {
        $user = auth()->user();
        $appurl = config('app.url');
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->id == $torrentRequest->user_id) {
            // Send Private Message
            $pm = new PrivateMessage;
            $pm->sender_id = 1;
            $pm->receiver_id = $torrentRequest->filled_by;
            $pm->subject = "Your Request Fullfill On " . $torrentRequest->name . " Has Been Declined!";
            $pm->message = $user->username . " Has Declined Your Fulfillment On [url={$appurl}/request/" . $torrentRequest->id . "]" . $torrentRequest->name . "[/url] It did not meet the requirements!";
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has declined {$torrentRequest->filled_by} fill on torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->save();

            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::success("This request has been reset.", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::success("You don't have access to approve this request", 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Torrent Request
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteRequest($id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->group->is_modo || $torrentRequest->user_id == $user->id) {
            $name = $torrentRequest->name;
            $torrentRequest->delete();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has deleted torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            return redirect()->route('requests')
                ->with(Toastr::success("You have deleted {$name}", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::error("You don't have access to delete this request.", 'Whoops!', ['options']));
        }
    }

    /**
     * Claim A Torrent Request
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function claimRequest(Request $request, $id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($torrentRequest->claimed == null) {
            $requestClaim = new TorrentRequestClaim();
            $requestClaim->request_id = $id;
            $requestClaim->username = $user->username;
            $requestClaim->anon = $request->input('anon');
            $requestClaim->save();

            $torrentRequest->claimed = 1;
            $torrentRequest->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has claimed torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::success("Request Successfully Claimed", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::error("Someone else has already claimed this request buddy.", 'Whoops!', ['options']));
        }
    }

    /**
     * Uncliam A Torrent Request
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function unclaimRequest($id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);
        $claimer = TorrentRequestClaim::where('request_id', $id)->first();

        if ($user->group->is_modo || $user->username == $claimer->username) {
            if ($torrentRequest->claimed == 1) {
                $requestClaim = TorrentRequestClaim::where('request_id', $id)->firstOrFail();
                $requestClaim->delete();

                $torrentRequest->claimed = null;
                $torrentRequest->save();

                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has un-claimed torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

                return redirect()->route('request', ['id' => $id])
                    ->with(Toastr::success("Request Successfully Un-Claimed", 'Yay!', ['options']));
            } else {
                return redirect()->route('request', ['id' => $id])
                    ->with(Toastr::error("Nothing To Unclaim.", 'Whoops!', ['options']));
            }
        } else {
            return back()->with(Toastr::error('You Are Not Authorized To Perform This Action!', 'Error 403', ['options']));
        }
    }
}
