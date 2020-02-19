<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
        'content'    => $faker->text,
        'anon'       => $faker->randomNumber(),
        'torrent_id' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'article_id' => function () {
            return factory(App\Models\Article::class)->create()->id;
        },
        'requests_id' => function () {
            return factory(App\Models\TorrentRequest::class)->create()->id;
        },
        'playlist_id' => function () {
            return factory(App\Models\Playlist::class)->create()->id;
        },
        'collection_id' => $faker->randomNumber(),
        'user_id'       => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
