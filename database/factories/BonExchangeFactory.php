<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\BonExchange::class, function (Faker $faker) {
    return [
        'description'        => $faker->text,
        'value'              => $faker->randomNumber(),
        'cost'               => $faker->randomNumber(),
        'upload'             => $faker->boolean,
        'download'           => $faker->boolean,
        'personal_freeleech' => $faker->boolean,
        'invite'             => $faker->boolean,
    ];
});
