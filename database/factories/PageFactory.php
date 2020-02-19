<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Page::class, function (Faker $faker) {
    return [
        'name'    => $faker->name,
        'slug'    => $faker->slug,
        'content' => $faker->text,
    ];
});
