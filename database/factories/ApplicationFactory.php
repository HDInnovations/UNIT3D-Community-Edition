<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Application;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->email,
            'moderated_by' => \App\Models\User::factory(),
            'status' => $this->faker->boolean,
            'type' => $this->faker->word,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
