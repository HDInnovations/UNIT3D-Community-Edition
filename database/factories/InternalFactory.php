<?php

namespace Database\Factories;

use App\Models\Internal;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Internal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'effect' => $this->faker->word,
            'icon'   => $this->faker->word,
            'name'   => $this->faker->unique()->name,
        ];
    }
}
