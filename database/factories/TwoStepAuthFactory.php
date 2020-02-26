<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\TwoStepAuth::class, function (Faker $faker) {
    return [
        'userId'      => $faker->randomNumber(),
        'authCode'    => sprintf('%s%s%s%s', $faker->numberBetween(0, 9), $faker->numberBetween(0, 9), $faker->numberBetween(0, 9), $faker->numberBetween(0, 9)),
        'authCount'   => 0,
        'authStatus'  => false,
        'authDate'    => null,
        'requestDate' => Carbon::now(),
    ];
});
