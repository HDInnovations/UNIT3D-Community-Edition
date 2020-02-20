<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\ChatStatus::class, function (Faker $faker) {
    return [
        'name'  => $faker->unique()->name,
        'color' => $faker->unique()->word,
        'icon'  => $faker->word,
    ];
});
