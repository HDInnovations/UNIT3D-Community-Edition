<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Image::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'album_id'    => $faker->randomNumber(),
        'image'       => $faker->word,
        'description' => $faker->text,
        'type'        => $faker->word,
        'downloads'   => $faker->randomNumber(),
    ];
});
