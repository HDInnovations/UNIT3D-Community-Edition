<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\UserNotification::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'show_bon_gift'                => $faker->boolean,
        'show_mention_forum_post'      => $faker->boolean,
        'show_mention_article_comment' => $faker->boolean,
        'show_mention_request_comment' => $faker->boolean,
        'show_mention_torrent_comment' => $faker->boolean,
        'show_subscription_topic'      => $faker->boolean,
        'show_subscription_forum'      => $faker->boolean,
        'show_forum_topic'             => $faker->boolean,
        'show_following_upload'        => $faker->boolean,
        'show_request_bounty'          => $faker->boolean,
        'show_request_comment'         => $faker->boolean,
        'show_request_fill'            => $faker->boolean,
        'show_request_fill_approve'    => $faker->boolean,
        'show_request_fill_reject'     => $faker->boolean,
        'show_request_claim'           => $faker->boolean,
        'show_request_unclaim'         => $faker->boolean,
        'show_torrent_comment'         => $faker->boolean,
        'show_torrent_tip'             => $faker->boolean,
        'show_torrent_thank'           => $faker->boolean,
        'show_account_follow'          => $faker->boolean,
        'show_account_unfollow'        => $faker->boolean,
        'json_account_groups'          => $faker->word,
        'json_bon_groups'              => $faker->word,
        'json_mention_groups'          => $faker->word,
        'json_request_groups'          => $faker->word,
        'json_torrent_groups'          => $faker->word,
        'json_forum_groups'            => $faker->word,
        'json_following_groups'        => $faker->word,
        'json_subscription_groups'     => $faker->word,
    ];
});
