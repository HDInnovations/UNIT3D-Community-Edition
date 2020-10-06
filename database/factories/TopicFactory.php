<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'               => $this->faker->name,
            'slug'               => $this->faker->slug,
            'state'              => $this->faker->word,
            'pinned'             => $this->faker->boolean,
            'approved'           => $this->faker->boolean,
            'denied'             => $this->faker->boolean,
            'solved'             => $this->faker->boolean,
            'invalid'            => $this->faker->boolean,
            'bug'                => $this->faker->boolean,
            'suggestion'         => $this->faker->boolean,
            'implemented'        => $this->faker->boolean,
            'num_post'           => $this->faker->randomNumber(),
            'first_post_user_id' => function () {
                return User::factory()->create()->id;
            },
            'last_post_user_id'        => $this->faker->randomNumber(),
            'first_post_user_username' => $this->faker->word,
            'last_post_user_username'  => $this->faker->word,
            'last_reply_at'            => $this->faker->dateTime(),
            'views'                    => $this->faker->randomNumber(),
            'forum_id'                 => function () {
                return Forum::factory()->create()->id;
            },
        ];
    }
}
