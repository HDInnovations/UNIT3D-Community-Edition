<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    return [
        'forum_id' => function () {
            return factory(App\Models\Forum::class)->create()->id;
        },
        'first_post_user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
