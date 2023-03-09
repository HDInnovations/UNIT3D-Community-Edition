<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content'          => $this->faker->text(),
            'anon'             => $this->faker->randomNumber(),
            'user_id'          => \App\Models\User::factory(),
            'commentable_type' => $this->faker->word(),
            'commentable_id'   => $this->faker->randomDigitNotNull(),
        ];
    }
}
