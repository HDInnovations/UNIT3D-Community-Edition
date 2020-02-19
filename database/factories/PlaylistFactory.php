<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Playlist::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'name'        => $faker->name,
        'description' => $faker->text,
        'cover_image' => $faker->word,
        'position'    => $faker->randomNumber(),
        'is_private'  => $faker->boolean,
        'is_pinned'   => $faker->boolean,
        'is_featured' => $faker->boolean,
    ];
});
