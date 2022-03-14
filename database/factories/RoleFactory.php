<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'color'           => $this->faker->word,
            'description'     => $this->faker->text,
            'download_slots'  => $this->faker->randomNumber,
            'effect'          => $this->faker->word,
            'icon'            => $this->faker->word,
            'level'           => $this->faker->randomNumber,
            'name'            => $this->faker->name,
            'position'        => $this->faker->randomNumber,
            'rule_id'         => $this->faker->integer,
            'slug'            => $this->faker->unique()->slug,
            'system_required' => $this->faker->boolean,
        ];
    }
}
