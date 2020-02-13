<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
        'bot_id' => function () {
            return factory(App\Models\Bot::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'receiver_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'chatroom_id' => function () {
            return factory(App\Models\Chatroom::class)->create()->id;
        },
    ];
});
