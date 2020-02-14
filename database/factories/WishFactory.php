<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Wish::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'title'  => $faker->word,
        'imdb'   => $faker->word,
        'type'   => $faker->word,
        'source' => $faker->word,
    ];
});
