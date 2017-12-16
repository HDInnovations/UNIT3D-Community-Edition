<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     Mr.G
 */

namespace App\Http\Controllers;

use App\BonTransactions;
use App\Category;
use App\Type;
use App\Requests;
use App\RequestsBounty;
use App\RequestsClaims;
use App\Torrent;
use App\Shoutbox;
use App\User;
use Carbon\Carbon;
use Decoda\Decoda;
use App\PrivateMessage;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Achievements\UserFilled25Requests;
use App\Achievements\UserFilled50Requests;
use App\Achievements\UserFilled75Requests;
use App\Achievements\UserFilled100Requests;

use \Toastr;
use Cache;

class RequestController extends Controller
{
    /**
     * Search for requests
     *
     * @access public
     * @return View page.requests
     *
     */
    public function search()
    {
        $user = Auth::user();
        $num_req = Requests::count();
        $num_fil = Requests::whereNotNull('filled_by')->count();
        $num_unfil = Requests::whereNull('filled_by')->count();
        $total_bounty = Requests::all()->sum('bounty');
        $claimed_bounty = Requests::whereNotNull('filled_by')->sum('bounty');
        $unclaimed_bounty = Requests::whereNull('filled_by')->sum('bounty');
        $requests = Requests::where([
            ['name', 'like', '%' . Request::get('name') . '%'],
            ['category_id', '=', Request::get('category_id')],
        ])->orderBy('created_at', 'DESC')->paginate(25);

        $requests->setPath('?name=' . Request::get('name') . '&category_id=' . Request::get('category_id'));

        return view('requests.requests', ['requests' => $requests, 'user' => $user, 'num_req' => $num_req, 'num_fil' => $num_fil, 'num_unfil' => $num_unfil, 'total_bounty' => $total_bounty, 'claimed_bounty' => $claimed_bounty, 'unclaimed_bounty' => $unclaimed_bounty, 'categories' => Category::all()]);
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
        $user = Auth::user();
        $num_req = Requests::count();
        $num_fil = Requests::whereNotNull('filled_by')->count();
        $num_unfil = Requests::whereNull('filled_by')->count();
        $total_bounty = Requests::all()->sum('bounty');
        $claimed_bounty = Requests::whereNotNull('filled_by')->sum('bounty');
        $unclaimed_bounty = Requests::whereNull('filled_by')->sum('bounty');
        if (Request::get('filled_requests') == true) {
            $requests = Requests::whereNotNull('filled_by')->orderBy('created_at', 'DESC')->paginate(20);
            $requests->setPath('?filled_requests=true');
        } elseif (Request::get('unfilled_requests') == true) {
            $requests = Requests::whereNull('filled_by')->orderBy('created_at', 'DESC')->paginate(20);
            $requests->setPath('?unfilled_requests=true');
        } elseif (Request::get('my_requests') == true) {
            $requests = Requests::where('user_id', '=', $user->id)->orderBy('created_at', 'DESC')->paginate(20);
            $requests->setPath('?my_requests=true');
        } else {
            $requests = Requests::orderBy('created_at', 'DESC')->paginate(20);
        }
        return view('requests.requests', ['requests' => $requests, 'user' => $user, 'num_req' => $num_req, 'num_fil' => $num_fil, 'num_unfil' => $num_unfil, 'total_bounty' => $total_bounty, 'claimed_bounty' => $claimed_bounty, 'unclaimed_bounty' => $unclaimed_bounty, 'categories' => Category::all()]);
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
        $request = Requests::findOrFail($id);
        $user = Auth::user();
        $requestClaim = RequestsClaims::where('request_id', '=', $id)->first();
        $voters = $request->requestBounty()->get();
        $comments = $request->comments()->orderBy('created_at', 'DESC')->get();
        $carbon = Carbon::now()->addDay();
        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        if ($request->category_id == 2) {
            $movie = $client->scrape('tv', 'tt' . $request->imdb);
        } else {
            $movie = $client->scrape('movie', 'tt' . $request->imdb);
        }
        return view('requests.request', ['request' => $request, 'voters' => $voters, 'user' => $user, 'comments' => $comments, 'carbon' => $carbon, 'movie' => $movie, 'requestClaim' => $requestClaim]);
    }

