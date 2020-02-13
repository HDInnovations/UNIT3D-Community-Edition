<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\TagTorrent::class, function (Faker $faker) {
    return [
        'tag_name' => function () {
            return factory(App\Models\Tag::class)->create()->id;
        },
    ];
});
