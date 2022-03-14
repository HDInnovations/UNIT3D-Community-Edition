<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Recommendation;

class RecommendationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recommendation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'movie_id' => \App\Models\Movie::factory(),
            'title' => $this->faker->sentence,
            'tv_id' => \App\Models\Tv::factory(),
        ];
    }
}
