<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Season;

class SeasonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Season::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'air_date' => $this->faker->word,
            'created_at' => $this->faker->dateTime,
            'name' => $this->faker->name,
            'overview' => $this->faker->text,
            'poster' => $this->faker->word,
            'season_number' => $this->faker->randomNumber,
            'tv_id' => \App\Models\Tv::factory(),
            'updated_at' => $this->faker->dateTime,
        ];
    }
}
