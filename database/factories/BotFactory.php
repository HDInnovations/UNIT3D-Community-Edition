<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Bot::class, function (Faker $faker) {
    return [
        'position'     => $faker->randomNumber(),
        'slug'         => $faker->slug,
        'name'         => $faker->name,
        'command'      => $faker->word,
        'color'        => $faker->word,
        'icon'         => $faker->word,
        'emoji'        => $faker->word,
        'info'         => $faker->word,
        'about'        => $faker->word,
        'help'         => $faker->text,
        'active'       => $faker->boolean,
        'is_protected' => $faker->boolean,
        'is_triviabot' => $faker->boolean,
        'is_nerdbot'   => $faker->boolean,
        'is_systembot' => $faker->boolean,
        'is_casinobot' => $faker->boolean,
        'is_betbot'    => $faker->boolean,
        'uploaded'     => $faker->randomNumber(),
        'downloaded'   => $faker->randomNumber(),
        'fl_tokens'    => $faker->randomNumber(),
        'seedbonus'    => $faker->randomFloat(),
        'invites'      => $faker->randomNumber(),
    ];
});
