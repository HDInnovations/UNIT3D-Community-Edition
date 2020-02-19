<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Voter::class, function (Faker $faker) {
    return [
        'poll_id' => function () {
            return factory(App\Models\Poll::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'ip_address' => $faker->word,
    ];
});