    /**
     * Add Torrent Request
     *
     *
     * @access public
     * @return Redirect::to
     */
    public function addrequest()
    {
        $user = Auth::user();
        // Post the Request
        if (Request::isMethod('post')) {
            // Validator
            $v = Validator::make(Request::all(), [
                "name" => "required|max:180",
                "imdb" => "required|numeric",
                "tvdb" => "required|numeric",
                "tmdb" => "required|numeric",
                "mal" => "required|numeric",
                "category_id" => "required|exists:categories,id",
                "type" => "required",
                "description" => "required|string",
                "bounty" => "required|numeric|max:{$user->seedbonus}"
            ]);

            if ($v->passes()) {
                // Find the right category
                $category = Category::findOrFail(Request::get('category_id'));

                // Holders for new data
                $requests = new Requests([
                    'name' => Request::get('name'),
                    'description' => Request::get('description'),
                    'category_id' => $category->id,
                    'user_id' => $user->id,
                    'imdb' => Request::get('imdb'),
                    'tvdb' => Request::get('tvdb'),
                    'tmdb' => Request::get('tmdb'),
                    'mal' => Request::get('mal'),
                    'type' => Request::get('type'),
                    'bounty' => Request::get('bounty'),
                    'votes' => 1,
                ]);
                $requests->save();

                $requestsBounty = new RequestsBounty([
                    'user_id' => $user->id,
                    'seedbonus' => Request::get('bounty'),
                    'requests_id' => $requests->id,
                ]);
                $requestsBounty->save();

                $BonTransactions = new BonTransactions([
                    'itemID' => 0,
                    'name' => 'request',
                    'cost' => Request::get('bounty'),
                    'sender' => $user->id,
                    'receiver' => 0,
                    'comment' => "new request - " . Request::get('name') . ""
                ]);
                $BonTransactions->save();

                $user->seedbonus -= Request::get('bounty');
                $user->save();

                $appurl = env('APP_URL', 'http://unit3d.site');
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has created a new request [url={$appurl}/request/" . $requests->id . "]" . $requests->name . "[/url]"]);
                Cache::forget('shoutbox_messages');

                return Redirect::to('/requests')->with(Toastr::success('Request Added.', 'Successful', ['options']));
            } else {
                return Redirect::to('/requests')->with(Toastr::error('Not all the required information was provided, please try again.', 'Add request failed', ['options']));
            }
        } else {
            if ($user->seedbonus >= 100) {
                return view('requests.add_request', ['categories' => Category::all(), 'types' => Type::all()->sortBy('position'), 'user' => $user]);
            } else {
                return Redirect::to('/requests')->with(Toastr::error('You dont have the minium of 100 BON to make a request!', 'Error!', ['options']));
            }
        }
    }

    /**
     * Edit Torrent Request
     *
     *
     * @access public
     * @return Redirect::to
     */
    public function editrequest($id)
    {
        $user = Auth::user();
        $request = Requests::findOrFail($id);
        if ($user->group->is_modo || $user->id == $request->user_id) {
            // Post the Request
            if (Request::isMethod('post')) {
                // Find the right category
                $name = Request::get('name');
                $imdb = Request::get('imdb');
                $tvdb = Request::get('tvdb');
                $tmdb = Request::get('tmdb');
                $mal = Request::get('mal');
                $category = Request::get('category_id');
                $type = Request::get('type');
                $description = Request::get('description');

                $request->name = $name;
                $request->imdb = $imdb;
                $request->tvdb = $tvdb;
                $request->tmdb = $tmdb;
                $request->mal = $mal;
                $request->category_id = $category;
                $request->type = $type;
                $request->description = $description;
                $request->save();

                return Redirect::route('requests', ['id' => $request->id])->with(Toastr::success('Request Edited.', 'Successful', ['options']));
            } else {
                return view('requests.edit_request', ['categories' => Category::all(), 'types' => Type::all(), 'user' => $user, 'request' => $request]);
            }
        } else {
            return Redirect::route('requests', ['id' => $request->id])->with(Toastr::warning('You Dont Have Access To This Operation!', 'Error!', ['options']));
        }
    }

