<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Option::class, function (Faker $faker) {
    return [
        'poll_id' => function () {
            return factory(App\Models\Poll::class)->create()->id;
        },
        'name'  => $faker->name,
        'votes' => $faker->randomNumber(),
    ];
});
