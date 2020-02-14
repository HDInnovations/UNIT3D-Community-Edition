<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TorrentRequestClaim::class, function (Faker $faker) {
    return [
        'request_id' => $faker->randomNumber(),
        'username'   => $faker->userName,
        'anon'       => $faker->randomNumber(),
    ];
});
