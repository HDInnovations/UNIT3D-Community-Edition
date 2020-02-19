<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TorrentRequest::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'category_id' => function () {
            return factory(App\Models\Category::class)->create()->id;
        },
        'type'        => $faker->word,
        'imdb'        => $faker->word,
        'tvdb'        => $faker->word,
        'tmdb'        => $faker->word,
        'mal'         => $faker->word,
        'igdb'        => $faker->word,
        'description' => $faker->text,
        'user_id'     => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'bounty'    => $faker->randomFloat(),
        'votes'     => $faker->randomNumber(),
        'claimed'   => $faker->boolean,
        'anon'      => $faker->boolean,
        'filled_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'filled_hash' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'filled_when' => $faker->dateTime(),
        'filled_anon' => $faker->boolean,
        'approved_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'approved_when' => $faker->dateTime(),
        'type_id'       => function () {
            return factory(App\Models\Type::class)->create()->id;
        },
    ];
});
