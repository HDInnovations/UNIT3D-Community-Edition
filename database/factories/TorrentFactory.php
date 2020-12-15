<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Category;
use App\Models\Resolution;
use App\Models\Torrent;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TorrentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Torrent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'            => $this->faker->name,
            'slug'            => $this->faker->slug,
            'description'     => $this->faker->text,
            'mediainfo'       => $this->faker->text,
            'info_hash'       => $this->faker->word,
            'file_name'       => $this->faker->word,
            'num_file'        => $this->faker->randomNumber(),
            'size'            => $this->faker->randomFloat(),
            'nfo'             => $this->faker->text,
            'leechers'        => $this->faker->randomNumber(),
            'seeders'         => $this->faker->randomNumber(),
            'times_completed' => $this->faker->randomNumber(),
            'category_id'     => function () {
                return Category::factory()->create()->id;
            },
            'announce' => $this->faker->word,
            'user_id'  => function () {
                return User::factory()->create()->id;
            },
            'imdb'         => $this->faker->randomNumber(),
            'tvdb'         => $this->faker->randomNumber(),
            'tmdb'         => $this->faker->randomNumber(),
            'mal'          => $this->faker->randomNumber(),
            'igdb'         => $this->faker->randomNumber(),
            'type_id'      => function () {
                return Type::factory()->create()->id;
            },
            'resolution_id'      => function () {
                return Resolution::factory()->create()->id;
            },
            'stream'       => $this->faker->boolean,
            'free'         => $this->faker->boolean,
            'doubleup'     => $this->faker->boolean,
            'highspeed'    => $this->faker->boolean,
            'featured'     => $this->faker->boolean,
            'status'       => 1,
            'moderated_at' => \now(),
            'moderated_by' => 1,
            'anon'         => $this->faker->boolean,
            'sticky'       => $this->faker->boolean,
            'sd'           => $this->faker->boolean,
            'internal'     => $this->faker->boolean,
            'release_year' => $this->faker->date('Y'),
        ];
    }
}
