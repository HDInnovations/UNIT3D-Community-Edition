<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Permission::class, function (Faker $faker) {
    return [
        'forum_id' => function () {
            return factory(App\Models\Forum::class)->create()->id;
        },
        'group_id' => function () {
            return factory(App\Models\Group::class)->create()->id;
        },
        'show_forum'  => $faker->boolean,
        'read_topic'  => $faker->boolean,
        'reply_topic' => $faker->boolean,
        'start_topic' => $faker->boolean,
    ];
});
