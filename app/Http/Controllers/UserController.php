<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Group;
use App\User;
use App\Peer;
use App\Torrent;
use App\Client;
use App\PrivateMessage;
use App\Follow;
use App\History;
use App\Warning;
use \Toastr;
use Image;
use Carbon\Carbon;

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
        $users = User::latest()->paginate(50);

        return view('user.members', ['users' => $users]);
    }

    /**
     * Search for members (member use)
     *
     * @access public
     *
     */
    public function userSearch(Request $request)
    {
        $search = $request->input('search');
        $users = User::where([
            ['username', 'like', '%' . $request->input('username') . '%'],
        ])->paginate(25);
        $users->setPath('?username=' . $request->input('username'));
        return view('user.members')->with('users', $users);
    }

    /**
     * Get User Profile
     *
     * @access public
     * @return view user.profile
     */
    public function profile($username, $id)
    {
        $user = User::findOrFail($id);
        $groups = Group::all();
        $followers = Follow::where('target_id', $id)->get();
        $history = $user->history;
        $warnings = Warning::where('user_id', $id)->whereNotNull('torrent')->where('active', 1)->take(3)->get();
        $hitrun = Warning::where('user_id', $id)->latest()->paginate(10);

        return view('user.profile', ['user' => $user, 'groups' => $groups, 'followers' => $followers, 'history' => $history, 'warnings' => $warnings, 'hitrun' => $hitrun]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfileForm($username, $id)
    {
        $user = auth()->user();
        return view('user.edit_profile', ['user' => $user]);
    }

    /**
     * Edit User Profile
     *
     * @access public
     * @return void
     *
     */
    public function editProfile(Request $request, $username, $id)
    {
        $user = auth()->user();
        // Avatar
        $max_upload = config('image.max_upload_size');
        if ($request->hasFile('image') && $request->file('image')->getError() == 0) {
            $image = $request->file('image');
            if (in_array($image->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'bmp', 'png', 'PNG', 'tiff', 'gif']) && preg_match('#image/*#', $image->getMimeType())) {
                if ($max_upload >= $image->getSize()) {
                    $filename = $user->username . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/files/img/' . $filename);
                    if ($image->getClientOriginalExtension() != 'gif') {
                        Image::make($image->getRealPath())->fit(150, 150)->encode('png', 100)->save($path);
                    } else {
                        $v = validator($request->all(), [
                            'image' => 'dimensions:ratio=1/1'
                        ]);
                        if ($v->passes()) {
                            $image->move(public_path('/files/img/'), $filename);
                        } else {
                            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Because you are uploading a GIF, your avatar must be symmetrical!', 'Whoops!', ['options']));
                        }
                    }
                    $user->image = $user->username . '.' . $image->getClientOriginalExtension();
                } else {
                    return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Your avatar is too large, max file size: ' . ($max_upload / 1000000) . ' MB', 'Whoops!', ['options']));
                }
            }
        }
        // Define data
        $user->title = $request->input('title');
        $user->about = $request->input('about');
        $user->signature = $request->input('signature');
        // Save the user
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has updated there profile.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
    }


    /**
     * User Account Settings
     *
     * @access public
     * @return view user.settings
     */
    public function settings($username, $id)
    {
        $user = auth()->user();
        return view('user.settings', ['user' => $user]);
    }

    /**
     * Change User Account Settings
     *
     * @access public
     * @return view user.settings
     */
    public function changeSettings(Request $request, $username, $id)
    {
        $user = auth()->user();
        // General Settings
        $user->censor = $request->input('censor');
        $user->chat_hidden = $request->input('chat_hidden');

        // Style Settings
        $user->style = (int)$request->input('theme');
        $css_url = $request->input('custom_css');
        if (isset($css_url) && filter_var($css_url, FILTER_VALIDATE_URL) === false) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.', 'Whoops!', ['options']));
        } else {
            $user->custom_css = $css_url;
        }
        $user->nav = $request->input('sidenav');

        // Privacy Settings
        $user->hidden = $request->input('onlinehide');
        $user->private_profile = $request->input('private_profile');
        $user->peer_hidden = $request->input('peer_hidden');

        // Torrent Settings
        $user->torrent_layout = (int)$request->input('torrent_layout');
        $user->show_poster = $request->input('show_poster');
        $user->ratings = $request->input('ratings');

        // Security Settings
        if (config('auth.TwoStepEnabled') == true) {
            $user->twostep = $request->input('twostep');
        }
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed there account settings.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
    }

    /**
     * User Password Change
     *
     * @access protected
     *
     */
    protected function changePassword(Request $request)
    {
        $user = auth()->user();
        $v = validator($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ]);
        if ($v->passes()) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();

                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has changed there account password.");

                return redirect('/')->with(Toastr::success('Your Password Has Been Reset', 'Yay!', ['options']));
            } else {
                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Your Password Was Incorrect!', 'Whoops!', ['options']));
            }
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Your New Password Is To Weak!', 'Whoops!', ['options']));
        }
    }

    /**
     * User Email Change
     *
     * @access protected
     *
     */
    protected function changeEmail(Request $request, $username, $id)
    {
        $user = auth()->user();
        $v = validator($request->all(), [
            'current_password' => 'required',
            'new_email' => 'required',
        ]);
        if ($v->passes()) {
            $user->email = $request->input('new_email');
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has changed there email address on file.");

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Email Was Updated Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Change User PID
     *
     * @access public
     * @return view user.settings
     */
    public function changePID(Request $request, $username, $id)
    {
        $user = auth()->user();
        $user->passkey = md5(uniqid() . time() . microtime());
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed there account PID.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your PID Was Changed Successfully!', 'Yay!', ['options']));
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
        $user = auth()->user();
        $cli = Client::where('user_id', $user->id)->get();
        return view('user.clients', ['user' => $user, 'clients' => $cli]);
    }

    protected function authorizeClient(Request $request, $username, $id)
    {
        $v = validator($request->all(), [
            'password' => 'required',
            'ip' => 'required|ipv4|unique:clients,ip',
            'client_name' => 'required|alpha_num',
        ]);

        $user = auth()->user();
        if ($v->passes()) {
            if (Hash::check($request->input('password'), $user->password)) {
                if (Client::where('user_id', $user->id)->get()->count() >= config('other.max_cli')) {
                    return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Max Clients Reached!', 'Whoops!', ['options']));
                }
                $cli = new Client;
                $cli->user_id = $user->id;
                $cli->name = $request->input('client_name');
                $cli->ip = $request->input('ip');
                $cli->save();

                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has added a new seedbox to there account.");

                return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Client Has Been Added!', 'Yay', ['options']));
            } else {
                return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Password Invalid!', 'Whoops!', ['options']));
            }
        } else {
            return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('All required values not received or IP is already registered by a member.', 'Whoops!', ['options']));
        }
    }

    protected function removeClient(Request $request, $username, $id)
    {
        $v = validator($request->all(), [
            'cliid' => 'required|exists:clients,id',
            'userid' => 'required|exists:users,id',
        ]);

        $user = auth()->user();
        if ($v->passes()) {
            $cli = Client::where('id', $request->input('cliid'));
            $cli->delete();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has removed a seedbox from there account.");

            return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Client Has Been Removed!', 'Yay!', ['options']));
        } else {
            return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('Unable to remove this client.', 'Whoops!', ['options']));
        }
    }

    public function getWarnings($username, $id)
    {
        if (auth()->user()->group->is_modo) {
            $user = User::findOrFail($id);
            $warnings = Warning::where('user_id', $user->id)->with(['torrenttitle', 'warneduser'])->latest('active')->paginate(25);
            $warningcount = Warning::where('user_id', $id)->count();

            return view('user.warninglog', ['warnings' => $warnings, 'warningcount' => $warningcount, 'user' => $user]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function deactivateWarning($id)
    {
        if (auth()->user()->group->is_modo) {
            $staff = auth()->user();
            $warning = Warning::findOrFail($id);
            $warning->expires_on = Carbon::now();
            $warning->active = 0;
            $warning->save();

            // Send Private Message
            PrivateMessage::create(['sender_id' => $staff->id, 'reciever_id' => $warning->user_id, 'subject' => "Hit and Run Warning Deactivated", 'message' => $staff->username . " has decided to deactivate your warning for torrent " . $warning->torrent . " You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has deactivated a warning on {$warning->warneduser->username} account.");

            return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])->with(Toastr::success('Warning Was Successfully Deactivated', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function myUploads($username, $id)
    {
        $user = User::findOrFail($id);
        if (auth()->user()->group->is_modo || auth()->user()->id == $user->id) {
            $torrents = Torrent::withAnyStatus()->sortable(['created_at' => 'desc'])->where('user_id', $user->id)->paginate(50);
            return view('user.uploads', ['user' => $user, 'torrents' => $torrents]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function myActive($username, $id)
    {
        $user = User::findOrFail($id);
        if (auth()->user()->group->is_modo || auth()->user()->id == $user->id) {
            $active = Peer::sortable(['created_at' => 'desc'])->where('user_id', $user->id)->with('torrent')->distinct('hash')->paginate(50);
            return view('user.active', ['user' => $user, 'active' => $active]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function myHistory($username, $id)
    {
        $user = User::findOrFail($id);
        if (auth()->user()->group->is_modo || auth()->user()->id == $user->id) {
            $his_upl = History::where('user_id', $id)->sum('actual_uploaded');
            $his_upl_cre = History::where('user_id', $id)->sum('uploaded');
            $his_downl = History::where('user_id', $id)->sum('actual_downloaded');
            $his_downl_cre = History::where('user_id', $id)->sum('downloaded');
            $history = History::sortable(['created_at' => 'desc'])->where('user_id', $user->id)->paginate(50);
            return view('user.history', ['user' => $user, 'history' => $history, 'his_upl' => $his_upl, 'his_upl_cre' => $his_upl_cre, 'his_downl' => $his_downl, 'his_downl_cre' => $his_downl_cre]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}