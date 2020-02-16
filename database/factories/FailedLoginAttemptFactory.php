<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\FailedLoginAttempt::class, function (Faker $faker) {
    return [
        'user_id'    => $faker->randomNumber(),
        'username'   => $faker->userName,
        'ip_address' => $faker->word,
    ];
});
