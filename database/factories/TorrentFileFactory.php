<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TorrentFile::class, function (Faker $faker) {
    return [
        'name'       => $faker->name,
        'size'       => $faker->randomNumber(),
        'torrent_id' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
    ];
});
