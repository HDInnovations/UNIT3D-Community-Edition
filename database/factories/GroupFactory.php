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
        'is_owner'     => $faker->boolean,
        'is_admin'     => $faker->boolean,
        'is_modo'      => $faker->boolean,
        'is_trusted'   => $faker->boolean,
        'is_immune'    => $faker->boolean,
        'is_freeleech' => $faker->boolean,
        'can_upload'   => $faker->boolean,
        'is_incognito' => $faker->boolean,
        'autogroup'    => $faker->boolean,
    ];
});
