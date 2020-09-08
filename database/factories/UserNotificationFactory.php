<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\UserNotification::class;

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
            'show_bon_gift'                => $this->faker->boolean,
            'show_mention_forum_post'      => $this->faker->boolean,
            'show_mention_article_comment' => $this->faker->boolean,
            'show_mention_request_comment' => $this->faker->boolean,
            'show_mention_torrent_comment' => $this->faker->boolean,
            'show_subscription_topic'      => $this->faker->boolean,
            'show_subscription_forum'      => $this->faker->boolean,
            'show_forum_topic'             => $this->faker->boolean,
            'show_following_upload'        => $this->faker->boolean,
            'show_request_bounty'          => $this->faker->boolean,
            'show_request_comment'         => $this->faker->boolean,
            'show_request_fill'            => $this->faker->boolean,
            'show_request_fill_approve'    => $this->faker->boolean,
            'show_request_fill_reject'     => $this->faker->boolean,
            'show_request_claim'           => $this->faker->boolean,
            'show_request_unclaim'         => $this->faker->boolean,
            'show_torrent_comment'         => $this->faker->boolean,
            'show_torrent_tip'             => $this->faker->boolean,
            'show_torrent_thank'           => $this->faker->boolean,
            'show_account_follow'          => $this->faker->boolean,
            'show_account_unfollow'        => $this->faker->boolean,
            'json_account_groups'          => $this->faker->word,
            'json_bon_groups'              => $this->faker->word,
            'json_mention_groups'          => $this->faker->word,
            'json_request_groups'          => $this->faker->word,
            'json_torrent_groups'          => $this->faker->word,
            'json_forum_groups'            => $this->faker->word,
            'json_following_groups'        => $this->faker->word,
            'json_subscription_groups'     => $this->faker->word,
        ];
    }
}
