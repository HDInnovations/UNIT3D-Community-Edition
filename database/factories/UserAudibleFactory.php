<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\UserAudible::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'room_id' => function () {
            return factory(App\Models\Chatroom::class)->create()->id;
        },
        'target_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'bot_id' => function () {
            return factory(App\Models\Bot::class)->create()->id;
        },
        'status' => $faker->boolean,
    ];
});
