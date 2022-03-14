<?php

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'air_date'      => $this->faker->word,
            'created_at'    => $this->faker->dateTime,
            'name'          => $this->faker->name,
            'overview'      => $this->faker->text,
            'poster'        => $this->faker->word,
            'season_number' => $this->faker->randomNumber,
            'tv_id'         => \App\Models\Tv::factory(),
            'updated_at'    => $this->faker->dateTime,
        ];
    }
}
