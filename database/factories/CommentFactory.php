<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use App\Models\Comment;
use App\Models\Torrent;
use App\Models\Article;
use App\Models\Playlist;
use App\Models\TorrentRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content'    => $this->faker->text,
            'anon'       => (int) $this->faker->boolean(),
            'torrent_id' => function () {
                return Torrent::factory()->create()->id;
            },
            'article_id' => function () {
                return Article::factory()->create()->id;
            },
            'requests_id' => function () {
                return TorrentRequest::factory()->create()->id;
            },
            'playlist_id' => function () {
                return Playlist::factory()->create()->id;
            },
            'user_id'       => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
