<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TagTorrent::class, function (Faker $faker) {
    return [
        'torrent_id' => $faker->randomNumber(),
        'tag_name'   => function () {
            return factory(App\Models\Tag::class)->create()->id;
        },
    ];
});
