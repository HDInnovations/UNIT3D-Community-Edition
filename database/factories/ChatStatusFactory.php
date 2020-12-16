<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\ChatStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'  => $this->faker->unique()->name,
            'color' => $this->faker->unique()->word,
            'icon'  => $this->faker->word,
        ];
    }
}
