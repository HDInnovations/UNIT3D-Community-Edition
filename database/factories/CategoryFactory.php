<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'slug'        => $faker->slug,
        'image'       => $faker->word,
        'position'    => $faker->randomNumber(),
        'icon'        => $faker->word,
        'no_meta'     => $faker->boolean,
        'music_meta'  => $faker->boolean,
        'game_meta'   => $faker->boolean,
        'tv_meta'     => $faker->boolean,
        'movie_meta'  => $faker->boolean,
        'num_torrent' => $faker->randomNumber(),
    ];
});
