<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TwoStepAuth::class, function (Faker $faker) {
    return [
        'userId'      => $faker->randomNumber(),
        'authCode'    => $faker->word,
        'authCount'   => $faker->randomNumber(),
        'authStatus'  => $faker->boolean,
        'authDate'    => $faker->dateTime(),
        'requestDate' => $faker->dateTime(),
    ];
});
