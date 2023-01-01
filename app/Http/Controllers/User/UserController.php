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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BonTransactions;
use App\Models\Group;
use App\Models\History;
use App\Models\Invite;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use App\Models\UserPrivacy;
use App\Models\Warning;
use App\Rules\EmailBlacklist;
use Assada\Achievements\Model\AchievementProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\UserControllerTest
 */
class UserController extends Controller
{
    /**
     * Show A User.
     */
    public function show(string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::with(['privacy', 'history'])
            ->withCount('torrents')
            ->where('username', '=', $username)
            ->when(\auth()->user()->group->is_modo == true, fn ($query) => $query->withTrashed())
            ->firstOrFail();

        $groups = Group::all();
        $followers = $user->followers()->latest()->limit(25)->get();
        $history = $user->history;
        $warnings = Warning::where('user_id', '=', $user->id)->where('active', '=', 1)->take(\config('hitrun.max_warnings'))->get();
        $hitrun = Warning::where('user_id', '=', $user->id)->whereNotNull('torrent')->latest()->paginate(10);

        $bonupload = BonTransactions::where('sender', '=', $user->id)->where([['name', 'like', '%Upload%']])->sum('cost');
        //$bondownload = BonTransactions::where('sender', '=', $user->id)->where([['name', 'like', '%Download%']])->sum('cost');

        //  With Multipliers
        $hisUplCre = History::where('user_id', '=', $user->id)->sum('uploaded');
        //  Without Multipliers
        $hisUpl = History::where('user_id', '=', $user->id)->sum('actual_uploaded');

        $defUpl = \config('other.default_upload');
        $multiUpload = $hisUplCre - $hisUpl;
        $manUpload = $user->uploaded - $hisUplCre - $defUpl - $bonupload;
        $realupload = $user->getUploaded();

        $hisDown = History::where('user_id', '=', $user->id)->sum('actual_downloaded');
        $defDown = \config('other.default_download');
        $freeDownload = $hisDown + $defDown - $user->downloaded;
        $realdownload = $user->getDownloaded();

        $invitedBy = Invite::where('accepted_by', '=', $user->id)->first();

        $requested = TorrentRequest::where('user_id', '=', $user->id)->count();
        $filled = TorrentRequest::where('filled_by', '=', $user->id)->whereNotNull('approved_by')->count();

        $clients = $user->peers()
            ->select('agent', 'port')
            ->selectRaw('INET6_NTOA(ip) as ip, MIN(created_at), MAX(updated_at), COUNT(*) as num_peers')
            ->groupBy(['ip', 'port', 'agent'])
            ->get();

        $achievements = AchievementProgress::with('details')
            ->where('achiever_id', '=', $user->id)
            ->whereNotNull('unlocked_at')
            ->get();

        return \view('user.profile.show', [
            'route'        => 'profile',
            'user'         => $user,
            'groups'       => $groups,
            'followers'    => $followers,
            'history'      => $history,
            'warnings'     => $warnings,
            'hitrun'       => $hitrun,

            //'bondownload'  => $bondownload,
            'realdownload' => $realdownload,
            'def_download' => $defDown,
            'his_down'     => $hisDown,
            'free_down'    => $freeDownload,

            'realupload'   => $realupload,
            'def_upload'   => $defUpl,
            'his_upl'      => $hisUpl,
            'multi_upload' => $multiUpload,
            'bonupload'    => $bonupload,
            'man_upload'   => $manUpload,

            'requested'     => $requested,
            'filled'        => $filled,
            'invitedBy'     => $invitedBy,
            'clients'       => $clients,
            'achievements'  => $achievements,
        ]);
    }

    /**
     * Edit Profile Form.
     */
    public function editProfileForm(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        return \view('user.profile.edit', ['user' => $user, 'route' => 'edit']);
    }

    /**
     * Edit User Profile.
     */
    public function editProfile(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        // Avatar
        $maxUpload = \config('image.max_upload_size');
        if ($request->hasFile('image') && $request->file('image')->getError() === 0) {
            $image = $request->file('image');
            if (\in_array($image->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'bmp', 'png', 'PNG', 'tiff', 'gif']) && \preg_match('#image/*#', (string) $image->getMimeType())) {
                if ($maxUpload >= $image->getSize()) {
                    $filename = $user->username.'.'.$image->getClientOriginalExtension();
                    $path = \public_path('/files/img/'.$filename);
                    if ($image->getClientOriginalExtension() !== 'gif') {
                        Image::make($image->getRealPath())->fit(150, 150)->encode('png', 100)->save($path);
                    } else {
                        $v = \validator($request->all(), [
                            'image' => 'dimensions:ratio=1/1',
                        ]);
                        if ($v->passes()) {
                            $image->move(\public_path('/files/img/'), $filename);
                        } else {
                            return \to_route('users.show', ['username' => $user->username])
                                ->withErrors('Because you are uploading a GIF, your avatar must be square!');
                        }
                    }

                    $user->image = $user->username.'.'.$image->getClientOriginalExtension();
                } else {
                    return \to_route('users.show', ['username' => $user->username])
                        ->withErrors('Your avatar is too large, max file size: '.($maxUpload / 1_000_000).' MB');
                }
            }
        }

