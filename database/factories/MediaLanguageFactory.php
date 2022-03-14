<?php

namespace Database\Factories;

use App\Models\MediaLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaLanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaLanguage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'name' => $this->faker->name,
        ];
    }
}
