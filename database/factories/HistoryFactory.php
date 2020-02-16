<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\History::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'agent'     => $faker->word,
        'info_hash' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'uploaded'          => $faker->randomNumber(),
        'actual_uploaded'   => $faker->randomNumber(),
        'client_uploaded'   => $faker->randomNumber(),
        'downloaded'        => $faker->randomNumber(),
        'actual_downloaded' => $faker->randomNumber(),
        'client_downloaded' => $faker->randomNumber(),
        'seeder'            => $faker->boolean,
        'active'            => $faker->boolean,
        'seedtime'          => $faker->randomNumber(),
        'immune'            => $faker->boolean,
        'hitrun'            => $faker->boolean,
        'prewarn'           => $faker->boolean,
        'completed_at'      => $faker->dateTime(),
        'deleted_at'        => $faker->dateTime(),
    ];
});
