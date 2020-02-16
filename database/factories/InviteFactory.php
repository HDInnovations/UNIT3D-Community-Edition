<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Invite::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'email'       => $faker->safeEmail,
        'code'        => $faker->word,
        'expires_on'  => $faker->dateTime(),
        'accepted_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'accepted_at' => $faker->dateTime(),
        'custom'      => $faker->text,
    ];
});
