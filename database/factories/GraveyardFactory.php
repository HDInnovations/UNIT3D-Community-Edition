<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Torrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class GraveyardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Graveyard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'user_id' => function () {
            return User::factory()->create()->id;
        },
        'torrent_id' => function () {
            return Torrent::factory()->create()->id;
        },
        'seedtime' => $this->faker->randomNumber(),
        'rewarded' => $this->faker->boolean,
    ];
    }
}
