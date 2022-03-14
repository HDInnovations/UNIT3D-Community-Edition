<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Chatroom;
use App\Models\ChatStatus;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'username'            => $this->faker->unique()->userName(),
            'email'               => $this->faker->unique()->safeEmail(),
            'password'            => \bcrypt('secret'),
            'passkey'             => \md5(\random_bytes(60)),
            'group_id'            => fn () => Group::factory()->create()->id,
            'active'              => true,
            'uploaded'            => $this->faker->randomNumber(),
            'downloaded'          => $this->faker->randomNumber(),
            'image'               => $this->faker->word(),
            'title'               => $this->faker->word(),
            'about'               => $this->faker->word(),
            'signature'           => $this->faker->text(),
            'fl_tokens'           => $this->faker->randomNumber(),
            'seedbonus'           => $this->faker->randomFloat(),
            'invites'             => $this->faker->randomNumber(),
            'hitandruns'          => $this->faker->randomNumber(),
            'rsskey'              => \md5(\random_bytes(60)),
            'chatroom_id'         => fn () => Chatroom::factory()->create()->id,
            'censor'              => $this->faker->boolean(),
            'chat_hidden'         => $this->faker->boolean(),
            'hidden'              => $this->faker->boolean(),
            'style'               => $this->faker->boolean(),
            'nav'                 => $this->faker->boolean(),
            'torrent_layout'      => $this->faker->boolean(),
            'torrent_filters'     => $this->faker->boolean(),
            'custom_css'          => $this->faker->word(),
            'ratings'             => $this->faker->boolean(),
            'read_rules'          => $this->faker->boolean(),
            'can_chat'            => $this->faker->boolean(),
            'can_comment'         => $this->faker->boolean(),
            'can_download'        => $this->faker->boolean(),
            'can_request'         => $this->faker->boolean(),
            'can_invite'          => $this->faker->boolean(),
            'can_upload'          => $this->faker->boolean(),
            'show_poster'         => $this->faker->boolean(),
            'peer_hidden'         => $this->faker->boolean(),
            'private_profile'     => $this->faker->boolean(),
            'block_notifications' => $this->faker->boolean(),
            'stat_hidden'         => $this->faker->boolean(),
            'twostep'             => false,
            'remember_token'      => Str::random(10),
            'api_token'           => $this->faker->uuid(),
            //'last_login'          => $this->faker->dateTime(),
            'last_action'         => $this->faker->dateTime(),
            //'disabled_at'         => $this->faker->dateTime(),
            //'deleted_by'          => $this->faker->randomNumber(),
            'locale'              => $this->faker->word(),
            'chat_status_id'      => fn () => ChatStatus::factory()->create()->id,
        ];
    }
}
