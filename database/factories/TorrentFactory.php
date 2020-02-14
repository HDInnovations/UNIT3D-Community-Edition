<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Torrent::class, function (Faker $faker) {
    return [
        'name'            => $faker->name,
        'slug'            => $faker->slug,
        'description'     => $faker->text,
        'mediainfo'       => $faker->text,
        'bdinfo'          => $faker->text,
        'info_hash'       => $faker->word,
        'file_name'       => $faker->word,
        'num_file'        => $faker->randomNumber(),
        'size'            => $faker->randomFloat(),
        'nfo'             => $faker->text,
        'leechers'        => $faker->randomNumber(),
        'seeders'         => $faker->randomNumber(),
        'times_completed' => $faker->randomNumber(),
        'category_id'     => function () {
            return factory(App\Models\Category::class)->create()->id;
        },
        'announce' => $faker->word,
        'user_id'  => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'imdb'         => $faker->word,
        'tvdb'         => $faker->word,
        'tmdb'         => $faker->word,
        'mal'          => $faker->word,
        'igdb'         => $faker->word,
        'type'         => $faker->word,
        'resolution'   => $faker->randomNumber(),
        'stream'       => $faker->boolean,
        'free'         => $faker->boolean,
        'doubleup'     => $faker->boolean,
        'highspeed'    => $faker->boolean,
        'featured'     => $faker->boolean,
        'status'       => $faker->randomNumber(),
        'moderated_at' => $faker->dateTime(),
        'moderated_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'anon'         => $faker->randomNumber(),
        'sticky'       => $faker->randomNumber(),
        'sd'           => $faker->boolean,
        'internal'     => $faker->boolean,
        'release_year' => $faker->date(),
        'deleted_at'   => $faker->dateTime(),
        'uploader_id'  => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'type_id' => function () {
            return factory(App\Models\Type::class)->create()->id;
        },
    ];
});
