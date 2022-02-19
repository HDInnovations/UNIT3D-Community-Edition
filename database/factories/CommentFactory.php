<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Article;
use App\Models\Playlist;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'content'       => $this->faker->text(),
            'anon'          => (int) $this->faker->boolean(),
            'torrent_id'    => fn () => Torrent::factory()->create()->id,
            'article_id'    => fn () => Article::factory()->create()->id,
            'requests_id'   => fn () => TorrentRequest::factory()->create()->id,
            'playlist_id'   => fn () => Playlist::factory()->create()->id,
            'user_id'       => fn () => User::factory()->create()->id,
        ];
    }
}
