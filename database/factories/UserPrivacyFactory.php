<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\UserPrivacy::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'show_achievement'           => $faker->boolean,
        'show_bon'                   => $faker->boolean,
        'show_comment'               => $faker->boolean,
        'show_download'              => $faker->boolean,
        'show_follower'              => $faker->boolean,
        'show_online'                => $faker->boolean,
        'show_peer'                  => $faker->boolean,
        'show_post'                  => $faker->boolean,
        'show_profile'               => $faker->boolean,
        'show_profile_about'         => $faker->boolean,
        'show_profile_achievement'   => $faker->boolean,
        'show_profile_badge'         => $faker->boolean,
        'show_profile_follower'      => $faker->boolean,
        'show_profile_title'         => $faker->boolean,
        'show_profile_bon_extra'     => $faker->boolean,
        'show_profile_comment_extra' => $faker->boolean,
        'show_profile_forum_extra'   => $faker->boolean,
        'show_profile_request_extra' => $faker->boolean,
        'show_profile_torrent_count' => $faker->boolean,
        'show_profile_torrent_extra' => $faker->boolean,
        'show_profile_torrent_ratio' => $faker->boolean,
        'show_profile_torrent_seed'  => $faker->boolean,
        'show_profile_warning'       => $faker->boolean,
        'show_rank'                  => $faker->boolean,
        'show_requested'             => $faker->boolean,
        'show_topic'                 => $faker->boolean,
        'show_upload'                => $faker->boolean,
        'show_wishlist'              => $faker->boolean,
        'json_profile_groups'        => $faker->word,
        'json_torrent_groups'        => $faker->word,
        'json_forum_groups'          => $faker->word,
        'json_bon_groups'            => $faker->word,
        'json_comment_groups'        => $faker->word,
        'json_wishlist_groups'       => $faker->word,
        'json_follower_groups'       => $faker->word,
        'json_achievement_groups'    => $faker->word,
        'json_rank_groups'           => $faker->word,
        'json_request_groups'        => $faker->word,
        'json_other_groups'          => $faker->word,
    ];
});
