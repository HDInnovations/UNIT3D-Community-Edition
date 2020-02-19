<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Peer::class, function (Faker $faker) {
    return [
        'peer_id'     => $faker->word,
        'md5_peer_id' => $faker->word,
        'info_hash'   => $faker->word,
        'ip'          => $faker->word,
        'port'        => $faker->randomNumber(),
        'agent'       => $faker->word,
        'uploaded'    => $faker->randomNumber(),
        'downloaded'  => $faker->randomNumber(),
        'left'        => $faker->randomNumber(),
        'seeder'      => $faker->boolean,
        'torrent_id'  => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'torrents.id' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
    ];
});
