<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Report::class, function (Faker $faker) {
    return [
        'type'        => $faker->word,
        'reporter_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'staff_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'title'         => $faker->word,
        'message'       => $faker->text,
        'solved'        => $faker->randomNumber(),
        'verdict'       => $faker->text,
        'reported_user' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'torrent_id' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'request_id' => function () {
            return factory(App\Models\TorrentRequest::class)->create()->id;
        },
    ];
});
