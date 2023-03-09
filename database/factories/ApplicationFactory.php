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
            'type'         => $this->faker->word(),
            'email'        => $this->faker->unique()->email(),
            'status'       => $this->faker->boolean(),
            'moderated_by' => \App\Models\User::factory(),
            'user_id'      => \App\Models\User::factory(),
        ];
    }
}
