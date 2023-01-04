<?php

use App\Enums\UserGroups;
use App\Models\Group;
use App\Models\UserNotification;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allowedGroups = Group::query()
            ->where('is_modo', '=', '0')
            ->where('is_admin', '=', '0')
            ->where('id', '!=', UserGroups::VALIDATING)
            ->where('id', '!=', UserGroups::PRUNED)
            ->where('id', '!=', UserGroups::BANNED)
            ->where('id', '!=', UserGroups::DISABLED)
            ->pluck('id')
            ->toArray();

        //
        // Input format looks like:
        // {
        //   "default_groups": {
        //     "1": 0,
        //     "2": 1,
        //     "3": 0,
        //     "4": 1,
        //   }
        // }
        //
        // Output format looks like:
        // [
        //   1,
        //   3,
        // ]
        //

        $migrate = fn ($groups) => array_keys(array_filter(
            $groups,
            fn ($groupId, $acceptsNotifications) => !$acceptsNotifications && \in_array($groupId, $allowedGroups),
            ARRAY_FILTER_USE_BOTH
        ));

        foreach (UserNotification::all() as $user_notification) {
            $user_notification->json_account_groups = $migrate($user_notification->json_account_groups['default_groups']);
            $user_notification->json_bon_groups = $migrate($user_notification->json_bon_groups['default_groups']);
            $user_notification->json_mention_groups = $migrate($user_notification->json_mention_groups['default_groups']);
            $user_notification->json_request_groups = $migrate($user_notification->json_request_groups['default_groups']);
            $user_notification->json_torrent_groups = $migrate($user_notification->json_torrent_groups['default_groups']);
            $user_notification->json_forum_groups = $migrate($user_notification->json_forum_groups['default_groups']);
            $user_notification->json_following_groups = $migrate($user_notification->json_following_groups['default_groups']);
            $user_notification->json_subscription_groups = $migrate($user_notification->json_subscription_groups['default_groups']);
            $user_notification->save();
        }
    }
};
