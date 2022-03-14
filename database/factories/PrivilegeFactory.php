<?php

namespace Database\Factories;

use App\Models\Privilege;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrivilegeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Privilege::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'     => $this->faker->word,
            'name'            => $this->faker->name,
            'position'        => $this->faker->randomNumber,
            'slug'            => $this->faker->unique()->slug,
            'system_required' => $this->faker->boolean,
        ];
    }
}
