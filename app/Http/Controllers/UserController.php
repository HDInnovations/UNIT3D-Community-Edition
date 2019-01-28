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
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Image;
use App\Ban;
use App\Peer;
use App\User;
use App\Group;
use App\Client;
use App\Follow;
use App\Invite;
use ZipArchive;
use App\History;
use App\Torrent;
use App\Warning;
use App\Graveyard;
use Carbon\Carbon;
use App\PrivateMessage;
use App\BonTransactions;
use App\Services\Bencode;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * UserController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get Users List.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members()
    {
        $users = User::with('group')->latest()->paginate(50);

        return view('user.members', ['users' => $users]);
    }

    /**
     * Search For A User (Public Use).
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userSearch(Request $request)
    {
        $users = User::where([
            ['username', 'like', '%'.$request->input('username').'%'],
        ])->paginate(25);
        $users->setPath('?username='.$request->input('username'));

        return view('user.members')->with('users', $users);
    }

    /**
     * Get A User Profile.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($username, $id)
    {
        $user = User::findOrFail($id);
        $groups = Group::all();
        $followers = Follow::where('target_id', '=', $id)->get();
        $history = $user->history;
        $warnings = Warning::where('user_id', '=', $id)->whereNotNull('torrent')->where('active', '=', 1)->take(3)->get();
        $hitrun = Warning::where('user_id', '=', $id)->latest()->paginate(10);
        $bonupload = BonTransactions::where('sender', '=', $id)->where([['name', 'like', '%Upload%']])->sum('cost');
        $realupload = $user->uploaded - $bonupload;
        $bondownload = BonTransactions::where('sender', '=', $id)->where([['name', 'like', '%Download%']])->sum('cost');
        $realdownload = $user->downloaded + $bondownload;
        $invitedBy = Invite::where('accepted_by', '=', $user->id)->first();

        return view('user.profile', [
            'user'         => $user,
            'groups'       => $groups,
            'followers'    => $followers,
            'history'      => $history,
            'warnings'     => $warnings,
            'hitrun'       => $hitrun,
            'bonupload'    => $bonupload,
            'realupload'   => $realupload,
            'bondownload'  => $bondownload,
            'realdownload' => $realdownload,
            'invitedBy'    => $invitedBy,
        ]);
    }

    /**
     * Edit Profile Form.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfileForm($username, $id)
    {
        $user = auth()->user();

        return view('user.edit_profile', ['user' => $user]);
    }

    /**
     * Edit User Profile.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
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
                    $filename = $user->username.'.'.$image->getClientOriginalExtension();
                    $path = public_path('/files/img/'.$filename);
                    if ($image->getClientOriginalExtension() != 'gif') {
                        Image::make($image->getRealPath())->fit(150, 150)->encode('png', 100)->save($path);
                    } else {
                        $v = validator($request->all(), [
                            'image' => 'dimensions:ratio=1/1',
                        ]);
                        if ($v->passes()) {
                            $image->move(public_path('/files/img/'), $filename);
                        } else {
                            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                                ->with($this->toastr->error('Because you are uploading a GIF, your avatar must be symmetrical!', 'Whoops!', ['options']));
                        }
                    }
                    $user->image = $user->username.'.'.$image->getClientOriginalExtension();
                } else {
                    return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                        ->with($this->toastr->error('Your avatar is too large, max file size: '.($max_upload / 1000000).' MB', 'Whoops!', ['options']));
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

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
            ->with($this->toastr->success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
    }

    /**
     * User Account Settings.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings($username, $id)
    {
        $user = auth()->user();

        return view('user.settings', ['user' => $user]);
    }

    /**
     * Change User Account Settings.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function changeSettings(Request $request, $username, $id)
    {
        $user = auth()->user();
        // General Settings
        $user->censor = $request->input('censor');
        $user->chat_hidden = $request->input('chat_hidden');

        // Style Settings
        $user->style = (int) $request->input('theme');
        $css_url = $request->input('custom_css');
        if (isset($css_url) && filter_var($css_url, FILTER_VALIDATE_URL) === false) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.', 'Whoops!', ['options']));
        } else {
            $user->custom_css = $css_url;
        }
        $user->nav = $request->input('sidenav');

        // Privacy Settings
        $user->hidden = $request->input('onlinehide');
        $user->private_profile = $request->input('private_profile');
        $user->peer_hidden = $request->input('peer_hidden');

        // Torrent Settings
        $user->torrent_layout = (int) $request->input('torrent_layout');
        $user->show_poster = $request->input('show_poster');
        $user->ratings = $request->input('ratings');

        // Security Settings
        if (config('auth.TwoStepEnabled') == true) {
            $user->twostep = $request->input('twostep');
        }
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed there account settings.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
            ->with($this->toastr->success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
    }

    /**
     * User Password Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changePassword(Request $request)
    {
        $user = auth()->user();
        $v = validator($request->all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ]);
        if ($v->passes()) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->save();

                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has changed there account password.");

                return redirect('/')->with($this->toastr->success('Your Password Has Been Reset', 'Yay!', ['options']));
            } else {
                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                    ->with($this->toastr->error('Your Password Was Incorrect!', 'Whoops!', ['options']));
            }
        } else {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('Your New Password Is To Weak!', 'Whoops!', ['options']));
        }
    }

    /**
     * User Email Change.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeEmail(Request $request, $username, $id)
    {
        $user = auth()->user();

        if (config('email-white-blacklist.enabled') === 'allow') {
            $v = validator($request->all(), [
                'email' => 'required|email|unique:users|email_list:allow', // Whitelist
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            $v = validator($request->all(), [
                'email' => 'required|email|unique:users|email_list:block', // Blacklist
            ]);
        } else {
            $v = validator($request->all(), [
                'email' => 'required|email|unique:users', // Default
            ]);
        }

        if ($v->fails()) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $user->email = $request->input('email');
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has changed there email address on file.");

            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->success('Your Email Was Updated Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Change User PID.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function changePID(Request $request, $username, $id)
    {
        $user = auth()->user();
        $user->passkey = md5(uniqid().time().microtime());
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed there account PID.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
            ->with($this->toastr->success('Your PID Was Changed Successfully!', 'Yay!', ['options']));
    }

    /**
     * Get A Users Seedboxes/Clients.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clients($username, $id)
    {
        $user = auth()->user();
        $cli = Client::where('user_id', '=', $user->id)->get();

        return view('user.clients', ['user' => $user, 'clients' => $cli]);
    }

    /**
     * Add A Seedbox/Client.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function authorizeClient(Request $request, $username, $id)
    {
        $v = validator($request->all(), [
            'password'    => 'required',
            'ip'          => 'required|ipv4|unique:clients,ip',
            'client_name' => 'required|alpha_num',
        ]);

        $user = auth()->user();
        if ($v->passes()) {
            if (Hash::check($request->input('password'), $user->password)) {
                if (Client::where('user_id', '=', $user->id)->get()->count() >= config('other.max_cli')) {
                    return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])
                        ->with($this->toastr->error('Max Clients Reached!', 'Whoops!', ['options']));
                }
                $cli = new Client();
                $cli->user_id = $user->id;
                $cli->name = $request->input('client_name');
                $cli->ip = $request->input('ip');
                $cli->save();

                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has added a new seedbox to there account.");

                return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])
                    ->with($this->toastr->success('Client Has Been Added!', 'Yay', ['options']));
            } else {
                return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])
                    ->with($this->toastr->error('Password Invalid!', 'Whoops!', ['options']));
            }
        } else {
            return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('All required values not received or IP is already registered by a member.', 'Whoops!', ['options']));
        }
    }

    /**
     * Delete A Seedbox/Client.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function removeClient(Request $request, $username, $id)
    {
        $v = validator($request->all(), [
            'cliid'  => 'required|exists:clients,id',
            'userid' => 'required|exists:users,id',
        ]);

        $user = auth()->user();
        if ($v->passes()) {
            $cli = Client::where('id', '=', $request->input('cliid'));
            $cli->delete();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has removed a seedbox from there account.");

            return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->success('Client Has Been Removed!', 'Yay!', ['options']));
        } else {
            return redirect()->route('user_clients', ['username' => $user->username, 'id' => $user->id])
                ->with($this->toastr->error('Unable to remove this client.', 'Whoops!', ['options']));
        }
    }

    /**
     * Get A Users Warnings.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWarnings($username, $id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $user = User::findOrFail($id);
        $warnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('active')->paginate(25);
        $warningcount = Warning::where('user_id', '=', $id)->count();

        $softDeletedWarnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('created_at')->onlyTrashed()->paginate(25);
        $softDeletedWarningCount = Warning::where('user_id', '=', $id)->onlyTrashed()->count();

        return view('user.warninglog', [
            'warnings'                => $warnings,
            'warningcount'            => $warningcount,
            'softDeletedWarnings'     => $softDeletedWarnings,
            'softDeletedWarningCount' => $softDeletedWarningCount,
            'user'                    => $user,
        ]);
    }

    /**
     * Deactivate A Warning.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deactivateWarning($id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);
        $staff = auth()->user();
        $warning = Warning::findOrFail($id);
        $warning->expires_on = Carbon::now();
        $warning->active = 0;
        $warning->save();

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'Hit and Run Warning Deactivated';
        $pm->message = $staff->username.' has decided to deactivate your active warning for torrent '.$warning->torrent.' You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deactivated a warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->with($this->toastr->success('Warning Was Successfully Deactivated', 'Yay!', ['options']));
    }

    /**
     * Deactivate All Warnings.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deactivateAllWarnings($username, $id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);
        $staff = auth()->user();
        $user = User::findOrFail($id);

        $warnings = Warning::where('user_id', '=', $user->id)->get();

        foreach ($warnings as $warning) {
            $warning->expires_on = Carbon::now();
            $warning->active = 0;
            $warning->save();
        }

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'All Hit and Run Warning Deactivated';
        $pm->message = $staff->username.' has decided to deactivate all of your active hit and run warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deactivated all warnings on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->with($this->toastr->success('All Warnings Were Successfully Deactivated', 'Yay!', ['options']));
    }

    /**
     * Delete A Warning.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteWarning($id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();
        $warning = Warning::findOrFail($id);

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'Hit and Run Warning Deleted';
        $pm->message = $staff->username.' has decided to delete your warning for torrent '.$warning->torrent.' You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        $warning->deleted_by = $staff->id;
        $warning->save();
        $warning->delete();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deleted a warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->with($this->toastr->success('Warning Was Successfully Deleted', 'Yay!', ['options']));
    }

    /**
     * Delete All Warnings.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteAllWarnings($username, $id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();
        $user = User::findOrFail($id);

        $warnings = Warning::where('user_id', '=', $user->id)->get();

        foreach ($warnings as $warning) {
            $warning->deleted_by = $staff->id;
            $warning->save();
            $warning->delete();
        }

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'All Hit and Run Warnings Deleted';
        $pm->message = $staff->username.' has decided to delete all of your warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deleted all warnings on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->with($this->toastr->success('All Warnings Were Successfully Deleted', 'Yay!', ['options']));
    }

    /**
     * Restore A Soft Deleted Warning.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function restoreWarning($id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();
        $warning = Warning::findOrFail($id);
        $warning->restore();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has restore a soft deleted warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->with($this->toastr->success('Warning Was Successfully Restored', 'Yay!', ['options']));
    }

    /**
     * Get A Users Uploads.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myUploads($username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless(auth()->user()->group->is_modo || auth()->user()->id == $user->id, 403);
        $torrents = Torrent::withAnyStatus()->sortable(['created_at' => 'desc'])->where('user_id', '=', $user->id)->paginate(50);

        return view('user.uploads', ['user' => $user, 'torrents' => $torrents]);
    }

    /**
     * Get A Users Active Table.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myActive($username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless(auth()->user()->group->is_modo || auth()->user()->id == $user->id, 403);
        $active = Peer::with(['torrent' => function ($query) {
            $query->withAnyStatus();
        }])->sortable(['created_at' => 'desc'])
            ->where('user_id', '=', $user->id)
            ->distinct('hash')
            ->paginate(50);

        return view('user.active', ['user' => $user, 'active' => $active]);
    }

    /**
     * Uses Input's To Put Together A Filtered View.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return array
     */
    public function myFilter(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        abort_unless(auth()->user()->group->is_modo || auth()->user()->id == $user->id, 403);

        if ($request->has('view') && $request->input('view') == 'history') {
            $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
            $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
            $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
            $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');
            $history = History::with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->leftJoin('torrents as torrents', 'torrents.info_hash', '=', 'history.info_hash');

            $order = null;
            $sorting = null;
            if ($request->has('sorting') && $request->input('sorting') != null) {
                $sorting = $request->input('sorting');
            }
            if ($request->has('direction') && $request->input('direction') != null) {
                $order = $request->input('direction');
            }
            if (! $sorting || $sorting == null || ! $order || $order == null) {
                $sorting = 'created_at';
                $order = 'desc';
                // $order = 'asc';
            }
            if ($order == 'asc') {
                $direction = 1;
            } else {
                $direction = 2;
            }

            if ($request->has('completed') && $request->input('completed') != null) {
                $history->where('completed_at', '>', 0);
            }

            if ($request->has('active') && $request->input('active') != null) {
                $history->where('active', '=', 1);
            }

            if ($request->has('seeding') && $request->input('seeding') != null) {
                $history->where('seeder', '=', 1);
            }

            if ($request->has('prewarned') && $request->input('prewarned') != null) {
                $history->where('prewarn', '=', 1);
            }

            if ($request->has('hr') && $request->input('hr') != null) {
                $history->where('hitrun', '=', 1);
            }

            if ($request->has('immune') && $request->input('immune') != null) {
                $history->where('immune', '=', 1);
            }

            if ($sorting != 'name') {
                $table = $history->where('history.user_id', '=', $user->id)->orderBy('history.'.$sorting, $order)->paginate(50);
            } else {
                $table = $history->where('history.user_id', '=', $user->id)->orderBy('torrents.'.$sorting, $order)->paginate(50);
            }

            return view('user.filters', [
                'user' => $user,
                'history' => $table,
                'his_upl' => $his_upl,
                'his_upl_cre' => $his_upl_cre,
                'his_downl' => $his_downl,
                'his_downl_cre' => $his_downl_cre,
            ])->render();
        }

        return false;
    }

    /**
     * Get A Users History Table.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myHistory($username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless(auth()->user()->group->is_modo || auth()->user()->id == $user->id, 403);
        $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
        $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
        $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
        $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');
        $history = History::with(['torrent' => function ($query) {
            $query->withAnyStatus();
        }])->sortable(['created_at' => 'desc'])
            ->where('user_id', '=', $user->id)
            ->paginate(50);

        return view('user.history', [
            'user'          => $user,
            'history'       => $history,
            'his_upl'       => $his_upl,
            'his_upl_cre'   => $his_upl_cre,
            'his_downl'     => $his_downl,
            'his_downl_cre' => $his_downl_cre,
        ]);
    }

    /**
     * Search A User's Uploads.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myUploadsSearch(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless(auth()->user()->group->is_modo || auth()->user()->id == $user->id, 403);
        $torrents = Torrent::withAnyStatus()->sortable(['created_at' => 'desc'])
            ->where('user_id', '=', $user->id)
            ->where('name', 'like', '%'.$request->input('name').'%')
            ->paginate(50);

        return view('user.uploads', ['user' => $user, 'torrents' => $torrents]);
    }

    /**
     * Download All History Torrents.
     *
     * @param $username
     * @param $id
     *
     * @return \ZipArchive
     */
    public function downloadHistoryTorrents($username, $id)
    {
        //  Extend The Maximum Execution Time
        set_time_limit(300);

        // Authorized User
        $user = User::findOrFail($id);

        // User's ratio is too low
        if ($user->getRatio() < config('other.ratio')) {
            return back()->with($this->toastr->error('Your Ratio Is To Low To Download!!!', 'Whoops!', ['options']));
        }

        // User's download rights are revoked
        if ($user->can_download == 0) {
            return back()->with($this->toastr->error('Your Download Rights Have Been Revoked!!!', 'Whoops!', ['options']));
        }

        abort_unless(auth()->user()->id == $user->id, 403);
        // Define Dir Folder
        $path = getcwd().'/files/tmp_zip/';

        // Zip File Name
        $zipFileName = "{$user->username}.zip";

        // Create ZipArchive Obj
        $zip = new ZipArchive();

        // Get Users History
        $historyTorrents = History::where('user_id', '=', $user->id)->pluck('info_hash');

        if ($zip->open($path.'/'.$zipFileName, ZipArchive::CREATE) === true) {
            // Match History Results To Torrents
            foreach ($historyTorrents as $historyTorrent) {
                // Get Torrent
                $torrent = Torrent::withAnyStatus()->where('info_hash', '=', $historyTorrent)->first();

                // Define The Torrent Filename
                $tmpFileName = "{$torrent->slug}.torrent";

                // The Torrent File Exist?
                if (! file_exists(getcwd().'/files/torrents/'.$torrent->file_name)) {
                    return back()->with($this->toastr->error('Torrent File Not Found! Please Report This Torrent!', 'Error!', ['options']));
                } else {
                    // Delete The Last Torrent Tmp File If Exist
                    if (file_exists(getcwd().'/files/tmp/'.$tmpFileName)) {
                        unlink(getcwd().'/files/tmp/'.$tmpFileName);
                    }
                }

                // Get The Content Of The Torrent
                $dict = Bencode::bdecode(file_get_contents(getcwd().'/files/torrents/'.$torrent->file_name));
                // Set the announce key and add the user passkey
                $dict['announce'] = route('announce', ['passkey' => $user->passkey]);
                // Remove Other announce url
                unset($dict['announce-list']);

                $fileToDownload = Bencode::bencode($dict);
                file_put_contents(getcwd().'/files/tmp/'.$tmpFileName, $fileToDownload);

                // Add Files To ZipArchive
                $zip->addFile(getcwd().'/files/tmp/'.$tmpFileName, $tmpFileName);
            }
            // Close ZipArchive
            $zip->close();
        }

        $zip_file = $path.'/'.$zipFileName;

        if (file_exists($zip_file)) {
            return response()->download($zip_file)->deleteFileAfterSend(true);
        } else {
            return back()->with($this->toastr->error('Something Went Wrong!', 'Whoops!', ['options']));
        }
    }

    /**
     * Get A Users Graveyard Resurrections.
     *
     * @param $username
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myResurrections($username, $id)
    {
        $user = User::findOrFail($id);
        abort_unless(auth()->user()->group->is_modo || auth()->user()->id == $user->id, 403);

        $resurrections = Graveyard::with(['torrent', 'user'])->where('user_id', '=', $user->id)->paginate(25);

        return view('user.resurrections', [
            'user' => $user,
            'resurrections' => $resurrections,
        ]);
    }

    /**
     * Get A Users Bans.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBans($username, $id)
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $user = User::findOrFail($id);
        $bans = Ban::where('owned_by', '=', $user->id)->latest()->get();

        return view('user.banlog', [
            'user'      => $user,
            'bans'  => $bans,
        ]);
    }
}
