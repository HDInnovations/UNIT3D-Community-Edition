<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Person;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'adult' => $this->faker->word,
            'also_known_as' => $this->faker->text,
            'biography' => $this->faker->text,
            'birthday' => $this->faker->word,
            'deathday' => $this->faker->word,
            'gender' => $this->faker->word,
            'homepage' => $this->faker->word,
            'imdb_id' => $this->faker->integer,
            'known_for_department' => $this->faker->word,
            'name' => $this->faker->name,
            'place_of_birth' => $this->faker->word,
            'popularity' => $this->faker->word,
            'profile' => $this->faker->word,
            'still' => $this->faker->word,
        ];
    }
}
