<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\PlaylistTorrent::class, function (Faker $faker) {
    return [
        'position'    => $faker->randomNumber(),
        'playlist_id' => function () {
            return factory(App\Models\Playlist::class)->create()->id;
        },
        'torrent_id' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'tmdb_id' => $faker->randomNumber(),
    ];
});
