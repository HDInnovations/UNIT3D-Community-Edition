<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Application::class, function (Faker $faker) {
    return [
        'type'         => $faker->word,
        'email'        => $faker->unique()->safeEmail,
        'referrer'     => $faker->text,
        'status'       => $faker->boolean,
        'moderated_at' => $faker->dateTime(),
        'moderated_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'accepted_by' => $faker->randomNumber(),
    ];
});
