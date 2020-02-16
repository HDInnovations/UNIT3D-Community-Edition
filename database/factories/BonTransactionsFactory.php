<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\BonTransactions::class, function (Faker $faker) {
    return [
        'itemID' => function () {
            return factory(App\Models\BonExchange::class)->create()->id;
        },
        'name'   => $faker->name,
        'cost'   => $faker->randomFloat(),
        'sender' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'receiver' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'torrent_id'    => $faker->randomNumber(),
        'donation_id'   => $faker->randomNumber(),
        'post_id'       => $faker->randomNumber(),
        'comment'       => $faker->text,
        'date_actioned' => $faker->dateTime(),
    ];
});
