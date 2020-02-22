<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
        'email'    => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'passkey'  => $faker->word,
        'group_id' => function () {
            return factory(App\Models\Group::class)->create()->id;
        },
        'active'      => true,
        'uploaded'    => $faker->randomNumber(),
        'downloaded'  => $faker->randomNumber(),
        'image'       => $faker->word,
        'title'       => $faker->word,
        'about'       => $faker->word,
        'signature'   => $faker->text,
        'fl_tokens'   => $faker->randomNumber(),
        'seedbonus'   => $faker->randomFloat(),
        'invites'     => $faker->randomNumber(),
        'hitandruns'  => $faker->randomNumber(),
        'rsskey'      => $faker->word,
        'chatroom_id' => function () {
            return factory(App\Models\Chatroom::class)->create()->id;
        },
        'censor'              => $faker->boolean,
        'chat_hidden'         => $faker->boolean,
        'hidden'              => $faker->boolean,
        'style'               => $faker->boolean,
        'nav'                 => $faker->boolean,
        'torrent_layout'      => $faker->boolean,
        'torrent_filters'     => $faker->boolean,
        'custom_css'          => $faker->word,
        'ratings'             => $faker->boolean,
        'read_rules'          => $faker->boolean,
        'can_chat'            => $faker->boolean,
        'can_comment'         => $faker->boolean,
        'can_download'        => $faker->boolean,
        'can_request'         => $faker->boolean,
        'can_invite'          => $faker->boolean,
        'can_upload'          => $faker->boolean,
        'show_poster'         => $faker->boolean,
        'peer_hidden'         => $faker->boolean,
        'private_profile'     => $faker->boolean,
        'block_notifications' => $faker->boolean,
        'stat_hidden'         => $faker->boolean,
        'twostep'             => false,
        'remember_token'      => Str::random(10),
        'api_token'           => $faker->uuid,
        //'last_login'          => $faker->dateTime(),
        'last_action'         => $faker->dateTime(),
        //'disabled_at'         => $faker->dateTime(),
        //'deleted_by'          => $faker->randomNumber(),
        'locale'              => $faker->word,
        'chat_status_id'      => function () {
            return factory(App\Models\ChatStatus::class)->create()->id;
        },
    ];
});
