<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Group;
use App\Mail\InviteUser;
use App\User;
use App\Category;
use App\Peer;
use App\Torrent;
use App\TorrentFile;
use App\Comment;
use App\Client;
use App\Shoutbox;
use App\Post;
use App\Topic;
use App\PrivateMessage;
use App\Follow;
use App\History;
use App\Warning;
use App\Note;
use Carbon\Carbon;

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use Illuminate\Support\Facades\Input;

use Cache;
use \Toastr;
use Image;
use Carbon\Cabon;

/**
 * User Management
 *
 *
 *
 */
class UserController extends Controller
{
    /**
     * Get Members List
     *
     * @access public
     * @return view users.members
     */
    public function members()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(50);

        return view('user.members', ['users' => $users]);
    }

    /**
     * Search for members (member use)
     *
     * @access public
     *
     */
    public function userSearch()
    {
        $search = Request::get('search');
        $users = User::where([
            ['username', 'like', '%' . Request::get('username') . '%'],
        ])->paginate(25);
        $users->setPath('?username=' . Request::get('username'));
        return view('user.members')->with('users', $users);
    }

    /**
     * Get User Profile
     *
     * @access public
     * @return view user.profile
     */
    public function profil($username, $id)
    {
        $user = User::findOrFail($id);
        $groups = Group::all();
        $hiscount = History::where('user_id', '=', $id)->count();
        $seedtime = History::where('user_id', '=', $id)->sum('seedtime');

        $num_uploads = Torrent::where('user_id', '=', $id)->count();
        $num_downloads = History::where('user_id', '=', $id)->where('actual_downloaded', '>', 0)->count();
        $achievements = $user->unlockedAchievements();
        $followers = Follow::where('target_id', '=', $id)->get();
        $tor_comments = Comment::where('user_id', '=', $id)->where('torrent_id', '>', 0)->count();
        $art_comments = Comment::where('user_id', '=', $id)->where('article_id', '>', 0)->count();
        $req_comments = Comment::where('user_id', '=', $id)->where('requests_id', '>', 0)->count();
        $topics = Topic::where('first_post_user_id', '=', $id)->count();
        $posts = Post::where('user_id', '=', $id)->count();
        $warnings = Warning::where('user_id', '=', $id)->whereNotNull('torrent')->where('active', '=', '1')->take(3)->get();
        $hitrun = Warning::where('user_id', '=', $id)->orderBy('created_at', 'DESC')->get();
        $notes = Note::where('user_id', '=', $id)->count();

        return view('user.profil', ['user' => $user, 'groups' => $groups, 'num_uploads' => $num_uploads, 'num_downloads' => $num_downloads, 'achievements' => $achievements, 'followers' => $followers, 'notes' => $notes,
            'seedtime' => $seedtime, 'hiscount' => $hiscount, 'tor_comments' => $tor_comments, 'art_comments' => $art_comments, 'req_comments' => $req_comments, 'topics' => $topics, 'posts' => $posts, 'warnings' => $warnings, 'hitrun' => $hitrun]);
    }

    /**
     * Edit User Profile
     *
     * @access public
     * @return void
     *
     */
    public function editProfil($username, $id)
    {
        $user = Auth::user();
        // Requetes post only
        if (Request::isMethod('post')) {
            // Avatar
            if (Request::hasFile('image')) {
                $image = Request::file('image');
                if (in_array($image->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'bmp', 'png', 'PNG', 'tiff', 'gif', 'GIF']) && preg_match('#image/*#', $image->getMimeType())) {
                    $filename = $user->username . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/files/img/' . $filename);
                    Image::make($image->getRealPath())->fit(150, 150)->save($path);
                    $user->image = $user->username . '.' . $image->getClientOriginalExtension();
                }
            }
            // Define data
            $user->title = Request::get('title');
            $user->about = Request::get('about');
            $user->signature = Request::get('signature');
            // Save the user
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member " . $user->username . " has updated there profile.");

            return Redirect::route('profil', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
        }

        return view('user.edit_profil', ['user' => $user]);
    }

    /**
     * User Account Settings
     *
     * @access public
     * @return view user.settings
     */
    public function settings($username, $id)
    {
        $user = Auth::user();
        return view('user.settings', ['user' => $user]);
    }

    /**
     * Change User Account Settings
     *
     * @access public
     * @return view user.settings
     */
    public function changeSettings($username, $id)
    {
        $user = Auth::user();
        if (Request::isMethod('post')) {
            $user->style = (int)Request::get('theme');
            $css_url = Request::get('custom_css');
            if (isset($css_url) && filter_var($css_url, FILTER_VALIDATE_URL) === false) {
                return redirect()->back()->with(Toastr::warning('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.', 'Error', ['options']));
            } else {
                $user->custom_css = $css_url;
            }

            $user->nav = Request::get('sidenav');
            $user->hidden = Request::get('onlinehide');
            $user->private_profile = Request::get('private_profile');
            $user->peer_hidden = Request::get('peer_hidden');
            $user->show_poster = Request::get('show_poster');
            $user->ratings = Request::get('ratings');
            if(config('auth.TwoStepEnabled') == true) {
            $user->twostep = Request::get('twostep');
            }
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member " . $user->username . " has changed there account settings.");

            return Redirect::route('profil', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->back()->with(Toastr::warning('Something Went Wrong!', 'Error', ['options']));
        }
    }

    /**
     * User Password Change
     *
     * @access protected
     *
     */
    protected function changePassword(IlluminateRequest $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ]);
        $usr = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->current_password, $usr->password)) {
            $usr->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            return redirect('/login')->with(Toastr::success('Your Password Has Been Reset', 'Success!', ['options']));
        } else {
            return redirect()->back()->with(Toastr::warning('Your Password Was Incorrect!', 'Error', ['options']));
        }
    }

    /**
     * User Email Change
     *
     * @access protected
     *
     */
    protected function changeEmail($username, $id)
    {
        $user = Auth::user();
        $v = Validator::make(Request::all(), [
            'current_password' => 'required',
            'new_email' => 'required',
        ]);
        if ($v->passes()) {
            if (Request::isMethod('post')) {
                $user->email = Request::get('new_email');
                $user->save();

                // Activity Log
                \LogActivity::addToLog("Member " . $user->username . " has changed there email address on file.");

                return Redirect::route('profil', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Email Was Updated Successfully!', 'Yay!', ['options']));
            } else {
                return redirect()->back()->with(Toastr::warning('Your Password Was Incorrect!', 'Error', ['options']));
            }
        }
    }

    /**
     * Change User PID
     *
     * @access public
     * @return view user.settings
     */
    public function changePID($username, $id)
    {
        $user = Auth::user();
        if (Request::isMethod('post')) {
            $user->passkey = md5(uniqid() . time() . microtime());
            $user->save();
            return Redirect::route('profil', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your PID Was Changed Successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->back()->with(Toastr::warning('Something Went Wrong!', 'Error', ['options']));
        }
    }

    /**
     * My SeedBoxes
     *
     *
     * @access public
     * @param $id Id User
     * @return view::make user.clients
     */
    public function clients($username, $id)
    {
        $user = Auth::user();
        $cli = Client::where('user_id', '=', $user->id)->get();
        return view('user.clients', ['user' => $user, 'clients' => $cli]);
    }

    protected function authorizeClient($username, $id)
    {
        $v = Validator::make(Request::all(), [
            'password' => 'required',
            'ip' => 'required|ipv4|unique:clients,ip',
            'client_name' => 'required|alpha_num',
        ]);

        $user = Auth::user();
        if ($v->passes()) {
            if (Hash::check(Request::get('password'), $user->password)) {
                if (Client::where('user_id', '=', $user->id)->get()->count() >= config('other.max_cli')) {
                    return Redirect::route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Max Clients Reached!', 'Sorry', ['options']));
                }
                $cli = new Client;
                $cli->user_id = $user->id;
                $cli->name = Request::get('client_name');
                $cli->ip = Request::get('ip');
                $cli->save();
                return Redirect::route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Client Has Been Added!', 'Yay', ['options']));
            } else {
                return Redirect::route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Password Invalid!', 'Sorry', ['options']));
            }
        } else {
            return Redirect::route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('All required values not received or IP is already registered by a member.', 'Hmm!', ['options']));
        }
    }

    protected function removeClient($username, $id)
    {
        $v = Validator::make(Request::all(), [
            'cliid' => 'required|exists:clients,id',
            'userid' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        if ($v->passes()) {
            $cli = Client::where('id', '=', Request::get('cliid'));
            $cli->delete();
            return Redirect::route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Client Has Been Removed!', 'Yay', ['options']));
        } else {
            return Redirect::route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Unable to remove this client.', 'Whoops, something went wrong', ['options']));
        }
    }

    public function getWarnings($username, $id)
    {
        if (Auth::user()->group->is_modo) {
            $user = User::findOrFail($id);
            $warnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->orderBy('active', 'DESC')->paginate(25);
            $warningcount = Warning::where('user_id', '=', $id)->count();

            return view('user.warninglog', ['warnings' => $warnings, 'warningcount' => $warningcount, 'user' => $user]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function deactivateWarning($id)
    {
        if (Auth::user()->group->is_modo) {
            $staff = Auth::user();
            $warning = Warning::findOrFail($id);
            $warning->expires_on = Carbon::now();
            $warning->active = 0;
            $warning->save();
            PrivateMessage::create(['sender_id' => $staff->id, 'reciever_id' => $warning->user_id, 'subject' => "Hit and Run Warning Deactivated", 'message' => $staff->username . " has decided to deactivate your warning for torrent " . $warning->torrent . " You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);

            return Redirect::route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])->with(Toastr::success('Warning Was Successfully Deactivated', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function myUploads($username, $id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->group->is_modo || Auth::user()->id == $user->id) {
            $torrents = Torrent::withAnyStatus()->sortable(['created_at' => 'desc'])->where('user_id', '=', $user->id)->paginate(50);
            return view('user.uploads', ['user' => $user, 'torrents' => $torrents]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function myActive($username, $id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->group->is_modo || Auth::user()->id == $user->id) {
            $active = Peer::sortable(['created_at' => 'desc'])->where('user_id', '=', $user->id)->with('torrent')->distinct('hash')->paginate(50);
            return view('user.active', ['user' => $user, 'active' => $active]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function myHistory($username, $id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->group->is_modo || Auth::user()->id == $user->id) {
            $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
            $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
            $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
            $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');
            $history = History::sortable(['created_at' => 'desc'])->where('user_id', '=', $user->id)->paginate(50);
            return view('user.history', ['user' => $user, 'history' => $history, 'his_upl' => $his_upl, 'his_upl_cre' => $his_upl_cre, 'his_downl' => $his_downl, 'his_downl_cre' => $his_downl_cre]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
