<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    return [
        'name'               => $faker->name,
        'slug'               => $faker->slug,
        'state'              => $faker->word,
        'pinned'             => $faker->boolean,
        'approved'           => $faker->boolean,
        'denied'             => $faker->boolean,
        'solved'             => $faker->boolean,
        'invalid'            => $faker->boolean,
        'bug'                => $faker->boolean,
        'suggestion'         => $faker->boolean,
        'implemented'        => $faker->boolean,
        'num_post'           => $faker->randomNumber(),
        'first_post_user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'last_post_user_id'        => $faker->randomNumber(),
        'first_post_user_username' => $faker->word,
        'last_post_user_username'  => $faker->word,
        'last_reply_at'            => $faker->dateTime(),
        'views'                    => $faker->randomNumber(),
        'forum_id'                 => function () {
            return factory(App\Models\Forum::class)->create()->id;
        },
    ];
});
