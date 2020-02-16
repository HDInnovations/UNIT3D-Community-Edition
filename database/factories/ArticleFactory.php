<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Article::class, function (Faker $faker) {
    return [
        'title'   => $faker->word,
        'slug'    => $faker->slug,
        'image'   => $faker->word,
        'content' => $faker->text,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
