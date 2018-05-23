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
use App\Message;
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

    private $chat;

    public function __construct(RequestFacetedRepository $repository, ChatRepository $chat)
    {
        $this->repository = $repository;
        $this->chat = $chat;
    }

    /**
     * Torrent Requests
     *
     *
     * @access public
     * @return view::make requests.requests
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

        return view('requests.requests', ['torrentRequest' => $torrentRequest, 'repository' => $repository, 'user' => $user, 'num_req' => $num_req, 'num_fil' => $num_fil, 'num_unfil' => $num_unfil, 'total_bounty' => $total_bounty, 'claimed_bounty' => $claimed_bounty, 'unclaimed_bounty' => $unclaimed_bounty]);
    }

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
     * Torrent Request
     *
     *
     * @access public
     * @return view::make requests.request
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

        return view('requests.request', ['torrentRequest' => $torrentRequest, 'voters' => $voters, 'user' => $user, 'comments' => $comments, 'carbon' => $carbon, 'movie' => $movie, 'torrentRequestClaim' => $torrentRequestClaim]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addRequestForm()
    {
        $user = auth()->user();
        return view('requests.add_request', ['categories' => Category::all()->sortBy('position'), 'types' => Type::all()->sortBy('position'), 'user' => $user]);
    }

    /**
     * Add Torrent Request
     *
     *
     * @access public
     * @return Redirect::to
     */
    public function addrequest(Request $request)
    {
        $user = auth()->user();
        $v = validator($request->all(), [
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

        if ($v->passes()) {
            // Find the right category
            $category = Category::findOrFail($request->input('category_id'));

            // Holders for new data
            $tr = new TorrentRequest([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'category_id' => $category->id,
                'user_id' => $user->id,
                'imdb' => $request->input('imdb'),
                'tvdb' => $request->input('tvdb'),
                'tmdb' => $request->input('tmdb'),
                'mal' => $request->input('mal'),
                'type' => $request->input('type'),
                'bounty' => $request->input('bounty'),
                'votes' => 1,
            ]);
            $tr->save();

            $requestsBounty = new TorrentRequestBounty([
                'user_id' => $user->id,
                'seedbonus' => $request->input('bounty'),
                'requests_id' => $tr->id,
            ]);
            $requestsBounty->save();

            $BonTransactions = new BonTransactions([
                'itemID' => 0,
                'name' => 'request',
                'cost' => $request->input('bounty'),
                'sender' => $user->id,
                'receiver' => 0,
                'comment' => "new request - {$request->input('name')}"
            ]);
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

            return redirect('/requests')->with(Toastr::success('Request Added.', 'Yay!', ['options']));
        } else {
            return redirect('/requests')->with(Toastr::error('Not all the required information was provided, please try again.', 'Whoops!', ['options']));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRequestForm($id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        return view('requests.edit_request', ['categories' => Category::all()->sortBy('position'), 'types' => Type::all()->sortBy('position'), 'user' => $user, 'torrentRequest' => $torrentRequest]);
    }

    /**
     * Edit Torrent Request
     *
     *
     * @access public
     * @return Redirect::to
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
            $torrentRequest->save();

            if ($user->group->is_modo) {
                // Activity Log
                \LogActivity::addToLog("Staff Member {$user->username} has edited torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");
            } else {
                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has edited torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");
            }

            return redirect()->route('requests', ['id' => $torrentRequest->id])->with(Toastr::success('Request Edited Successfuly.', 'Yay!', ['options']));
        } else {
            return view('requests.edit_request', ['categories' => Category::all()->sortBy('position'), 'types' => Type::all()->sortBy('position'), 'user' => $user, 'torrentRequest' => $torrentRequest]);
        }
    }

    /**
     * Add Bounty to a request
     *
     * @access public
     * @return Redirect::route
     */
    public function addBonus(Request $request, $id)
    {
        $user = auth()->user();

        if ($request->isMethod('POST') && $user->seedbonus >= 100) {
            $v = validator($request->all(), [
                'request_id' => "required|exists:requests,id",
                'bonus_value' => "required|numeric|min:100|max:{$user->seedbonus}",
            ]);

            if ($v->passes()) {
                $tr = TorrentRequest::findOrFail($request->input('request_id'));

                $tr->votes += 1;
                $tr->bounty += $request->input('bonus_value');
                $tr->created_at = Carbon::now();
                $tr->save();

                $requestsBounty = new TorrentRequestBounty([
                    'user_id' => $user->id,
                    'seedbonus' => $request->input('bonus_value'),
                    'requests_id' => $tr->id,
                ]);
                $requestsBounty->save();

                $BonTransactions = new BonTransactions([
                    'itemID' => 0,
                    'name' => 'request',
                    'cost' => $request->input('bonus_value'),
                    'sender' => $user->id,
                    'receiver' => 0,
                    'comment' => "adding bonus to {$tr->name}"
                ]);
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

                return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::success('Your bonus has been successfully added.', 'Yay!', ['options']));
            } else {
                return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::error('You failed to adhere to the requirements.', 'Whoops!', ['options']));
            }
        } else {
            return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::error('The server doesnt unserstand your request.', 'Whoops!', ['options']));
        }
        return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::error('Something went horribly wrong', 'Whoops!', ['options']));
    }

    /**
     * Fill a request
     * @method fillRequest
     *
     * @param $id ID of the request
     *
     */
    public function fillRequest(Request $request, $id)
    {
        $user = auth()->user();

        if ($request->isMethod('POST')) {
            $v = validator($request->all(), [
                'request_id' => "required|exists:requests,id",
                'info_hash' => "required|exists:torrents,info_hash",
            ]);

            if ($v->passes()) {
                $torrent = Torrent::where('info_hash', $request->input('info_hash'))->firstOrFail();

                if ($user->id == $torrent->user_id) {
                    $this->addRequestModeration($request->input('request_id'), $request->input('info_hash'));

                    return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::success('Your request fill is pending approval by the Requestor.', 'Yay!', ['options']));
                } elseif ($user->id != $torrent->user_id && Carbon::now()->addDay() > $torrent->created_at) {
                    $this->addRequestModeration($request->input('request_id'), $request->input('info_hash'));

                    return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::success('Your request fill is pending approval by the Requestor.', 'Yay!', ['options']));
                } else {
                    return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::error('You cannot fill this request for some weird reason', 'Whoops!', ['options']));
                }
            } else {
                return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::error('You failed to adhere to the requirements.', 'Whoops!', ['options']));
            }
        } else {
            return redirect()->route('request', ['id' => $request->input('request_id')])->with(Toastr::error('The server doesnt understand your request.', 'Whoops!', ['options']));
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
     * Approves the filled torrent on a request
     * @method approveRequest
     *
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

            $BonTransactions = new BonTransactions([
                'itemID' => 0,
                'name' => 'request',
                'cost' => $fill_amount,
                'sender' => 0,
                'receiver' => $fill_user->id,
                'comment' => "{$fill_user->username} has filled {$tr->name} and has been awared {$fill_amount} BONUS."
            ]);
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
            $pm->subject = "Your Request Fullfill On " . $tr->name . " Has Been Approved!";
            $pm->message = $tr->approved_by . " Has Approved Your Fullfillment On [url={$tr_url}]" . $tr->name . "[/url] Enjoy The " . $tr->bounty . " Bonus Points!";
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has approved {$fill_user->username} fill on torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            return redirect()->route('request', ['id' => $id])->with(Toastr::success("You have approved {$tr->name} and the bounty has been awarded to {$fill_user->username}", "Yay!", ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])->with(Toastr::error("You don't have access to approve this request", 'Whoops!', ['options']));
        }
    }

    /**
     * Rejects the filling torrent on a request
     * @method rejectRequest
     *
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
            $pm->message = $user->username . " Has Declined Your Fullfillment On [url={$appurl}/request/" . $torrentRequest->id . "]" . $torrentRequest->name . "[/url] It did not meet the requirements!";
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has declined {$torrentRequest->filled_by} fill on torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->save();

            return redirect()->route('request', ['id' => $id])->with(Toastr::success("This request has been reset.", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])->with(Toastr::success("You don't have access to approve this request", 'Yay!', ['options']));
        }
    }

    /**
     * Deletes a specific request
     * @method deleteRequest
     *
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

            return redirect()->route('requests')->with(Toastr::success("You have deleted {$name}", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])->with(Toastr::error("You don't have access to delete this request.", 'Whoops!', ['options']));
        }
    }

    /**
     * User can claim a specific request
     * @method claimRequest
     *
     */
    public function claimRequest(Request $request, $id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($torrentRequest->claimed == null) {
            $requestClaim = new TorrentRequestClaim([
                'request_id' => $id,
                'username' => $user->username,
                'anon' => $request->input('anon'),
            ]);
            $requestClaim->save();

            $torrentRequest->claimed = 1;
            $torrentRequest->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has claimed torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            return redirect()->route('request', ['id' => $id])->with(Toastr::success("Request Successfuly Claimed", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])->with(Toastr::error("Someone else has already claimed this request buddy.", 'Whoops!', ['options']));
        }
    }

    /**
     * User can claim a specific request
     * @method claimRequest
     *
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

                return redirect()->route('request', ['id' => $id])->with(Toastr::success("Request Successfuly Un-Claimed", 'Yay!', ['options']));
            } else {
                return redirect()->route('request', ['id' => $id])->with(Toastr::error("Nothing To Unclaim.", 'Whoops!', ['options']));
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
