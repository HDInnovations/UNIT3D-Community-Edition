<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\FreeleechToken::class, function (Faker $faker) {
    return [
        'user_id'    => $faker->randomNumber(),
        'torrent_id' => $faker->randomNumber(),
    ];
});
