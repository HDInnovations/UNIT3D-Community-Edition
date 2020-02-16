<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Rss::class, function (Faker $faker) {
    return [
        'position' => $faker->randomNumber(),
        'name'     => $faker->name,
        'user_id'  => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'staff_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'is_private'   => $faker->boolean,
        'is_torrent'   => $faker->boolean,
        'json_torrent' => $faker->word,
    ];
});
