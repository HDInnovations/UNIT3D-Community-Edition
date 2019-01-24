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
 * @author     Mr.G
 */

namespace App\Http\Controllers;

use App\Type;
use App\User;
use App\Torrent;
use App\Category;
use Carbon\Carbon;
use App\PrivateMessage;
use App\TorrentRequest;
use App\BonTransactions;
use App\TorrentRequestClaim;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\TorrentRequestBounty;
use App\Repositories\ChatRepository;
use App\Achievements\UserFilled25Requests;
use App\Achievements\UserFilled50Requests;
use App\Achievements\UserFilled75Requests;
use App\Achievements\UserFilled100Requests;
use App\Repositories\RequestFacetedRepository;

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
     * @var Toastr
     */
    private $toastr;

    /**
     * RequestController Constructor.
     *
     * @param RequestFacetedRepository $faceted
     * @param ChatRepository           $chat
     * @param Toastr                   $toastr
     */
    public function __construct(RequestFacetedRepository $faceted, ChatRepository $chat, Toastr $toastr)
    {
        $this->faceted = $faceted;
        $this->chat = $chat;
        $this->toastr = $toastr;
    }

    /**
     * Displays Torrent List View.
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
     * @return array
     */
    public function faceted(Request $request, TorrentRequest $torrentRequest)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $imdb_id = starts_with($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');
        $imdb = str_replace('tt', '', $imdb_id);
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
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
            $torrentRequest->where('imdb', '=', $imdb);
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

        if ($request->has('categories') && $request->input('categories') != null) {
            $torrentRequest->whereIn('category_id', $categories);
        }

        if ($request->has('types') && $request->input('types') != null) {
            $torrentRequest->whereIn('type', $types);
        }

        if ($request->has('myrequests') && $request->input('myrequests') != null) {
            $torrentRequest->where('user_id', '=', $myrequests);
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
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function request($id)
    {
        // Find the torrent in the database
        $torrentRequest = TorrentRequest::findOrFail($id);
        $user = auth()->user();
        $torrentRequestClaim = TorrentRequestClaim::where('request_id', '=', $id)->first();
        $voters = $torrentRequest->requestBounty()->get();
        $comments = $torrentRequest->comments()->latest('created_at')->paginate(6);
        $carbon = Carbon::now()->addDay();
        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        if ($torrentRequest->category_id == 2) {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $movie = $client->scrape('tv', null, $torrentRequest->tmdb);
            } else {
                $movie = $client->scrape('tv', 'tt'.$torrentRequest->imdb);
            }
        } else {
            if ($torrentRequest->tmdb || $torrentRequest->tmdb != 0) {
                $movie = $client->scrape('movie', null, $torrentRequest->tmdb);
            } else {
                $movie = $client->scrape('movie', 'tt'.$torrentRequest->imdb);
            }
        }

        return view('requests.request', [
            'torrentRequest'      => $torrentRequest,
            'voters'              => $voters, 'user' => $user,
            'comments'            => $comments,
            'carbon'              => $carbon,
            'movie'               => $movie,
            'torrentRequestClaim' => $torrentRequestClaim,
        ]);
    }

    /**
     * Torrent Request Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addRequestForm()
    {
        $user = auth()->user();

        return view('requests.add_request', [
            'categories' => Category::all()->sortBy('position'),
            'types'      => Type::all()->sortBy('position'),
            'user'       => $user,
        ]);
    }

    /**
     * Add A Torrent Request.
     *
     * @param \Illuminate\Http\Request $request
     *
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
        $tr->anon = $request->input('anon');

        $v = validator($tr->toArray(), [
            'name'        => 'required|max:180',
            'imdb'        => 'required|numeric',
            'tvdb'        => 'required|numeric',
            'tmdb'        => 'required|numeric',
            'mal'         => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required',
            'description' => 'required|string',
            'bounty'      => "required|numeric|min:0|max:{$user->seedbonus}",
            'anon'        => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('requests')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
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
                    ":robot: [b][color=#fb9776]System[/color][/b] : [url={$profile_url}]{$user->username}[/url] has created a new request [url={$tr_url}]{$tr->name}[/url]"
                );
            } else {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : An anonymous user has created a new request [url={$tr_url}]{$tr->name}[/url]"
                );
            }

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has made a new torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            return redirect('/requests')
                ->with($this->toastr->success('Request Added.', 'Yay!', ['options']));
        }
    }

    /**
     * Torrent Request Edit Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRequestForm($id)
    {
        $user = auth()->user();
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
            $anon = $request->input('anon');

            $torrentRequest->name = $name;
            $torrentRequest->imdb = $imdb;
            $torrentRequest->tvdb = $tvdb;
            $torrentRequest->tmdb = $tmdb;
            $torrentRequest->mal = $mal;
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
                'category_id' => 'required|exists:categories,id',
                'type'        => 'required',
                'description' => 'required|string',
                'anon'        => 'required',
            ]);

            if ($v->fails()) {
                return redirect()->route('requests')
                    ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
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
                    ->with($this->toastr->success('Request Edited Successfully.', 'Yay!', ['options']));
            }
        }
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
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
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

            $tr_url = hrefTorrentRequest($tr);
            $profile_url = hrefProfile($user);

            // Auto Shout
            if ($requestsBounty->anon == 0) {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : [url={$profile_url}]{$user->username}[/url] has added {$request->input('bonus_value')} BON bounty to request [url={$tr_url}]{$tr->name}[/url]"
                );
            } else {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : An anonymous user added {$request->input('bonus_value')} BON bounty to request [url={$tr_url}]{$tr->name}[/url]"
                );
            }

            // Send Private Message
            if ($user->id != $tr->user_id) {
                $pm = new PrivateMessage();
                $pm->sender_id = 1;
                $pm->receiver_id = $tr->user_id;
                $pm->subject = 'Your Request '.$tr->name.' Has A New Bounty!';
                $profile_url = hrefProfile($user);
                if ($requestsBounty->anon == 0) {
                    $pm->message = "[url={$profile_url}]{$user->username}[/url] Has Added A Bounty To "."[url={$tr_url}]".$tr->name.'[/url]';
                } else {
                    $pm->message = 'An Anonymous User Has Added A Bounty To '."[url={$tr_url}]".$tr->name.'[/url]';
                }
                $pm->save();
            }

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has added a BON bounty to torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            return redirect()->route('request', ['id' => $request->input('request_id')])
                ->with($this->toastr->success('Your bonus has been successfully added.', 'Yay!', ['options']));
        }
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
        $user = auth()->user();

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

        if ($v->fails()) {
            return redirect()->route('request', ['id' => $request->input('request_id')])
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $torrentRequest->save();

            // Send Private Message
            $appurl = config('app.url');

            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $torrentRequest->user_id;
            $pm->subject = 'Your Request '.$torrentRequest->name.' Has Been Filled!';
            $profile_url = hrefProfile($user);
            if ($torrentRequest->filled_anon == 0) {
                $pm->message = "[url={$profile_url}]{$user->username}[/url] Has Filled Your Request [url={$appurl}/request/".$torrentRequest->id.']'.$torrentRequest->name.'[/url]'.' Please Approve or Decline The FullFill! ';
            } else {
                $pm->message = "An Anonymous User Filled Your Request [url={$appurl}/request/".$torrentRequest->id.']'.$torrentRequest->name.'[/url]'.' Please Approve or Decline The FullFill! ';
            }
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has filled torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} . It is now pending approval.");

            return redirect()->route('request', ['id' => $request->input('request_id')])
                ->with($this->toastr->success('Your request fill is pending approval by the Requester.', 'Yay!', ['options']));
        }
    }

    /**
     * Approve A Torrent Request.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approveRequest($id)
    {
        $user = auth()->user();

        $tr = TorrentRequest::findOrFail($id);

        if ($user->id == $tr->user_id || auth()->user()->group->is_modo) {
            if ($tr->approved_by != null) {
                return redirect()->route('request', ['id' => $id])
                    ->with($this->toastr->error('Seems this request was already approved', 'Whoops!', ['options']));
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

            $tr_url = hrefTorrentRequest($tr);
            $profile_url = hrefProfile($fill_user);

            // Auto Shout
            if ($tr->filled_anon == 0) {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : [url={$profile_url}]{$fill_user->username}[/url] has filled request, [url={$tr_url}]{$tr->name}[/url]"
                );
            } else {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : An anonymous user has filled request, [url={$tr_url}]{$tr->name}[/url]"
                );
            }

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $tr->filled_by;
            $pm->subject = 'Your Request Fulfill On '.$tr->name.' Has Been Approved!';
            $profile_url = hrefProfile($user);
            $pm->message = "[url={$profile_url}]{$user->username}[/url] Has Approved Your Fulfillment On [url={$tr_url}]".$tr->name.'[/url] Enjoy The '.$tr->bounty.' Bonus Points!';
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has approved {$fill_user->username} fill on torrent request, ID: {$tr->id} NAME: {$tr->name} .");

            if ($tr->filled_anon == 0) {
                return redirect()->route('request', ['id' => $id])
                    ->with($this->toastr->success("You have approved {$tr->name} and the bounty has been awarded to {$fill_user->username}", 'Yay!', ['options']));
            } else {
                return redirect()->route('request', ['id' => $id])
                    ->with($this->toastr->success("You have approved {$tr->name} and the bounty has been awarded to a anonymous user", 'Yay!', ['options']));
            }
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->error("You don't have access to approve this request", 'Whoops!', ['options']));
        }
    }

    /**
     * Reject A Torrent Request.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function rejectRequest($id)
    {
        $user = auth()->user();
        $appurl = config('app.url');
        $torrentRequest = TorrentRequest::findOrFail($id);

        if ($user->id == $torrentRequest->user_id) {
            if ($torrentRequest->approved_by != null) {
                return redirect()->route('request', ['id' => $id])
                    ->with($this->toastr->error('Seems this request was already rejected', 'Whoops!', ['options']));
            }

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $torrentRequest->filled_by;
            $pm->subject = 'Your Request Fullfill On '.$torrentRequest->name.' Has Been Declined!';
            $profile_url = hrefProfile($user);
            $pm->message = "[url={$profile_url}]{$user->username}[/url] Has Declined Your Fulfillment On [url={$appurl}/request/".$torrentRequest->id.']'.$torrentRequest->name.'[/url] It did not meet the requirements!';
            $pm->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has declined {$torrentRequest->filled_by} fill on torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->save();

            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->success('This request has been reset.', 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->success("You don't have access to approve this request", 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Torrent Request.
     *
     * @param $id
     *
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
                ->with($this->toastr->success("You have deleted {$name}", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->error("You don't have access to delete this request.", 'Whoops!', ['options']));
        }
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
                ->with($this->toastr->success('Request Successfully Claimed', 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->error('Someone else has already claimed this request buddy.', 'Whoops!', ['options']));
        }
    }

    /**
     * Uncliam A Torrent Request.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unclaimRequest($id)
    {
        $user = auth()->user();
        $torrentRequest = TorrentRequest::findOrFail($id);
        $claimer = TorrentRequestClaim::where('request_id', '=', $id)->first();

        abort_unless($user->group->is_modo || $user->username == $claimer->username, 403);

        if ($torrentRequest->claimed == 1) {
            $requestClaim = TorrentRequestClaim::where('request_id', '=', $id)->firstOrFail();
            $requestClaim->delete();

            $torrentRequest->claimed = null;
            $torrentRequest->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has un-claimed torrent request, ID: {$torrentRequest->id} NAME: {$torrentRequest->name} .");

            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->success('Request Successfully Un-Claimed', 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with($this->toastr->error('Nothing To Unclaim.', 'Whoops!', ['options']));
        }
    }
}
