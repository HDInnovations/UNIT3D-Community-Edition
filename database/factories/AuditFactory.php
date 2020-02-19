<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Audit::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'model_name'     => $faker->word,
        'model_entry_id' => $faker->randomNumber(),
        'action'         => $faker->word,
        'record'         => $faker->word,
    ];
});
