<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'chatroom_id' => function () {
            return factory(App\Models\Chatroom::class)->create()->id;
        },
        'receiver_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'bot_id' => function () {
            return factory(App\Models\Bot::class)->create()->id;
        },
        'message' => $faker->text,
    ];
});
