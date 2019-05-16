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
use ZipArchive;
use Carbon\Carbon;
use App\Models\Ban;
use App\Models\Peer;
use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Topic;
use App\Models\Follow;
use App\Models\Invite;
use App\Models\History;
use App\Models\Torrent;
use App\Models\Warning;
use App\Helpers\Bencode;
use App\Models\Graveyard;
use App\Models\UserPrivacy;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use App\Models\TorrentRequest;
use App\Models\BonTransactions;
use App\Models\UserNotification;
use App\Models\PersonalFreeleech;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($slug, $id)
    {
        $user = User::with(['privacy', 'history'])->findOrFail($id);

        $groups = Group::all();
        $followers = Follow::where('target_id', '=', $id)->latest()->limit(25)->get();
        $history = $user->history;
        $warnings = Warning::where('user_id', '=', $id)->whereNotNull('torrent')->where('active', '=', 1)->take(3)->get();
        $hitrun = Warning::where('user_id', '=', $id)->latest()->paginate(10);

        $bonupload = BonTransactions::where('sender', '=', $id)->where([['name', 'like', '%Upload%']])->sum('cost');
        $bondownload = BonTransactions::where('sender', '=', $id)->where([['name', 'like', '%Download%']])->sum('cost');

        $realupload = $user->uploaded - $bonupload;
        $realdownload = $user->downloaded + $bondownload;

        $invitedBy = Invite::where('accepted_by', '=', $user->id)->first();

        $requested = TorrentRequest::where('user_id', '=', $user->id)->count();
        $filled = TorrentRequest::where('filled_by', '=', $user->id)->whereNotNull('approved_by')->count();

        return view('user.profile', [
            'route'        => 'profile',
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
            'requested'    => $requested,
            'filled'       => $filled,
            'invitedBy'    => $invitedBy,
        ]);
    }

    /**
     * User Followers.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param $slug
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers(Request $request, $slug, int $id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();
        $results = Follow::with('user')->where('target_id', '=', $id)->latest()->paginate(25);

        return view('user.followers', [
                'route' => 'follower',
                'results' => $results,
                'user' => $user,
            ]);
    }

    /**
     * User Topics.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param $slug
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topics(Request $request, $slug, int $id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();
        $results = Topic::where('topics.first_post_user_id', '=', $user->id)->latest()->paginate(25);

        return view('user.topics', [
                'route' => 'forum',
                'results' => $results,
                'user' => $user,
            ]);
    }

    /**
     * User Posts.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param $slug
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function posts(Request $request, $slug, int $id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();
        $results = Post::selectRaw('posts.id as id,posts.*')->with(['topic', 'user'])->leftJoin('topics', 'posts.topic_id', '=', 'topics.id')->where('posts.user_id', '=', $user->id)->orderBy('posts.created_at', 'desc')->paginate(25);

        return view('user.posts', [
                'route' => 'forum',
                'results' => $results,
                'user' => $user,
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
    public function editProfileForm(Request $request, $username, $id)
    {
        abort_unless($request->user()->id == $id, 403);
        $user = $request->user();

        return view('user.edit_profile', ['user' => $user, 'route' => 'edit']);
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
        abort_unless($request->user()->id == $id, 403);
        $user = $request->user();
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
                                ->withErrors('Because you are uploading a GIF, your avatar must be symmetrical!');
                        }
                    }
                    $user->image = $user->username.'.'.$image->getClientOriginalExtension();
                } else {
                    return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                        ->withErrors('Your avatar is too large, max file size: '.($max_upload / 1000000).' MB');
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

        return redirect()->route('user_edit_profile_form', ['username' => $user->slug, 'id' => $user->id])
            ->withSuccess('Your Account Was Updated Successfully!');
    }

    /**
     * User Account Settings.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings(Request $request, $slug, $id)
    {
        abort_unless($request->user()->id == $id, 403);
        $user = $request->user();

        return view('user.settings', ['user' => $user, 'route' => 'settings']);
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
        abort_unless($request->user()->id == $id, 403);
        $user = $request->user();

        // General Settings
        $user->censor = $request->input('censor');
        $user->chat_hidden = $request->input('chat_hidden');

        // Style Settings
        $user->style = (int) $request->input('theme');
        $css_url = $request->input('custom_css');
        if (isset($css_url) && filter_var($css_url, FILTER_VALIDATE_URL) === false) {
            return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
                ->withErrors('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.');
        } else {
            $user->custom_css = $css_url;
        }
        $user->nav = $request->input('sidenav');

        // Torrent Settings
        $user->torrent_layout = (int) $request->input('torrent_layout');
        $user->show_poster = $request->input('show_poster');
        $user->ratings = $request->input('ratings');

        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed their account settings.");

        return redirect()->route('user_settings', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('Your Account Was Updated Successfully!');
    }

    /**
     * User Security Settings.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security(Request $request, $slug, $id)
    {
        $user = $request->user();

        return view('user.security', ['user' => $user]);
    }

    /**
     * User TwoStep Auth.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeTwoStep(Request $request)
    {
        $user = auth()->user();

        abort_unless(config('auth.TwoStepEnabled') == true, 403);
        $user->twostep = $request->input('twostep');
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Changed Your TwoStep Auth Status!');
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

                return redirect()->to('/')->withSuccess('Your Password Has Been Reset');
            } else {
                return redirect()->route('user_security', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#password'])
                    ->withErrors('Your Password Was Incorrect!');
            }
        } else {
            return redirect()->route('user_security', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#password'])
                ->withErrors('Your New Password Is To Weak!');
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
            return redirect()->route('user_security', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#email'])
                ->withErrors($v->errors());
        } else {
            $user->email = $request->input('email');
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has changed there email address on file.");

            return redirect()->route('user_security', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#email'])
                ->withSuccess('Your Email Was Updated Successfully!');
        }
    }

    /**
     * Change User Privacy Level.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function makePrivate(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->private_profile = 1;
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Have Gone Private!');
    }

    /**
     * Change User Privacy Level.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function makePublic(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->private_profile = 0;
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Have Gone Public!');
    }

    /**
     * Change User Notification Setting.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function disableNotifications(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->block_notifications = 1;
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Have Disabled Notifications!');
    }

    /**
     * Change User Notification Setting.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function enableNotifications(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->block_notifications = 0;
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Have Enabled Notifications!');
    }

    /**
     * Change User Hidden Value.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function makeHidden(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->hidden = 1;
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Have Disappeared Like A Ninja!');
    }

    /**
     * Change User Hidden Value.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function makeVisible(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->hidden = 0;
        $user->save();

        return redirect()->route('user_profile', ['slug' => $user->slug, 'id' => $user->id])
            ->withSuccess('You Have Given Up Your Ninja Ways And Become Visible!');
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
        $user = $request->user();
        $user->passkey = md5(uniqid().time().microtime());
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed their account PID.");

        return redirect()->route('user_security', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#pid'])
            ->withSuccess('Your PID Was Changed Successfully!');
    }

    /**
     * User Other Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeOther(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }
        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_other_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_online = ($request->input('show_online') && $request->input('show_online') == 1 ? 1 : 0);
        $privacy->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#other'])
            ->withSuccess('Your Other Privacy Settings Have Been Saved!');
    }

    /**
     * User Request Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeRequest(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }
        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_request_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_requested = ($request->input('show_requested') && $request->input('show_requested') == 1 ? 1 : 0);
        $privacy->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#request'])
            ->withSuccess('Your Request Privacy Settings Have Been Saved!');
    }

    /**
     * User Achievement Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeAchievement(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }
        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_achievement_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_achievement = ($request->input('show_achievement') && $request->input('show_achievement') == 1 ? 1 : 0);
        $privacy->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#achievement'])
            ->withSuccess('Your Achievement Privacy Settings Have Been Saved!');
    }

    /**
     * User Forum Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeForum(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }
        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_forum_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_topic = ($request->input('show_topic') && $request->input('show_topic') == 1 ? 1 : 0);
        $privacy->show_post = ($request->input('show_post') && $request->input('show_post') == 1 ? 1 : 0);
        $privacy->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#forum'])
            ->withSuccess('Your Forum History Privacy Settings Have Been Saved!');
    }

    /**
     * User Follower Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeFollower(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }
        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_follower_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_follower = ($request->input('show_follower') && $request->input('show_follower') == 1 ? 1 : 0);
        $privacy->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#follower'])
            ->withSuccess('Your Follower Privacy Settings Have Been Saved!');
    }

    /**
     * User Torrent Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeTorrent(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }
        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_torrent_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_upload = ($request->input('show_upload') && $request->input('show_upload') == 1 ? 1 : 0);
        $privacy->show_download = ($request->input('show_download') && $request->input('show_download') == 1 ? 1 : 0);
        $privacy->show_peer = ($request->input('show_peer') && $request->input('show_peer') == 1 ? 1 : 0);
        $privacy->save();

        $user->peer_hidden = 0;
        $user->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#torrent'])
            ->withSuccess('Your Torrent History Privacy Settings Have Been Saved!');
    }

    /**
     * User Account Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeAccountNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_account_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_account_follow = ($request->input('show_account_follow') && $request->input('show_account_follow') == 1 ? 1 : 0);
        $notification->show_account_unfollow = ($request->input('show_account_unfollow') && $request->input('show_account_unfollow') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#account'])
            ->withSuccess('Your Account Notification Settings Have Been Saved!');
    }

    /**
     * User Following Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeFollowingNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_following_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_following_upload = ($request->input('show_following_upload') && $request->input('show_following_upload') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#following'])
            ->withSuccess('Your Followed User Notification Settings Have Been Saved!');
    }

    /**
     * User BON Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeBonNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_bon_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_bon_gift = ($request->input('show_bon_gift') && $request->input('show_bon_gift') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#bon'])
            ->withSuccess('Your BON Notification Settings Have Been Saved!');
    }

    /**
     * User Subscription Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeSubscriptionNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_subscription_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_subscription_forum = ($request->input('show_subscription_forum') && $request->input('show_subscription_forum') == 1 ? 1 : 0);
        $notification->show_subscription_topic = ($request->input('show_subscription_topic') && $request->input('show_subscription_topic') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#subscription'])
            ->withSuccess('Your Subscription Notification Settings Have Been Saved!');
    }

    /**
     * User Request Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeRequestNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_request_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_request_comment = ($request->input('show_request_comment') && $request->input('show_request_comment') == 1 ? 1 : 0);
        $notification->show_request_bounty = ($request->input('show_request_bounty') && $request->input('show_request_bounty') == 1 ? 1 : 0);
        $notification->show_request_fill = ($request->input('show_request_fill') && $request->input('show_request_fill') == 1 ? 1 : 0);
        $notification->show_request_fill_approve = ($request->input('show_request_fill_approve') && $request->input('show_request_fill_approve') == 1 ? 1 : 0);
        $notification->show_request_fill_reject = ($request->input('show_request_fill_reject') && $request->input('show_request_fill_reject') == 1 ? 1 : 0);
        $notification->show_request_claim = ($request->input('show_request_claim') && $request->input('show_request_claim') == 1 ? 1 : 0);
        $notification->show_request_unclaim = ($request->input('show_request_unclaim') && $request->input('show_request_unclaim') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#request'])
            ->withSuccess('Your Request Notification Settings Have Been Saved!');
    }

    /**
     * User Torrent Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeTorrentNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_torrent_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_torrent_comment = ($request->input('show_torrent_comment') && $request->input('show_torrent_comment') == 1 ? 1 : 0);
        $notification->show_torrent_thank = ($request->input('show_torrent_thank') && $request->input('show_torrent_thank') == 1 ? 1 : 0);
        $notification->show_torrent_tip = ($request->input('show_torrent_tip') && $request->input('show_torrent_tip') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#torrent'])
            ->withSuccess('Your Torrent Notification Settings Have Been Saved!');
    }

    /**
     * User Mention Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeMentionNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_mention_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_mention_torrent_comment = ($request->input('show_mention_torrent_comment') && $request->input('show_mention_torrent_comment') == 1 ? 1 : 0);
        $notification->show_mention_request_comment = ($request->input('show_mention_request_comment') && $request->input('show_mention_request_comment') == 1 ? 1 : 0);
        $notification->show_mention_article_comment = ($request->input('show_mention_article_comment') && $request->input('show_mention_article_comment') == 1 ? 1 : 0);
        $notification->show_mention_forum_post = ($request->input('show_mention_forum_post') && $request->input('show_mention_forum_post') == 1 ? 1 : 0);

        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#mention'])
            ->withSuccess('Your @Mention Notification Settings Have Been Saved!');
    }

    /**
     * User Forum Notification Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeForumNotification(Request $request)
    {
        $user = auth()->user();
        $notification = $user->notification;
        if (! $notification) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $notification->json_forum_groups = array_merge($notification->expected_groups, ['default_groups' => $tomerge]);
        $notification->show_forum_topic = ($request->input('show_forum_topic') && $request->input('show_forum_topic') == 1 ? 1 : 0);
        $notification->save();

        return redirect()->route('user_notification', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#forum'])
            ->withSuccess('Your Forum Notification Settings Have Been Saved!');
    }

    /**
     * User Profile Privacy Change.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    protected function changeProfile(Request $request)
    {
        $user = auth()->user();
        $privacy = $user->privacy;
        if (! $privacy) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }

        $approved = $request->input('approved');
        $groups = Group::all();
        $tomerge = [];
        foreach ($groups as $group) {
            if (is_array($approved) && in_array($group->id, $approved)) {
                $tomerge[$group->id] = 1;
            } else {
                $tomerge[$group->id] = 0;
            }
        }
        $privacy->json_profile_groups = array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_profile_torrent_count = ($request->input('show_profile_torrent_count') && $request->input('show_profile_torrent_count') == 1 ? 1 : 0);
        $privacy->show_profile_torrent_ratio = ($request->input('show_profile_torrent_ratio') && $request->input('show_profile_torrent_ratio') == 1 ? 1 : 0);
        $privacy->show_profile_torrent_seed = ($request->input('show_profile_torrent_seed') && $request->input('show_profile_torrent_seed') == 1 ? 1 : 0);
        $privacy->show_profile_torrent_extra = ($request->input('show_profile_torrent_extra') && $request->input('show_profile_torrent_extra') == 1 ? 1 : 0);
        $privacy->show_profile_about = ($request->input('show_profile_about') && $request->input('show_profile_about') == 1 ? 1 : 0);
        $privacy->show_profile_achievement = ($request->input('show_profile_achievement') && $request->input('show_profile_achievement') == 1 ? 1 : 0);
        $privacy->show_profile_badge = ($request->input('show_profile_badge') && $request->input('show_profile_badge') == 1 ? 1 : 0);
        $privacy->show_profile_follower = ($request->input('show_profile_follower') && $request->input('show_profile_follower') == 1 ? 1 : 0);
        $privacy->show_profile_title = ($request->input('show_profile_title') && $request->input('show_profile_title') == 1 ? 1 : 0);
        $privacy->show_profile_bon_extra = ($request->input('show_profile_bon_extra') && $request->input('show_profile_bon_extra') == 1 ? 1 : 0);
        $privacy->show_profile_comment_extra = ($request->input('show_profile_comment_extra') && $request->input('show_profile_comment_extra') == 1 ? 1 : 0);
        $privacy->show_profile_forum_extra = ($request->input('show_profile_forum_extra') && $request->input('show_profile_forum_extra') == 1 ? 1 : 0);
        $privacy->show_profile_request_extra = ($request->input('show_profile_request_extra') && $request->input('show_profile_request_extra') == 1 ? 1 : 0);
        $privacy->show_profile_warning = ($request->input('show_profile_warning') && $request->input('show_profile_warning') == 1 ? 1 : 0);
        $privacy->save();

        return redirect()->route('user_privacy', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#profile'])
            ->withSuccess('Your Profile Privacy Settings Have Been Saved!');
    }

    /**
     * Change User RID.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function changeRID(Request $request, $username, $id)
    {
        $user = $request->user();
        $user->rsskey = md5(uniqid().time().microtime());
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has changed their account RID.");

        return redirect()->route('user_security', ['slug' => $user->slug, 'id' => $user->id, 'hash' => '#rid'])
            ->withSuccess('Your RID Was Changed Successfully!');
    }

    /**
     * User Privacy Settings.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacy(Request $request, $slug, $id)
    {
        $user = $request->user();
        $groups = Group::where('level', '>', 0)->orderBy('level', 'desc')->get();

        return view('user.privacy', ['user' => $user, 'groups'=> $groups]);
    }

    /**
     * User Notification Settings.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notification(Request $request, $slug, $id)
    {
        $user = $request->user();
        $groups = Group::where('level', '>', 0)->orderBy('level', 'desc')->get();

        return view('user.notification', ['user' => $user, 'groups'=> $groups]);
    }

    /**
     * Get A Users Warnings.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWarnings(Request $request, $username, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

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
    public function deactivateWarning(Request $request, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
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
            ->withSuccess('Warning Was Successfully Deactivated');
    }

    /**
     * Deactivate All Warnings.
     *
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deactivateAllWarnings(Request $request, $username, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
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
            ->withSuccess('All Warnings Were Successfully Deactivated');
    }

    /**
     * Delete A Warning.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteWarning(Request $request, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();
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
            ->withSuccess('Warning Was Successfully Deleted');
    }

    /**
     * Delete All Warnings.
     *
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteAllWarnings(Request $request, $username, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();
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
            ->withSuccess('All Warnings Were Successfully Deleted');
    }

    /**
     * Restore A Soft Deleted Warning.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function restoreWarning(Request $request, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();
        $warning = Warning::findOrFail($id);
        $warning->restore();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has restore a soft deleted warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->withSuccess('Warning Was Successfully Restored');
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
        abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);

        if ($request->has('view') && $request->input('view') == 'seeds') {
            $history = Peer::with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->selectRaw('distinct(torrents.info_hash),max(peers.id) as id,max(torrents.name) as name,max(torrents.seeders) as seeders,max(torrents.leechers) as leechers,max(torrents.times_completed) as times_completed,max(torrents.size) as size,max(history.info_hash) as history_info_hash,max(history.created_at) as history_created_at,max(torrents.id) as torrent_id,max(history.seedtime) as seedtime')->leftJoin('torrents', 'torrents.id', '=', 'peers.torrent_id')->leftJoin('history', 'history.info_hash', '=', 'torrents.info_hash')->where('peers.user_id', '=', $user->id)->whereRaw('history.user_id = ? and history.seeder = ?', [$user->id, 1])
                ->where('peers.seeder', '=', 1)->groupBy('torrents.info_hash');

            $order = null;
            $sorting = null;

            $history->where(function ($query) use ($request) {
                if ($request->has('dying') && $request->input('dying') != null) {
                    $query->orWhereRaw('(torrents.seeders = ? AND torrents.times_completed > ? AND date_sub(peers.created_at,interval 30 minute) < now())', [1, 2]);
                }
                if ($request->has('legendary') && $request->input('legendary') != null) {
                    $query->orWhereRaw('(torrents.created_at < date_sub(now(), interval 12 month) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('old') && $request->input('old') != null) {
                    $query->orWhereRaw('(torrents.created_at < date_sub(now(), Interval 6 month) and torrents.created_at > date_sub(now(), interval 12 month) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('huge') && $request->input('huge') != null) {
                    $query->orWhereRaw('(torrents.size > (1073741824 * 100) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('large') && $request->input('large') != null) {
                    $query->orWhereRaw('(torrents.size > (1073741824 * 25) and torrents.size < (1073741824 * 100) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('regular') && $request->input('regular') != null) {
                    $query->orWhereRaw('(torrents.size > (1073741824) and torrents.size < (1073741824 * 25) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('participant_seeder') && $request->input('participant_seeder') != null) {
                    $query->orWhereRaw('(history.active = 1 AND history.seedtime > (2592000) and history.seedtime < (2592000 * 2) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('teamplayer_seeder') && $request->input('teamplayer_seeder') != null) {
                    $query->orWhereRaw('(history.active = 1 AND history.seedtime > (2592000 * 2) and history.seedtime < (2592000 * 3) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('committed_seeder') && $request->input('committed_seeder') != null) {
                    $query->orWhereRaw('(history.active = 1 AND history.seedtime > (2592000 * 3) and history.seedtime < (2592000 * 6) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('mvp_seeder') && $request->input('mvp_seeder') != null) {
                    $query->orWhereRaw('(history.active = 1 AND history.seedtime > (2592000 * 6) and history.seedtime < (2592000 * 12) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
                if ($request->has('legendary_seeder') && $request->input('legendary_seeder') != null) {
                    $query->orWhereRaw('(history.active = 1 AND history.seedtime > (2592000 * 12) and date_sub(peers.created_at,interval 30 minute) < now())', []);
                }
            });

            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
            }
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

            if ($sorting != 'name' && $sorting != 'size' && $sorting != 'times_completed' && $sorting != 'seeders' && $sorting != 'leechers') {
                if ($sorting == 'seedtime') {
                    $table = $history->orderBy($sorting, $order)->paginate(50);
                } elseif ($sorting == 'hcreated_at') {
                    $table = $history->orderBy('history_created_at', $order)->paginate(50);
                } else {
                    $table = $history->orderBy($sorting, $order)->paginate(50);
                }
            } else {
                $table = $history->orderBy($sorting, $order)->paginate(50);
            }

            return view('user.filters.seeds', [
                'user' => $user,
                'seeds' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'requests') {
            $torrentRequests = TorrentRequest::with(['user', 'category']);

            $order = null;
            $sorting = null;

            if ($request->has('name') && $request->input('name') != null) {
                $torrentRequests->where('name', 'like', '%'.$request->input('name').'%');
            }
            if ($request->has('filled') && $request->input('filled') != null) {
                $torrentRequests->whereNotNull('filled_by')
                    ->whereNotNull('filled_hash')
                    ->whereNotNull('filled_when')
                    ->whereNotNull('approved_by')
                    ->whereNotNull('approved_when');
            }
            if ($request->has('pending') && $request->input('pending') != null) {
                $torrentRequests->whereNotNull('filled_by')
                    ->whereNotNull('filled_hash')
                    ->whereNotNull('filled_when')
                    ->whereNull('approved_by')
                    ->whereNull('approved_when');
            }
            if ($request->has('claimed') && $request->input('claimed') != null) {
                $torrentRequests->where('claimed', '=', 1);
            }
            if ($request->has('unfilled') && $request->input('unfilled') != null) {
                $torrentRequests->where(function ($query) {
                    $query->whereNull('filled_by')->orWhereNull('filled_hash')->orWhereNull('approved_by');
                });
            }

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

            if ($sorting == 'date') {
                $table = $torrentRequests->where('user_id', '=', $user->id)->orderBy('created_at', $order)->paginate(25);
            } else {
                $table = $torrentRequests->where('user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(25);
            }

            return view('user.filters.requests', [
                'user' => $user,
                'torrentRequests' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'resurrections') {
            $history = Graveyard::with(['torrent', 'user'])->leftJoin('torrents', 'torrents.id', '=', 'graveyard.torrent_id');

            $order = null;
            $sorting = null;

            if ($request->has('rewarded') && $request->input('rewarded') != null) {
                $history->where('graveyard.rewarded', '=', 1);
            }
            if ($request->has('notrewarded') && $request->input('notrewarded') != null) {
                $history->where('graveyard.rewarded', '=', 0);
            }
            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
            }
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

            if ($sorting != 'name' && $sorting != 'size' && $sorting != 'times_completed' && $sorting != 'seeders' && $sorting != 'leechers') {
                if ($sorting == 'goal') {
                    $table = $history->where('graveyard.user_id', '=', $user->id)->orderBy('graveyard.seedtime', $order)->paginate(50);
                } else {
                    $table = $history->where('graveyard.user_id', '=', $user->id)->orderBy('graveyard.'.$sorting, $order)->paginate(50);
                }
            } else {
                $table = $history->where('graveyard.user_id', '=', $user->id)->orderBy('torrents.'.$sorting, $order)->paginate(50);
            }

            return view('user.filters.resurrections', [
                'user' => $user,
                'resurrections' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'active') {
            $history = Peer::with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->leftJoin('torrents', 'torrents.id', '=', 'peers.torrent_id');

            $order = null;
            $sorting = null;

            if ($request->has('seeding') && $request->input('seeding') != null) {
                $history->where('peers.seeder', '=', 1);
            }
            if ($request->has('leeching') && $request->input('leeching') != null) {
                $history->where('peers.seeder', '=', 0)->where('peers.left', '>', 0);
            }
            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
            }
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

            if ($sorting != 'name' && $sorting != 'size' && $sorting != 'times_completed' && $sorting != 'seeders' && $sorting != 'leechers') {
                $table = $history->where('peers.user_id', '=', $user->id)->orderBy('peers.'.$sorting, $order)->paginate(50);
            } else {
                $table = $history->where('peers.user_id', '=', $user->id)->orderBy('torrents.'.$sorting, $order)->paginate(50);
            }

            return view('user.filters.active', [
                'user' => $user,
                'active' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'unsatisfieds') {
            if (config('hitrun.enabled') == true) {
                $history = History::selectRaw('distinct(history.info_hash), max(torrents.id), max(history.completed_at) as completed_at, max(torrents.name) as name, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seedtime) as satisfied_at, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                    $query->withAnyStatus();
                }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('actual_downloaded', '>', 0)
                    ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.enabled') == true ? (config('hitrun.buffer') / 100) : 0).'))')->groupBy('history.info_hash');
            } else {
                $history = History::selectRaw('distinct(history.info_hash), max(torrents.id), max(history.completed_at) as completed_at, max(torrents.name) as name, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seedtime) as satisfied_at, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                    $query->withAnyStatus();
                }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('actual_downloaded', '>', 0)
                    ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.enabled') == true ? (config('hitrun.buffer') / 100) : 0).'))')->groupBy('history.info_hash');
            }
            $order = null;
            $sorting = null;

            $history->whereRaw('(history.seedtime < ? and history.immune != 1)', [config('hitrun.seedtime')]);

            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
            }
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

            if ($request->has('error') && $request->input('error') != null) {
                $history->where('seeder', '=', 0);
            }

            if ($request->has('seeding') && $request->input('seeding') != null) {
                $history->where('seeder', '=', 1);
            }

            if ($sorting != 'name' && $sorting != 'satisfied_at' && $sorting != 'size' && $sorting != 'times_completed' && $sorting != 'seeders' && $sorting != 'leechers') {
                $table = $history->where('history.user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(50);
            } else {
                if ($sorting == 'satisfied_at') {
                    if ($order == 'desc') {
                        $order = 'asc';
                    } elseif ($order == 'asc') {
                        $order = 'desc';
                    }
                    $table = $history->where('history.user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(50);
                } else {
                    $table = $history->where('history.user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(50);
                }
            }

            return view('user.filters.unsatisfieds', [
                'user' => $user,
                'downloads' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'downloads') {
            $history = History::selectRaw('distinct(history.info_hash), max(history.completed_at) as completed_at, max(torrents.name) as name, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('actual_downloaded', '>', 0)
                ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.enabled') == true ? (config('hitrun.buffer') / 100) : 0).'))')->groupBy('history.info_hash');
            $order = null;
            $sorting = null;

            $history->where(function ($query) use ($request) {
                if ($request->has('satisfied') && $request->input('satisfied') != null) {
                    $query->orWhereRaw('(history.seedtime >= ? or history.immune = 1)', [config('hitrun.seedtime')]);
                }
                if ($request->has('notsatisfied') && $request->input('notsatisfied') != null) {
                    $query->orWhereRaw('(history.seedtime < ? and history.immune != 1)', [config('hitrun.seedtime')]);
                }
            });
            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
            }
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

            if ($sorting != 'name' && $sorting != 'size' && $sorting != 'times_completed' && $sorting != 'seeders' && $sorting != 'leechers') {
                $table = $history->where('history.user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(50);
            } else {
                $table = $history->where('history.user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(50);
            }

            return view('user.filters.downloads', [
                'user' => $user,
                'downloads' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'uploads') {
            $history = Torrent::selectRaw('distinct(torrents.id),max(torrents.moderated_at) as moderated_at,max(torrents.slug) as slug,max(torrents.user_id) as user_id,max(torrents.name) as name,max(torrents.category_id) as category_id,max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed,max(torrents.created_at) as created_at,max(torrents.status) as status,count(distinct thanks.id) as thanked_total,max(bt.tipped_total) as tipped_total')->withAnyStatus()->where('torrents.user_id', '=', $user->id)->with(['tips', 'thanks'])->leftJoin(DB::raw('(select distinct(bon_transactions.torrent_id),sum(bon_transactions.cost) as tipped_total from bon_transactions group by bon_transactions.torrent_id) as bt'), 'bt.torrent_id', '=', 'torrents.id')->leftJoin('thanks', 'thanks.torrent_id', 'torrents.id')->groupBy('torrents.id');

            $order = null;
            $sorting = null;

            if ($request->has('pending') && $request->input('pending') != null) {
                $history->whereRaw('(torrents.status) = ?', [0]);
            }
            if ($request->has('approved') && $request->input('approved') != null) {
                $history->whereRaw('(torrents.status) = ?', [1]);
            }
            if ($request->has('rejected') && $request->input('rejected') != null) {
                $history->whereRaw('(torrents.status) = ?', [2]);
            }

            $history->where(function ($query) use ($request) {
                if ($request->has('dead') && $request->input('dead') != null) {
                    $query->orWhereRaw('(torrents.seeders+torrents.leechers) = ?', [0]);
                }
                if ($request->has('alive') && $request->input('alive') != null) {
                    $query->orWhereRaw('torrents.seeders >= ?', [1]);
                }
                if ($request->has('reseed') && $request->input('reseed') != null) {
                    $query->orWhereRaw('(torrents.seeders = ?) AND (torrents.leechers >= ?)', [0, 1]);
                }
                if ($request->has('error') && $request->input('error') != null) {
                    $query->orWhereRaw('(torrents.seeders = ?) AND (torrents.leechers = ?)', [0, 0]);
                }
            });

            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
            }
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

            if ($sorting == 'tipped' || $sorting == 'thanked') {
                $table = $history->orderBy($sorting.'_total', $order)->paginate(50);
            } else {
                $table = $history->orderBy($sorting, $order)->paginate(50);
            }

            return view('user.filters.uploads', [
                'user' => $user,
                'uploads' => $table,
            ])->render();
        } elseif ($request->has('view') && $request->input('view') == 'history') {
            $history = History::with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->selectRaw('distinct(history.id),max(history.info_hash) as info_hash,max(history.agent) as agent,max(history.uploaded) as uploaded,max(history.downloaded) as downloaded,max(history.seeder) as seeder,max(history.active) as active,max(history.actual_uploaded) as actual_uploaded,max(history.actual_downloaded) as actual_downloaded,max(history.seedtime) as seedtime,max(history.created_at) as created_at,max(history.updated_at) as updated_at,max(history.completed_at) as completed_at,max(history.immune) as immune,max(history.hitrun) as hitrun,max(history.prewarn) as prewarn,max(torrents.moderated_at) as moderated_at,max(torrents.slug) as slug,max(torrents.user_id) as user_id,max(torrents.name) as name,max(torrents.category_id) as category_id,max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed,max(torrents.status) as status')->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->groupBy('history.id');

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

            if ($request->has('name') && $request->input('name') != null) {
                $history->where('torrents.name', 'like', '%'.$request->input('name').'%');
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

            $table = $history->where('history.user_id', '=', $user->id)->orderBy($sorting, $order)->paginate(50);

            return view('user.filters.history', [
                'user' => $user,
                'history' => $table,
            ])->render();
        }

        return false;
    }

    /**
     * Show User Achievements.
     *
     * @param $username
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function achievements($username, $id)
    {
        $user = User::findOrFail($id);
        $achievements = $user->unlockedAchievements();

        return view('user.achievements', [
            'route'        => 'achievement',
            'user'         => $user,
            'achievements' => $achievements,
        ]);
    }

    /**
     * Get A Users Wishlist.
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wishes(Request $request, $slug, $id)
    {
        $user = User::with('wishes')->where('id', '=', $id)->firstOrFail();

        abort_unless(($request->user()->group->is_modo || $request->user()->id == $user->id), 403);

        $wishes = $user->wishes()->latest()->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $id)->first();

        return view('user.wishlist', [
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'wishes'             => $wishes,
            'route'              => 'wish',
        ]);
    }

    /**
     * Get A Users Torrent Bookmarks.
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookmarks(Request $request, $slug, $id)
    {
        $user = User::with('bookmarks')->where('id', '=', $id)->firstOrFail();

        abort_unless(($request->user()->group->is_modo || $request->user()->id == $user->id), 403);

        $bookmarks = $user->bookmarks()->latest()->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $id)->first();

        return view('user.bookmarks', [
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'bookmarks'          => $bookmarks,
            'route'              => 'bookmark',
        ]);
    }

    /**
     * Get A Users Downloads (Fully Downloaded) Table.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloads(Request $request, $slug, $id)
    {
        $user = User::findOrFail($id);
        if (($request->user()->id == $user->id || $request->user()->group->is_modo)) {
            $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
            $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
            $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
            $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');

            $logger = 'user.private.downloads';

            if (config('hitrun.enabled') == true) {
                $downloads = History::selectRaw('distinct(history.info_hash), max(torrents.id), max(history.completed_at) as completed_at, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                    $query->withAnyStatus();
                }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('actual_downloaded', '>', 0)
                    ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.buffer') / 100).'))')
                    ->where('history.user_id', '=', $user->id)->groupBy('history.info_hash')->orderBy('completed_at', 'desc')
                    ->paginate(50);
            } else {
                $downloads = History::selectRaw('distinct(history.info_hash), max(torrents.id), max(history.completed_at) as completed_at, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                    $query->withAnyStatus();
                }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')
                    ->where('history.user_id', '=', $user->id)->groupBy('history.info_hash')->orderBy('completed_at', 'desc')
                    ->paginate(50);
            }

            return view($logger, [
                'route'        => 'downloads',
                'user'          => $user,
                'downloads'     => $downloads,
                'his_upl'       => $his_upl,
                'his_upl_cre'   => $his_upl_cre,
                'his_downl'     => $his_downl,
                'his_downl_cre' => $his_downl_cre,
            ]);
        } else {
            $logger = 'user.downloads';

            if (config('hitrun.enabled') == true) {
                $downloads = History::with(['torrent' => function ($query) {
                    $query->withAnyStatus();
                }])->selectRaw('distinct(history.info_hash), max(torrents.id), max(history.completed_at) as completed_at, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('actual_downloaded', '>', 0)
                    ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.buffer') / 100).'))')
                    ->where('history.user_id', '=', $user->id)
                    ->groupBy('history.info_hash')->orderBy('completed_at', 'desc')
                    ->paginate(50);
            } else {
                $downloads = History::with(['torrent' => function ($query) {
                    $query->withAnyStatus();
                }])->selectRaw('distinct(history.info_hash), max(torrents.id), max(history.completed_at) as completed_at, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')
                    ->where('history.user_id', '=', $user->id)
                    ->groupBy('history.info_hash')->orderBy('completed_at', 'desc')
                    ->paginate(50);
            }

            return view($logger, [
                'route'        => 'downloads',
                'user'        => $user,
                'downloads'   => $downloads,
            ]);
        }
    }

    /**
     * Get A Users Requested Table.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requested(Request $request, $slug, $id)
    {
        $user = User::findOrFail($id);
        if (($request->user()->id == $user->id || $request->user()->group->is_modo)) {
            $logger = 'user.private.requests';

            $torrentRequests = TorrentRequest::with(['user', 'category'])->where('user_id', '=', $user->id)->latest()->paginate(25);

            return view($logger, [
                'route'         => 'requests',
                'user'          => $user,
                'torrentRequests' => $torrentRequests,
            ]);
        } else {
            $logger = 'user.requests';

            $torrentRequests = TorrentRequest::with(['user', 'category'])->where('user_id', '=', $user->id)->where('anon', '!=', 1)->latest()->paginate(25);

            return view($logger, [
                'route'         => 'requests',
                'user'          => $user,
                'torrentRequests' => $torrentRequests,
            ]);
        }
    }

    /**
     * Get A Users Unsatisfieds Table.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unsatisfieds(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);
        $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
        $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
        $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
        $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');
        $logger = 'user.private.unsatisfieds';

        if (config('hitrun.enabled') == true) {
            $downloads = History::selectRaw('distinct(history.info_hash), max(torrents.name) as name, max(torrents.id), max(history.completed_at) as completed_at, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seedtime) as satisfied_at, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')
                ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.buffer') / 100).'))')
                ->where('history.user_id', '=', $user->id)->groupBy('history.info_hash')->orderBy('satisfied_at', 'desc')
                ->whereRaw('(history.seedtime < ? and history.immune != 1)', [config('hitrun.seedtime')])
                ->paginate(50);
        } else {
            $downloads = History::selectRaw('distinct(history.info_hash), max(torrents.name) as name, max(torrents.id), max(history.completed_at) as completed_at, max(history.created_at) as created_at, max(history.id) as id, max(history.user_id) as user_id, max(history.seedtime) as seedtime, max(history.seedtime) as satisfied_at, max(history.seeder) as seeder, max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed')->with(['torrent' => function ($query) {
                $query->withAnyStatus();
            }])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')
                ->whereRaw('history.actual_downloaded > (torrents.size * ('.(config('hitrun.buffer') / 100).'))')
                ->where('history.user_id', '=', $user->id)->groupBy('history.info_hash')->orderBy('satisfied_at', 'desc')
                ->whereRaw('(history.seedtime < ? and history.immune != 1)', [config('hitrun.seedtime')])
                ->paginate(50);
        }

        return view($logger, [
            'route'        => 'unsatisfieds',
            'user'          => $user,
            'downloads'     => $downloads,
            'his_upl'       => $his_upl,
            'his_upl_cre'   => $his_upl_cre,
            'his_downl'     => $his_downl,
            'his_downl_cre' => $his_downl_cre,
        ]);
    }

    /**
     * Get A Users History Table.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function torrents(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);
        $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
        $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
        $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
        $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');
        $history = History::with(['torrent' => function ($query) {
            $query->withAnyStatus();
        }])->selectRaw('distinct(history.id),max(history.info_hash) as info_hash,max(history.agent) as agent,max(history.uploaded) as uploaded,max(history.downloaded) as downloaded,max(history.seeder) as seeder,max(history.active) as active,max(history.actual_uploaded) as actual_uploaded,max(history.actual_downloaded) as actual_downloaded,max(history.seedtime) as seedtime,max(history.created_at) as created_at,max(history.updated_at) as updated_at,max(history.completed_at) as completed_at,max(history.immune) as immune,max(history.hitrun) as hitrun,max(history.prewarn) as prewarn,max(torrents.moderated_at) as moderated_at,max(torrents.slug) as slug,max(torrents.user_id) as user_id,max(torrents.name) as name,max(torrents.category_id) as category_id,max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed,max(torrents.status) as status')->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('history.user_id', '=', $user->id)->groupBy('history.id')
            ->orderBy('created_at', 'DESC')->paginate(50);

        return view('user.private.torrents', [
            'route'         => 'torrents',
            'user'          => $user,
            'history'       => $history,
            'his_upl'       => $his_upl,
            'his_upl_cre'   => $his_upl_cre,
            'his_downl'     => $his_downl,
            'his_downl_cre' => $his_downl_cre,
        ]);
    }

    /**
     * Get A Users Graveyard Resurrections.
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resurrections(Request $request, $slug, $id)
    {
        $user = User::findOrFail($id);
        abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);

        $resurrections = Graveyard::with(['torrent', 'user'])->where('user_id', '=', $user->id)->paginate(50);

        return view('user.private.resurrections', [
            'route'         => 'resurrections',
            'user'          => $user,
            'resurrections' => $resurrections,
        ]);
    }

    /**
     * Get A User Uploads.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploads(Request $request, $slug, $id)
    {
        $user = User::findOrFail($id);
        if ($request->user()->id == $user->id || $request->user()->group->is_modo) {
            $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
            $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
            $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
            $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');

            $logger = 'user.private.uploads';
            $uploads = Torrent::with(['tips', 'thanks', 'category'])->selectRaw('distinct(torrents.id),max(torrents.moderated_at) as moderated_at,max(torrents.slug) as slug,max(torrents.user_id) as user_id,max(torrents.name) as name,max(torrents.category_id) as category_id,max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed,max(torrents.created_at) as created_at,max(torrents.status) as status,count(distinct thanks.id) as thanked_total,max(bt.tipped_total) as tipped_total')
                ->withAnyStatus()->where('torrents.user_id', '=', $user->id)->leftJoin(DB::raw('(select distinct(bon_transactions.torrent_id),sum(bon_transactions.cost) as tipped_total from bon_transactions group by bon_transactions.torrent_id) as bt'), 'bt.torrent_id', '=', 'torrents.id')->leftJoin('thanks', 'thanks.torrent_id', 'torrents.id')->groupBy('torrents.id')->orderBy('created_at', 'DESC')->paginate(50);

            return view($logger, [
                'route'         => 'uploads',
                'user'          => $user,
                'uploads'       => $uploads,
                'his_upl'       => $his_upl,
                'his_upl_cre'   => $his_upl_cre,
                'his_downl'     => $his_downl,
                'his_downl_cre' => $his_downl_cre,
            ]);
        } else {
            $logger = 'user.uploads';
            $uploads = Torrent::selectRaw('distinct(torrents.id),max(torrents.moderated_at) as moderated_at,max(torrents.slug) as slug,max(torrents.user_id) as user_id,max(torrents.name) as name,max(torrents.category_id) as category_id,max(torrents.size) as size,max(torrents.leechers) as leechers,max(torrents.seeders) as seeders,max(torrents.times_completed) as times_completed,max(torrents.created_at) as created_at,max(torrents.status) as status,count(distinct thanks.id) as thanked_total,sum(bon_transactions.cost) as tipped_total')->where('torrents.user_id', '=', $user->id)->where('torrents.status', '=', 1)->where('torrents.anon', '=', 0)->with(['tips', 'thanks'])->leftJoin('bon_transactions', 'bon_transactions.torrent_id', 'torrents.id')->leftJoin('thanks', 'thanks.torrent_id', 'torrents.id')->groupBy('torrents.id')->orderBy('created_at', 'DESC')->paginate(50);

            return view($logger, [
                'route'       => 'uploads',
                'user'        => $user,
                'uploads'     => $uploads,
            ]);
        }
    }

    /**
     * Get A Users Active Table.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function active(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);

        $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
        $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
        $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
        $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');

        $active = Peer::with(['torrent' => function ($query) {
            $query->withAnyStatus();
        }])->sortable(['created_at' => 'desc'])
            ->where('user_id', '=', $user->id)
            ->distinct('hash')
            ->paginate(50);

        return view('user.private.active', ['user' => $user,
            'route'         => 'active',
            'active'        => $active,
            'his_upl'       => $his_upl,
            'his_upl_cre'   => $his_upl_cre,
            'his_downl'     => $his_downl,
            'his_downl_cre' => $his_downl_cre,
        ]);
    }

    /**
     * Get A Users Seeds Table.
     *
     * @param $username
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeds(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);

        abort_unless($request->user()->group->is_modo || $request->user()->id == $user->id, 403);

        $his_upl = History::where('user_id', '=', $id)->sum('actual_uploaded');
        $his_upl_cre = History::where('user_id', '=', $id)->sum('uploaded');
        $his_downl = History::where('user_id', '=', $id)->sum('actual_downloaded');
        $his_downl_cre = History::where('user_id', '=', $id)->sum('downloaded');

        $seeds = Peer::with(['torrent' => function ($query) {
            $query->withAnyStatus();
        }])->selectRaw('distinct(torrents.info_hash),max(peers.id) as id,max(torrents.name) as name,max(torrents.seeders) as seeders,max(torrents.leechers) as leechers,max(torrents.times_completed) as times_completed,max(torrents.size) as size,max(history.info_hash) as history_info_hash,max(history.created_at) as history_created_at,max(torrents.id) as torrent_id,max(history.seedtime) as seedtime')->leftJoin('torrents', 'torrents.id', '=', 'peers.torrent_id')->leftJoin('history', 'history.info_hash', '=', 'torrents.info_hash')->where('peers.user_id', '=', $user->id)->whereRaw('history.user_id = ? and history.seeder = ?', [$user->id, 1])
            ->where('peers.seeder', '=', 1)->orderBy('history_created_at', 'DESC')->groupBy('torrents.info_hash')
            ->paginate(50);

        return view('user.private.seeds', ['user' => $user,
            'route'         => 'seeds',
            'seeds'         => $seeds,
            'his_upl'       => $his_upl,
            'his_upl_cre'   => $his_upl_cre,
            'his_downl'     => $his_downl,
            'his_downl_cre' => $his_downl_cre,
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
    public function getBans(Request $request, $username, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $user = User::findOrFail($id);
        $bans = Ban::where('owned_by', '=', $user->id)->latest()->get();

        return view('user.banlog', [
            'user'      => $user,
            'bans'  => $bans,
        ]);
    }

    /**
     * Download All History Torrents.
     *
     * @param $username
     * @param $id
     *
     * @return \ZipArchive
     */
    public function downloadHistoryTorrents(Request $request, $username, $id)
    {
        //  Extend The Maximum Execution Time
        set_time_limit(300);

        // Authorized User
        $user = User::findOrFail($id);

        // User's ratio is too low
        if ($user->getRatio() < config('other.ratio')) {
            return redirect()->back()->withErrors('Your Ratio Is To Low To Download!');
        }

        // User's download rights are revoked
        if ($user->can_download == 0) {
            return redirect()->back()->withErrors('Your Download Rights Have Been Revoked!');
        }

        abort_unless($request->user()->id == $user->id, 403);
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
                    return redirect()->back()->withErrors('Torrent File Not Found! Please Report This Torrent!');
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
            return redirect()->back()->withErrors('Something Went Wrong!');
        }
    }

    /**
     * Accept Site Rules.
     *
     * @return void
     */
    public function acceptRules(Request $request)
    {
        $user = $request->user();
        $user->read_rules = 1;
        $user->save();
    }
}