    /**
     * Add Bounty to a request
     *
     * @access public
     * @return Redirect::route
     */
    public function addBonus($id)
    {
        $user = Auth::user();

        if (Request::isMethod('POST') && $user->seedbonus >= 100) {
            $v = Validator::make(Request::all(), [
                'request_id' => "required|exists:requests,id",
                'bonus_value' => "required|numeric|max:{$user->seedbonus}",
            ]);

            if ($v->passes()) {
                $requests = Requests::findOrFail(Request::get('request_id'));

                $requests->votes += 1;
                $requests->bounty += Request::get('bonus_value');
                $requests->save();

                $requestsBounty = new RequestsBounty([
                    'user_id' => $user->id,
                    'seedbonus' => Request::get('bonus_value'),
                    'requests_id' => $requests->id,
                ]);
                $requestsBounty->save();

                $BonTransactions = new BonTransactions([
                    'itemID' => 0,
                    'name' => 'request',
                    'cost' => Request::get('bonus_value'),
                    'sender' => $user->id,
                    'receiver' => 0,
                    'comment' => "adding bonus to {$requests->name}"
                ]);
                $BonTransactions->save();

                $user->seedbonus -= Request::get('bonus_value');
                $user->save();

                $appurl = env('APP_URL', 'http://unit3d.site');
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has addded " . Request::get('bonus_value') . " BON bounty to request " . "[url={$appurl}/request/" . $requests->id . "]" . $requests->name . "[/url]"]);
                Cache::forget('shoutbox_messages');
                PrivateMessage::create(['sender_id' => "0", 'reciever_id' => $requests->user_id, 'subject' => "Your Request " . $requests->name . " Has A New Bounty!", 'message' => $user->username . " Has Added A Bounty To " . "[url={$appurl}/request/" . $requests->id . "]" . $requests->name . "[/url]"]);

                return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::success('Your bonus has been successfully added.', 'Bonus added', ['options']));
            } else {
                return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::error('You failed to adhere to the requirements.', 'Rookie Mistake', ['options']));
            }
        } else {
            return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::error('The server doesnt unserstand your request.', 'Try again later', ['options']));
        }
        return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::error('Something went horribly wrong', 'Try again later', ['options']));
    }

    /**
     * Fill a request
     * @method fillRequest
     *
     * @param $id ID of the request
     *
     */
    public function fillRequest($id)
    {
        $user = Auth::user();

        if (Request::isMethod('POST')) {
            $v = Validator::make(Request::all(), [
                'request_id' => "required|exists:requests,id",
                'info_hash' => "required|exists:torrents,info_hash",
            ]);

            if ($v->passes()) {
                $torrent = Torrent::where('info_hash', '=', Request::get('info_hash'))->firstOrFail();

                if ($user->id == $torrent->user_id) {
                    $this->addRequestModeration(Request::get('request_id'), Request::get('info_hash'));

                    return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::success('Your request fill is pending approval by the Requestor.', 'Approval required', ['options']));
                } elseif ($user->id != $torrent->user_id && Carbon::now()->addDay() > $torrent->created_at) {
                    $this->addRequestModeration(Request::get('request_id'), Request::get('info_hash'));

                    return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::success('Your request fill is pending approval by the Requestor.', 'Approval required', ['options']));
                } else {
                    return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::error('You cannot fill this request for some weird reason', 'The request filling system', ['options']));
                }
            } else {
                return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::error('You failed to adhere to the requirements.', 'Rookie Mistake', ['options']));
            }
        } else {
            return Redirect::route('request', ['id' => Request::get('request_id')])->with(Toastr::error('The server doesnt understand your request.', 'Try again later', ['options']));
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
        $user = Auth::user();

        $request = Requests::findOrFail($request_id);

        $request->filled_by = $user->id;
        $request->filled_hash = $info_hash;
        $request->filled_when = Carbon::now();

        $request->save();

        $appurl = env('APP_URL', 'http://unit3d.site');
        PrivateMessage::create(['sender_id' => "0", 'reciever_id' => $request->user_id, 'subject' => "Your Request " . $request->name . " Has Been Filled!", 'message' => $request->filled_by . " Has Filled Your Request [url={$appurl}/request/" . $request->id . "]" . $request->name . "[/url]" . " Please Approve or Decline The FullFill! "]);

    }

    /**
     * Approves the filled torrent on a request
     * @method approveRequest
     *
     */
    public function approveRequest($id)
    {
        $user = Auth::user();

        $request = Requests::findOrFail($id);

        if ($user->id == $request->user_id || Auth::user()->group->is_modo) {
            $request->approved_by = $user->id;
            $request->approved_when = Carbon::now();
            $request->save();

            //BON and torrent request hash code below
            $fill_user = User::findOrFail($request->filled_by);
            $fill_amount = $request->bounty;

            $BonTransactions = new BonTransactions([
                'itemID' => 0,
                'name' => 'request',
                'cost' => $fill_amount,
                'sender' => 0,
                'receiver' => $fill_user->id,
                'comment' => "{$fill_user->username} has filled {$request->name} and has been awared {$fill_amount} BONUS."
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

            $appurl = env('APP_URL', 'http://unit3d.site');
            Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $fill_user->username . "." . $fill_user->id . "]" . $fill_user->username . "[/url] has filled [url={$appurl}/request/" . $request->id . "]" . $request->name . "[/url] and was awarded " . $fill_amount . " BON "]);
            Cache::forget('shoutbox_messages');
            PrivateMessage::create(['sender_id' => "0", 'reciever_id' => $request->filled_by, 'subject' => "Your Request Fullfill On " . $request->name . " Has Been Approved!", 'message' => $request->approved_by . " Has Approved Your Fullfillment On [url={$appurl}/request/" . $request->id . "]" . $request->name . "[/url] Enjoy The " . $request->bounty . " Bonus Points!"]);
            return Redirect::route('request', ['id' => $id])->with(Toastr::success("You have approved {$request->name} and the bounty has been awarded to {$fill_user->username}", "Request completed!", ['options']));
        } else {
            return Redirect::route('request', ['id' => $id])->with(Toastr::error("You don't have access to approve this request", 'Permission denied', ['options']));
        }
    }

    /**
     * Rejects the filling torrent on a request
     * @method rejectRequest
     *
     */
    public function rejectRequest($id)
    {
        $user = Auth::user();

        $request = Requests::findOrFail($id);

        if ($user->id == $request->user_id) {
            $request->filled_by = null;
            $request->filled_when = null;
            $request->filled_hash = null;

            $request->save();

            return Redirect::route('request', ['id' => $id])->with(Toastr::success("This request has been reset.", 'Request Reset', ['options']));
        } else {
            return Redirect::route('request', ['id' => $id])->with(Toastr::success("You don't have access to approve this request", 'Permission denied', ['options']));
        }
    }

    /**
     * Deletes a specific request
     * @method deleteRequest
     *
     */
    public function deleteRequest($id)
    {
        $user = Auth::user();
        $request = Requests::findOrFail($id);

        if ($user->group->is_modo || $request->user_id == $user->id) {
            $name = $request->name;
            $request->delete();

            return Redirect::route('requests')->with(Toastr::success("You have deleted {$name}", 'Request Deleted', ['options']));
        } else {
            return Redirect::route('request', ['id' => $id])->with(Toastr::success("You don't have access to delete this request.", 'Permission denied', ['options']));
        }
    }

    /**
     * User can claim a specific request
     * @method claimRequest
     *
     */
    public function claimRequest($id)
    {
        $user = Auth::user();
        $request = Requests::findOrFail($id);

        if ($request->claimed == 0) {

            $requestClaim = new RequestsClaims([
                'request_id' => $id,
                'username' => $user->username,
                'anon' => Request::get('anon'),
            ]);
            $requestClaim->save();

            $request->claimed = 1;
            $request->save();

            return Redirect::route('request', ['id' => $id])->with(Toastr::success("Request Successfuly Claimed", 'Request Claimed', ['options']));
        } else {
            return Redirect::route('request', ['id' => $id])->with(Toastr::error("Someone else has already claimed this request buddy.", 'Whoops!', ['options']));
        }
    }

    /**
     * User can claim a specific request
     * @method claimRequest
     *
     */
    public function unclaimRequest($id)
    {
        $user = Auth::user();
        $request = Requests::findOrFail($id);
        $claimer = RequestsClaims::where('request_id', '=', $id)->first();

        if ($user->group->is_modo || $user->username == $claimer->username) {

            if ($request->claimed == 1) {

                $requestClaim = RequestsClaims::where('request_id', '=', $id)->firstOrFail();
                $requestClaim->delete();

                $request->claimed = 0;
                $request->save();

                return Redirect::route('request', ['id' => $id])->with(Toastr::success("Request Successfuly Un-Claimed", 'Request Claimed', ['options']));
            } else {
                return Redirect::route('request', ['id' => $id])->with(Toastr::error("Nothing To Unclaim.", 'Whoops!', ['options']));
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
