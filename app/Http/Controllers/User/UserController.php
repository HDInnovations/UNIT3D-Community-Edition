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
use App\Models\Invite;
use App\Models\Peer;
use App\Models\User;
use Assada\Achievements\Model\AchievementProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $user = User::with(['privacy'])
            ->withCount([
                'torrents',
                'topics',
                'posts',
                'filledRequests' => fn ($query) => $query->whereNotNull('approved_by'),
                'requests',
                'userwarning as active_warnings_count'       => fn ($query) => $query->where('active', '=', 1),
                'userwarning as soft_deleted_warnings_count' => fn ($query) => $query->onlyTrashed(),
            ])
            ->with([
                'userban' => ['banneduser', 'staffuser'],
            ])
            ->where('username', '=', $username)
            ->when(auth()->user()->group->is_modo == true, fn ($query) => $query->withTrashed())
            ->sole();

        return view('user.profile.show', [
            'user'      => $user,
            'followers' => $user->followers()->latest()->limit(25)->get(),
            'history'   => DB::table('history')
                ->where('user_id', '=', $user->id)
                ->where('created_at', '>', $user->created_at)
                ->selectRaw('SUM(actual_uploaded) as upload_sum')
                ->selectRaw('SUM(uploaded) as credited_upload_sum')
                ->selectRaw('SUM(actual_downloaded) as download_sum')
                ->selectRaw('SUM(downloaded) as credited_download_sum')
                ->selectRaw('SUM(refunded_download) as refunded_download_sum')
                ->selectRaw('SUM(seedtime) as seedtime_sum')
                ->selectRaw('SUM(actual_downloaded > 0) as download_count')
                ->selectRaw('COUNT(*) as count')
                ->first(),
            'warnings' => $user
                ->userwarning()
                ->latest()
                ->paginate(2, ['*'], 'warningsPage'),
            'softDeletedWarnings' => $user
                ->userwarning()
                ->with(['torrenttitle', 'warneduser'])
                ->latest('created_at')
                ->onlyTrashed()
                ->paginate(2, ['*'], 'deletedWarningsPage'),
            'boughtUpload' => BonTransactions::where('sender', '=', $user->id)->where([['name', 'like', '%Upload%']])->sum('cost'),
            // 'boughtDownload'        => BonTransactions::where('sender', '=', $user->id)->where([['name', 'like', '%Download%']])->sum('cost'),
            'invitedBy' => Invite::where('accepted_by', '=', $user->id)->first(),
            'clients'   => $user->peers()
                ->select('agent', 'port')
                ->selectRaw('INET6_NTOA(ip) as ip, MIN(created_at) as created_at, MAX(updated_at) as updated_at, COUNT(*) as num_peers')
                ->groupBy(['ip', 'port', 'agent'])
                ->get(),
            'achievements' => AchievementProgress::with('details')
                ->where('achiever_id', '=', $user->id)
                ->whereNotNull('unlocked_at')
                ->get(),
            'peers' => Peer::query()
                ->selectRaw('SUM(seeder = 0) as leeching')
                ->selectRaw('SUM(seeder = 1) as seeding')
                ->where('user_id', '=', $user->id)
                ->first(),
            'watch' => $user->watchlist,
        ]);
    }

    /**
     * Edit Profile Form.
     */
    public function editProfileForm(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = User::where('username', '=', $username)->sole();

        abort_unless($request->user()->id == $user->id, 403);

        return view('user.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Edit User Profile.
     */
    public function editProfile(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('username', '=', $username)->sole();

        abort_unless($request->user()->id == $user->id, 403);

        // Avatar
        $maxUpload = config('image.max_upload_size');
        if ($request->hasFile('image') && $request->file('image')->getError() === 0) {
            $image = $request->file('image');
            if (\in_array($image->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'bmp', 'png', 'PNG', 'tiff', 'gif']) && preg_match('#image/*#', (string) $image->getMimeType())) {
                if ($maxUpload >= $image->getSize()) {
                    $filename = $user->username.'.'.$image->getClientOriginalExtension();
                    $path = public_path('/files/img/'.$filename);
                    if ($image->getClientOriginalExtension() !== 'gif') {
                        Image::make($image->getRealPath())->fit(150, 150)->encode('png', 100)->save($path);
                    } else {
                        $v = validator($request->all(), [
                            'image' => 'dimensions:ratio=1/1',
                        ]);
                        if ($v->passes()) {
                            $image->move(public_path('/files/img/'), $filename);
                        } else {
                            return to_route('users.show', ['username' => $user->username])
                                ->withErrors('Because you are uploading a GIF, your avatar must be square!');
                        }
                    }

                    $user->image = $user->username.'.'.$image->getClientOriginalExtension();
                } else {
                    return to_route('users.show', ['username' => $user->username])
                        ->withErrors('Your avatar is too large, max file size: '.($maxUpload / 1_000_000).' MB');
                }
            }
        }

        // Prevent User from abusing BBCODE Font Size (max. 99)
        $aboutTemp = $request->input('about');
        if (str_contains((string) $aboutTemp, '[size=') && preg_match('/\[size=[0-9]{3,}\]/', (string) $aboutTemp)) {
            return to_route('users.show', ['username' => $user->username])
                ->withErrors('Font size is too big!');
        }

        // Define data
        $user->title = $request->input('title');
        $user->about = $request->input('about');
        $user->signature = $request->input('signature');
        $user->save();

        return to_route('user_edit_profile_form', ['username' => $username])
            ->withSuccess('Your Account Was Updated Successfully!');
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
