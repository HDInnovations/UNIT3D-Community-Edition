<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'post_id' => function () {
                return Post::factory()->create()->id;
            },
            'subtitle_id' => $this->faker->randomNumber(),
            'like'        => $this->faker->boolean,
            'dislike'     => $this->faker->boolean,
        ];
    }
}
