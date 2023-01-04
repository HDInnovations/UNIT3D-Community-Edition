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
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationSettingController extends Controller
{
    /**
     * Update user notification settings.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->id === $user->id, 403);

        $notification = $user->notification;

        if ($notification === null) {
            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
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
            'show_account_follow'          => 'required|boolean',
            'show_account_unfollow'        => 'required|boolean',
            'show_following_upload'        => 'required|boolean',
            'show_bon_gift'                => 'required|boolean',
            'show_subscription_forum'      => 'required|boolean',
            'show_subscription_topic'      => 'required|boolean',
            'show_request_comment'         => 'required|boolean',
            'show_request_bounty'          => 'required|boolean',
            'show_request_fill'            => 'required|boolean',
            'show_request_fill_approve'    => 'required|boolean',
            'show_request_fill_reject'     => 'required|boolean',
            'show_request_claim'           => 'required|boolean',
            'show_request_unclaim'         => 'required|boolean',
            'show_torrent_comment'         => 'required|boolean',
            'show_torrent_thank'           => 'required|boolean',
            'show_torrent_tip'             => 'required|boolean',
            'show_mention_torrent_comment' => 'required|boolean',
            'show_mention_request_comment' => 'required|boolean',
            'show_mention_article_comment' => 'required|boolean',
            'show_mention_forum_post'      => 'required|boolean',
            'show_forum_topic'             => 'required|boolean',
            'json_account_groups'          => 'array',
            'json_account_groups.*'        => Rule::in($validGroups),
            'json_bon_groups'              => 'array',
            'json_bon_groups.*'            => Rule::in($validGroups),
            'json_following_groups'        => 'array',
            'json_following_groups.*'      => Rule::in($validGroups),
            'json_forum_groups'            => 'array',
            'json_forum_groups.*'          => Rule::in($validGroups),
            'json_request_groups'          => 'array',
            'json_request_groups.*'        => Rule::in($validGroups),
            'json_subscription_groups'     => 'array',
            'json_subscription_groups.*'   => Rule::in($validGroups),
            'json_torrent_groups'          => 'array',
            'json_torrent_groups.*'        => Rule::in($validGroups),
            'json_mention_groups'          => 'array',
            'json_mention_groups.*'        => Rule::in($validGroups),
            'block_notifications'          => 'required|boolean',
        ]);

        $notification->show_account_follow = $request->show_account_follow;
        $notification->show_account_unfollow = $request->show_account_unfollow;
        $notification->show_following_upload = $request->show_following_upload;
        $notification->show_bon_gift = $request->show_bon_gift;
        $notification->show_subscription_forum = $request->show_subscription_forum;
        $notification->show_subscription_topic = $request->show_subscription_topic;
        $notification->show_request_comment = $request->show_request_comment;
        $notification->show_request_bounty = $request->show_request_bounty;
        $notification->show_request_fill = $request->show_request_fill;
        $notification->show_request_fill_approve = $request->show_request_fill_approve;
        $notification->show_request_fill_reject = $request->show_request_fill_reject;
        $notification->show_request_claim = $request->show_request_claim;
        $notification->show_request_unclaim = $request->show_request_unclaim;
        $notification->show_torrent_comment = $request->show_torrent_comment;
        $notification->show_torrent_thank = $request->show_torrent_thank;
        $notification->show_torrent_tip = $request->show_torrent_tip;
        $notification->show_mention_torrent_comment = $request->show_mention_torrent_comment;
        $notification->show_mention_request_comment = $request->show_mention_request_comment;
        $notification->show_mention_article_comment = $request->show_mention_article_comment;
        $notification->show_mention_forum_post = $request->show_mention_forum_post;
        $notification->show_forum_topic = $request->show_forum_topic;
        $notification->json_account_groups = \array_map('intval', $request->json_account_groups ?? []);
        $notification->json_bon_groups = \array_map('intval', $request->json_bon_groups ?? []);
        $notification->json_following_groups = \array_map('intval', $request->json_following_groups ?? []);
        $notification->json_forum_groups = \array_map('intval', $request->json_forum_groups ?? []);
        $notification->json_request_groups = \array_map('intval', $request->json_request_groups ?? []);
        $notification->json_subscription_groups = \array_map('intval', $request->json_subscription_groups ?? []);
        $notification->json_torrent_groups = \array_map('intval', $request->json_torrent_groups ?? []);
        $notification->json_mention_groups = \array_map('intval', $request->json_mention_groups ?? []);
        $notification->save();

        $user->block_notifications = $request->block_notifications;
        $user->save();

        return \to_route('users.notification_settings.edit', ['user' => $user])
            ->withSuccess('Your notification settings have been successfully saved.');
    }

    /**
     * Edit user notification settings.
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

        return \view('user.notification_setting.edit', ['user' => $user, 'groups'=> $groups]);
    }
}
