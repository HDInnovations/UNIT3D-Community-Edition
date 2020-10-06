<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Tag;
use App\Models\TagTorrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagTorrentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TagTorrent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'torrent_id' => $this->faker->randomNumber(),
            'tag_name'   => function () {
                return Tag::factory()->create()->id;
            },
        ];
    }
}
