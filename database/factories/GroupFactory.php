<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'             => $this->faker->name,
            'slug'             => $this->faker->slug,
            'position'         => $this->faker->randomNumber(),
            'level'            => $this->faker->randomNumber(),
            'color'            => $this->faker->word,
            'icon'             => $this->faker->word,
            'effect'           => $this->faker->word,
            'is_internal'      => $this->faker->boolean,
            'is_owner'         => $this->faker->boolean,
            'is_admin'         => $this->faker->boolean,
            'is_modo'          => $this->faker->boolean,
            'is_trusted'       => $this->faker->boolean,
            'is_immune'        => $this->faker->boolean,
            'is_freeleech'     => $this->faker->boolean,
            'is_double_upload' => $this->faker->boolean,
            'can_upload'       => $this->faker->boolean,
            'is_incognito'     => $this->faker->boolean,
            'autogroup'        => $this->faker->boolean,
        ];
    }
}
