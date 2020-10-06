<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Forum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ForumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Forum::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'position'                => $this->faker->randomNumber(),
            'num_topic'               => $this->faker->randomNumber(),
            'num_post'                => $this->faker->randomNumber(),
            'last_topic_id'           => $this->faker->randomNumber(),
            'last_topic_name'         => $this->faker->word,
            'last_topic_slug'         => $this->faker->word,
            'last_post_user_id'       => $this->faker->randomNumber(),
            'last_post_user_username' => $this->faker->word,
            'name'                    => $this->faker->name,
            'slug'                    => $this->faker->slug,
            'description'             => $this->faker->text,
            'parent_id'               => $this->faker->randomNumber(),
        ];
    }
}
