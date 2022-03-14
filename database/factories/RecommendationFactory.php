<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecommendationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'movie_id' => \App\Models\Movie::factory(),
            'title'    => $this->faker->sentence,
            'tv_id'    => \App\Models\Tv::factory(),
        ];
    }
}
