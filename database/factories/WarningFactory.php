<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Warning::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'warned_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'torrent' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'reason'     => $faker->text,
        'expires_on' => $faker->dateTime(),
        'active'     => $faker->boolean,
        'deleted_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
