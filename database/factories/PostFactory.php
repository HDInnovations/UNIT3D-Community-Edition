<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'topic_id' => function () {
            return factory(App\Models\Topic::class)->create()->id;
        },
    ];
});
