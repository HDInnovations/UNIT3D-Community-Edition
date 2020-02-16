<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Forum::class, function (Faker $faker) {
    return [
        'position'                => $faker->randomNumber(),
        'num_topic'               => $faker->randomNumber(),
        'num_post'                => $faker->randomNumber(),
        'last_topic_id'           => $faker->randomNumber(),
        'last_topic_name'         => $faker->word,
        'last_topic_slug'         => $faker->word,
        'last_post_user_id'       => $faker->randomNumber(),
        'last_post_user_username' => $faker->word,
        'name'                    => $faker->name,
        'slug'                    => $faker->slug,
        'description'             => $faker->text,
        'parent_id'               => $faker->randomNumber(),
    ];
});
