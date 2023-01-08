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
use App\Models\TorrentRequest;
use App\Models\User;
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

        \cache()->forget('user:'.$user->passkey);

        return \to_route('user_security', ['username' => $user->username, 'hash' => '#pid'])
            ->withSuccess('Your PID Was Changed Successfully!');
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
     * Accept Site Rules.
     */
    public function acceptRules(Request $request): void
    {
        $user = $request->user();
        $user->read_rules = 1;
        $user->save();
    }
}
