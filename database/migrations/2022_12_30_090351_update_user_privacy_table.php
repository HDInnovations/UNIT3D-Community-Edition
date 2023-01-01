<?php

use App\Enums\UserGroups;
use App\Models\Group;
use App\Models\UserPrivacy;
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
            fn ($groupId, $isAllowed) => !$isAllowed && \in_array($groupId, $allowedGroups),
            ARRAY_FILTER_USE_BOTH
        ));

        foreach (UserPrivacy::all() as $user_privacy) {
            $user_privacy->json_profile_groups = $migrate($user_privacy->json_profile_groups['default_groups']);
            $user_privacy->json_torrent_groups = $migrate($user_privacy->json_torrent_groups['default_groups']);
            $user_privacy->json_forum_groups = $migrate($user_privacy->json_forum_groups['default_groups']);
            $user_privacy->json_bon_groups = $migrate($user_privacy->json_bon_groups['default_groups']);
            $user_privacy->json_comment_groups = $migrate($user_privacy->json_comment_groups['default_groups']);
            $user_privacy->json_wishlist_groups = $migrate($user_privacy->json_wishlist_groups['default_groups']);
            $user_privacy->json_follower_groups = $migrate($user_privacy->json_follower_groups['default_groups']);
            $user_privacy->json_achievement_groups = $migrate($user_privacy->json_achievement_groups['default_groups']);
            $user_privacy->json_rank_groups = $migrate($user_privacy->json_rank_groups['default_groups']);
            $user_privacy->json_request_groups = $migrate($user_privacy->json_request_groups['default_groups']);
            $user_privacy->json_other_groups = $migrate($user_privacy->json_other_groups['default_groups']);
            $user_privacy->save();
        }
    }
};
