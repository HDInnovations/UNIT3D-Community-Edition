<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Ban::class, function (Faker $faker) {
    return [
        'owned_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'created_by' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'ban_reason'   => $faker->text,
        'unban_reason' => $faker->text,
        'removed_at'   => $faker->dateTime(),
    ];
});
