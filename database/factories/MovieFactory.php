<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Movie;

class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'adult' => $this->faker->boolean,
            'backdrop' => $this->faker->word,
            'budget' => $this->faker->word,
            'homepage' => $this->faker->word,
            'imdb_id' => $this->faker->integer,
            'original_language' => $this->faker->word,
            'original_title' => $this->faker->word,
            'overview' => $this->faker->text,
            'popularity' => $this->faker->word,
            'poster' => $this->faker->word,
            'release_date' => $this->faker->date,
            'revenue' => $this->faker->word,
            'runtime' => $this->faker->word,
            'status' => $this->faker->word,
            'tagline' => $this->faker->word,
            'title' => $this->faker->sentence,
            'title_sort' => $this->faker->word,
            'tmdb_id' => $this->faker->integer,
            'vote_average' => $this->faker->word,
            'vote_count' => $this->faker->randomNumber,
        ];
    }
}
