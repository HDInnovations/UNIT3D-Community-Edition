<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Group::class, function (Faker $faker) {
    return [
        'name'         => $faker->name,
        'slug'         => $faker->slug,
        'position'     => $faker->randomNumber(),
        'level'        => $faker->randomNumber(),
        'color'        => $faker->word,
        'icon'         => $faker->word,
        'effect'       => $faker->word,
        'is_internal'  => $faker->boolean,
        'is_owner'     => true, // For Staff Tests
        'is_admin'     => true, // For Staff Tests
        'is_modo'      => true, // For Staff Tests
        'is_trusted'   => $faker->boolean,
        'is_immune'    => $faker->boolean,
        'is_freeleech' => $faker->boolean,
        'can_upload'   => $faker->boolean,
        'is_incognito' => $faker->boolean,
        'autogroup'    => $faker->boolean,
    ];
});
