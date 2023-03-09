<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Episode;

class EpisodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Episode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->name(),
            'season_number' => $this->faker->randomNumber(),
            'season_id'     => \App\Models\Season::factory(),
            'tv_id'         => $this->faker->randomDigitNotNull(),
        ];
    }
}
