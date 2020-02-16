<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Like::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'post_id' => function () {
            return factory(App\Models\Post::class)->create()->id;
        },
        'subtitle_id' => $faker->randomNumber(),
        'like'        => $faker->boolean,
        'dislike'     => $faker->boolean,
    ];
});
