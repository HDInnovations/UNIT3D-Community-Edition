<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\PrivateMessage::class, function (Faker $faker) {
    return [
        'sender_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'receiver_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'subject'    => $faker->word,
        'message'    => $faker->text,
        'read'       => $faker->boolean,
        'related_to' => $faker->randomNumber(),
    ];
});
