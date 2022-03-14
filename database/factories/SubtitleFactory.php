<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubtitleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'anon'        => $this->faker->boolean,
            'extension'   => $this->faker->word,
            'file_name'   => $this->faker->word,
            'file_size'   => $this->faker->randomNumber,
            'language_id' => \App\Models\MediaLanguage::factory(),
            'status'      => $this->faker->randomNumber,
            'title'       => $this->faker->sentence,
            'torrent_id'  => \App\Models\Torrent::factory(),
            'user_id'     => \App\Models\User::factory(),
            'verified'    => $this->faker->boolean,
        ];
    }
}
