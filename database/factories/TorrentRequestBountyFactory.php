<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TorrentRequestBounty::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'seedbonus'   => $faker->randomFloat(),
        'requests_id' => $faker->randomNumber(),
        'anon'        => $faker->boolean,
        'request_id'  => function () {
            return factory(App\Models\TorrentRequest::class)->create()->id;
        },
    ];
});