        // Prevent User from abusing BBCODE Font Size (max. 99)
        $aboutTemp = $request->input('about');
        if (\str_contains((string) $aboutTemp, '[size=') && \preg_match('/\[size=[0-9]{3,}\]/', (string) $aboutTemp)) {
            return \to_route('users.show', ['username' => $user->username])
                ->withErrors('Font size is too big!');
        }

        // Define data
        $user->title = $request->input('title');
        $user->about = $request->input('about');
        $user->signature = $request->input('signature');
        $user->save();

        return \to_route('user_edit_profile_form', ['username' => $user->username])
            ->withSuccess('Your Account Was Updated Successfully!');
    }

    /**
     * User Account Settings.
     */
    public function settings(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        return \view('user.settings.general.index', ['user' => $user, 'route' => 'settings']);
    }

    /**
     * Change User Account Settings.
     */
    public function changeSettings(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        // General Settings
        $user->censor = $request->input('censor');
        $user->chat_hidden = $request->input('chat_hidden');

        // Language Settings
        $user->locale = $request->input('language');

        // Style Settings
        $user->style = (int) $request->input('theme');

        $customCss = $request->input('custom_css');
        if (isset($customCss) && ! \filter_var($customCss, FILTER_VALIDATE_URL)) {
            return \to_route('users.show', ['username' => $user->username])
                ->withErrors('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.');
        }

        $user->custom_css = $customCss;

        $standaloneCss = $request->input('standalone_css');
        if (isset($standaloneCss) && ! \filter_var($standaloneCss, FILTER_VALIDATE_URL)) {
            return \to_route('users.show', ['username' => $user->username])
                ->withErrors('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.');
        }

        $user->standalone_css = $standaloneCss;

        // Torrent Settings
        $user->torrent_layout = (int) $request->input('torrent_layout');
        $user->show_poster = $request->input('show_poster');
        $user->ratings = $request->input('ratings');
        $user->save();

        return \to_route('user_settings', ['username' => $user->username])
            ->withSuccess('Your Account Was Updated Successfully!');
    }

    /**
     * User Security Settings.
     */
    public function security(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        return \view('user.settings.security.index', ['user' => $user]);
    }

    /**
     * User TwoStep Auth.
     */
    protected function changeTwoStep(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->getMethod() == 'GET') {
            return \to_route('user_security', ['username' => $request->user()->username]);
        }

        $user = \auth()->user();

        \abort_unless(\config('auth.TwoStepEnabled') == true, 403);
        $user->twostep = $request->input('twostep');
        $user->save();

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('You Changed Your TwoStep Auth Status!');
    }

    /**
     * User Password Change.
     */
    protected function changePassword(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $v = \validator($request->all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ]);
        if ($v->passes()) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->save();

                return \to_route('home.index')->withSuccess('Your Password Has Been Reset');
            }

            return \to_route('user_security', ['username' => $user->username, 'hash' => '#password'])
                ->withErrors('Your Password Was Incorrect!');
        }

        return \to_route('user_security', ['username' => $user->username, 'hash' => '#password'])
                ->withErrors('Your New Password Is To Weak!');
    }

    /**
     * User Email Change.
     */
    protected function changeEmail(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        if (\config('email-blacklist.enabled')) {
            $v = \validator($request->all(), [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:70',
                    'unique:users',
                    new EmailBlacklist(),
                ],
            ]);
        } else {
            $v = \validator($request->all(), [
                'email' => 'required|string|email|max:70|unique:users',
            ]);
        }

        if ($v->fails()) {
            return \to_route('user_security', ['username' => $user->username, 'hash' => '#email'])
                ->withErrors($v->errors());
        }

        $user->email = $request->input('email');
        $user->save();

        return \to_route('user_security', ['username' => $user->username, 'hash' => '#email'])
            ->withSuccess('Your Email Was Updated Successfully!');
    }

    /**
     * Change User Privacy Level.
     */
    public function makePrivate(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->private_profile = 1;
        $user->save();

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('You Have Gone Private!');
    }

    /**
     * Change User Privacy Level.
     */
    public function makePublic(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->private_profile = 0;
        $user->save();

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('You Have Gone Public!');
    }

    /**
     * Change User Hidden Value.
     */
    public function makeHidden(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->hidden = 1;
        $user->save();

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('You Have Disappeared Like A Ninja!');
    }

    /**
     * Change User Hidden Value.
     */
    public function makeVisible(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->hidden = 0;
        $user->save();

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('You Have Given Up Your Ninja Ways And Become Visible!');
    }

    /**
     * Change User PID.
     *
     * @throws \Exception
     */
    public function changePID(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->passkey = \md5(\random_bytes(60).$user->password);
        $user->save();

        \cache()->forget(\sprintf('user:%s', $user->passkey));

        return \to_route('user_security', ['username' => $user->username, 'hash' => '#pid'])
            ->withSuccess('Your PID Was Changed Successfully!');
    }

    /**
     * User Other Privacy Change.
     */
    protected function changeOther(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_other_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_online = ($request->input('show_online') && $request->input('show_online') == 1 ? 1 : 0);
        $privacy->save();

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#other'])
            ->withSuccess('Your Other Privacy Settings Have Been Saved!');
    }

    /**
     * User Request Privacy Change.
     */
    protected function changeRequest(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_request_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_requested = ($request->input('show_requested') && $request->input('show_requested') == 1 ? 1 : 0);
        $privacy->save();

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#request'])
            ->withSuccess('Your Request Privacy Settings Have Been Saved!');
    }

    /**
     * User Achievement Privacy Change.
     */
    protected function changeAchievement(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_achievement_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_achievement = ($request->input('show_achievement') && $request->input('show_achievement') == 1 ? 1 : 0);
        $privacy->save();

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#achievement'])
            ->withSuccess('Your Achievement Privacy Settings Have Been Saved!');
    }

    /**
     * User Forum Privacy Change.
     */
    protected function changeForum(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_forum_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_topic = ($request->input('show_topic') && $request->input('show_topic') == 1 ? 1 : 0);
        $privacy->show_post = ($request->input('show_post') && $request->input('show_post') == 1 ? 1 : 0);
        $privacy->save();

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#forum'])
            ->withSuccess('Your Forum History Privacy Settings Have Been Saved!');
    }

    /**
     * User Follower Privacy Change.
     */
    protected function changeFollower(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_follower_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_follower = ($request->input('show_follower') && $request->input('show_follower') == 1 ? 1 : 0);
        $privacy->save();

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#follower'])
            ->withSuccess('Your Follower Privacy Settings Have Been Saved!');
    }

    /**
     * User Torrent Privacy Change.
     */
    protected function changeTorrent(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_torrent_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
        $privacy->show_upload = ($request->input('show_upload') && $request->input('show_upload') == 1 ? 1 : 0);
        $privacy->show_download = ($request->input('show_download') && $request->input('show_download') == 1 ? 1 : 0);
        $privacy->show_peer = ($request->input('show_peer') && $request->input('show_peer') == 1 ? 1 : 0);
        $privacy->save();

        $user->peer_hidden = 0;
        $user->save();

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#torrent'])
            ->withSuccess('Your Torrent History Privacy Settings Have Been Saved!');
    }

    /**
     * User Profile Privacy Change.
     */
    protected function changeProfile(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

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
            $tomerge[$group->id] = \is_array($approved) && \in_array($group->id, $approved) ? 1 : 0;
        }

        $privacy->json_profile_groups = \array_merge($privacy->expected_groups, ['default_groups' => $tomerge]);
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

        return \to_route('user_privacy', ['username' => $user->username, 'hash' => '#profile'])
            ->withSuccess('Your Profile Privacy Settings Have Been Saved!');
    }

    /**
     * Change User RID.
     */
    public function changeRID(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->rsskey = \md5(\random_bytes(60).$user->password);
        $user->save();

        return \to_route('user_security', ['username' => $user->username, 'hash' => '#rid'])
            ->withSuccess('Your RID Was Changed Successfully!');
    }

    /**
     * Change User API Token.
     */
    public function changeApiToken(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $user->api_token = Str::random(100);
        $user->save();

        return \to_route('user_security', ['username' => $user->username, 'hash' => '#api'])
            ->withSuccess('Your API Token Was Changed Successfully!');
    }

    /**
     * User Privacy Settings.
     */
    public function privacy(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        \abort_unless($request->user()->id == $user->id, 403);

        $groups = Group::where('level', '>', 0)->latest('level')->get();

        return \view('user.settings.privacy.index', ['user' => $user, 'groups'=> $groups]);
    }

    /**
     * Accept Site Rules.
     */
    public function acceptRules(Request $request): void
    {
        $user = $request->user();
        $user->read_rules = 1;
        $user->save();
    }
}
