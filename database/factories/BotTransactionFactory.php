<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\BotTransaction::class, function (Faker $faker) {
    return [
        'type'    => $faker->word,
        'cost'    => $faker->randomFloat(),
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'bot_id' => function () {
            return factory(App\Models\Bot::class)->create()->id;
        },
        'to_user' => $faker->boolean,
        'to_bot'  => $faker->boolean,
        'comment' => $faker->text,
    ];
});
