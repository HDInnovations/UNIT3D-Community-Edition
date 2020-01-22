<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Subscription::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'topic_id' => function () {
            return factory(App\Models\Topic::class)->create()->id;
        },
        'forum_id' => function () {
            return factory(App\Models\Forum::class)->create()->id;
        },
    ];
});
