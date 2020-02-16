<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Poll::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'title'           => $faker->word,
        'slug'            => $faker->slug,
        'ip_checking'     => $faker->boolean,
        'multiple_choice' => $faker->boolean,
    ];
});
