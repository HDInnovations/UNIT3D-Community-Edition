<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TvFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'backdrop'                => $this->faker->word,
            'count_existing_episodes' => $this->faker->randomNumber,
            'count_total_episodes'    => $this->faker->randomNumber,
            'episode_run_time'        => $this->faker->word,
            'first_air_date'          => $this->faker->word,
            'homepage'                => $this->faker->word,
            'imdb_id'                 => $this->faker->integer,
            'in_production'           => $this->faker->boolean,
            'last_air_date'           => $this->faker->word,
            'name'                    => $this->faker->name,
            'name_sort'               => $this->faker->word,
            'next_episode_to_air'     => $this->faker->word,
            'number_of_episodes'      => $this->faker->randomNumber,
            'number_of_seasons'       => $this->faker->randomNumber,
            'origin_country'          => $this->faker->word,
            'original_language'       => $this->faker->word,
            'original_name'           => $this->faker->word,
            'overview'                => $this->faker->text,
            'popularity'              => $this->faker->word,
            'poster'                  => $this->faker->word,
            'status'                  => $this->faker->word,
            'tmdb_id'                 => $this->faker->integer,
            'tvdb_id'                 => $this->faker->integer,
            'type'                    => $this->faker->word,
            'vote_average'            => $this->faker->word,
            'vote_count'              => $this->faker->randomNumber,
        ];
    }
}
