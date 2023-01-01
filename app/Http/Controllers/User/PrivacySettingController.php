<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Enums\UserGroups;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\UserPrivacy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrivacySettingController extends Controller
{
    /**
     * Update user privacy settings.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->id === $user->id, 403);

        $privacy = $user->privacy;

        if ($privacy === null) {
            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
        }

        $validGroups = Group::query()
            ->where('is_modo', '=', '0')
            ->where('is_admin', '=', '0')
            ->where('id', '!=', UserGroups::VALIDATING)
            ->where('id', '!=', UserGroups::PRUNED)
            ->where('id', '!=', UserGroups::BANNED)
            ->where('id', '!=', UserGroups::DISABLED)
            ->pluck('id');

        $request->validate([
            'show_achievement'           => 'required|boolean',
            'show_download'              => 'required|boolean',
            'show_follower'              => 'required|boolean',
            'show_online'                => 'required|boolean',
            'show_peer'                  => 'required|boolean',
            'show_post'                  => 'required|boolean',
            'show_profile_about'         => 'required|boolean',
            'show_profile_achievement'   => 'required|boolean',
            'show_profile_badge'         => 'required|boolean',
            'show_profile_follower'      => 'required|boolean',
            'show_profile_title'         => 'required|boolean',
            'show_profile_bon_extra'     => 'required|boolean',
            'show_profile_comment_extra' => 'required|boolean',
            'show_profile_forum_extra'   => 'required|boolean',
            'show_profile_request_extra' => 'required|boolean',
            'show_profile_torrent_count' => 'required|boolean',
            'show_profile_torrent_extra' => 'required|boolean',
            'show_profile_torrent_ratio' => 'required|boolean',
            'show_profile_torrent_seed'  => 'required|boolean',
            'show_profile_warning'       => 'required|boolean',
            'show_requested'             => 'required|boolean',
            'show_topic'                 => 'required|boolean',
            'show_upload'                => 'required|boolean',
            'json_profile_groups'        => 'array',
            'json_profile_groups.*'        => Rule::in($validGroups),
            'json_torrent_groups'        => 'array',
            'json_torrent_groups.*'      => Rule::in($validGroups),
            'json_forum_groups'          => 'array',
            'json_forum_groups.*'        => Rule::in($validGroups),
            'json_bon_groups'            => 'array',
            'json_bon_groups.*'          => Rule::in($validGroups),
            'json_comment_groups'        => 'array',
            'json_comment_groups.*'      => Rule::in($validGroups),
            'json_wishlist_groups'       => 'array',
            'json_wishlist_groups.*'     => Rule::in($validGroups),
            'json_follower_groups'       => 'array',
            'json_follower_groups.*'     => Rule::in($validGroups),
            'json_achievement_groups'    => 'array',
            'json_achievement_groups.*'  => Rule::in($validGroups),
            'json_rank_groups'           => 'array',
            'json_rank_groups.*'         => Rule::in($validGroups),
            'json_request_groups'        => 'array',
            'json_request_groups.*'      => Rule::in($validGroups),
            'json_other_groups'          => 'array',
            'json_other_groups.*'        => Rule::in($validGroups),
            'private_profile'            => 'required|boolean',
            'hidden'                     => 'required|boolean',
        ]);

        $privacy->show_achievement = $request->show_achievement;
        $privacy->show_download = $request->show_download;
        $privacy->show_follower = $request->show_follower;
        $privacy->show_online = $request->show_online;
        $privacy->show_peer = $request->show_peer;
        $privacy->show_post = $request->show_post;
        $privacy->show_profile_about = $request->show_profile_about;
        $privacy->show_profile_achievement = $request->show_profile_achievement;
        $privacy->show_profile_badge = $request->show_profile_badge;
        $privacy->show_profile_follower = $request->show_profile_follower;
        $privacy->show_profile_title = $request->show_profile_title;
        $privacy->show_profile_bon_extra = $request->show_profile_bon_extra;
        $privacy->show_profile_comment_extra = $request->show_profile_comment_extra;
        $privacy->show_profile_forum_extra = $request->show_profile_forum_extra;
        $privacy->show_profile_request_extra = $request->show_profile_request_extra;
        $privacy->show_profile_torrent_count = $request->show_profile_torrent_count;
        $privacy->show_profile_torrent_extra = $request->show_profile_torrent_extra;
        $privacy->show_profile_torrent_ratio = $request->show_profile_torrent_ratio;
        $privacy->show_profile_torrent_seed = $request->show_profile_torrent_seed;
        $privacy->show_profile_warning = $request->show_profile_warning;
        $privacy->show_requested = $request->show_requested;
        $privacy->show_topic = $request->show_topic;
        $privacy->show_upload = $request->show_upload;
        $privacy->json_profile_groups = \array_map('intval', $request->json_profile_groups ?? []);
        $privacy->json_torrent_groups = \array_map('intval', $request->json_torrent_groups ?? []);
        $privacy->json_forum_groups = \array_map('intval', $request->json_forum_groups ?? []);
        $privacy->json_bon_groups = \array_map('intval', $request->json_bon_groups ?? []);
        $privacy->json_comment_groups = \array_map('intval', $request->json_comment_groups ?? []);
        $privacy->json_wishlist_groups = \array_map('intval', $request->json_wishlist_groups ?? []);
        $privacy->json_follower_groups = \array_map('intval', $request->json_follower_groups ?? []);
        $privacy->json_achievement_groups = \array_map('intval', $request->json_achievement_groups ?? []);
        $privacy->json_rank_groups = \array_map('intval', $request->json_rank_groups ?? []);
        $privacy->json_request_groups = \array_map('intval', $request->json_request_groups ?? []);
        $privacy->json_other_groups = \array_map('intval', $request->json_other_groups ?? []);
        $privacy->save();

        $user->private_profile = $request->private_profile;
        $user->hidden = $request->hidden;
        $user->save();

        return \to_route('users.privacy_settings.edit', ['user' => $user])
            ->withSuccess('Your privacy settings have been successfully saved.');
    }

    /**
     * Edit user privacy settings.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        \abort_unless($request->user()->id == $user->id, 403);

        $groups = Group::query()
            ->where('is_modo', '=', '0')
            ->where('is_admin', '=', '0')
            ->where('id', '!=', UserGroups::VALIDATING)
            ->where('id', '!=', UserGroups::PRUNED)
            ->where('id', '!=', UserGroups::BANNED)
            ->where('id', '!=', UserGroups::DISABLED)
            ->latest('level')
            ->get();

        return \view('user.privacy_setting.edit', ['user' => $user, 'groups'=> $groups]);
    }
}
