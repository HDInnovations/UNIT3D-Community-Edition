<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use App\Models\User;
use App\Models\Group;
use App\Models\Chatroom;
use App\Models\ChatStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'email'    => $this->faker->unique()->safeEmail,
            'password' => bcrypt('secret'),
            'passkey'  => $this->faker->word,
            'group_id' => function () {
                return Group::factory()->create()->id;
            },
            'active'      => true,
            'uploaded'    => $this->faker->randomNumber(),
            'downloaded'  => $this->faker->randomNumber(),
            'image'       => $this->faker->word,
            'title'       => $this->faker->word,
            'about'       => $this->faker->word,
            'signature'   => $this->faker->text,
            'fl_tokens'   => $this->faker->randomNumber(),
            'seedbonus'   => $this->faker->randomFloat(),
            'invites'     => $this->faker->randomNumber(),
            'hitandruns'  => $this->faker->randomNumber(),
            'rsskey'      => $this->faker->word,
            'chatroom_id' => function () {
                return Chatroom::factory()->create()->id;
            },
            'censor'              => $this->faker->boolean,
            'chat_hidden'         => $this->faker->boolean,
            'hidden'              => $this->faker->boolean,
            'style'               => $this->faker->boolean,
            'nav'                 => $this->faker->boolean,
            'torrent_layout'      => $this->faker->boolean,
            'torrent_filters'     => $this->faker->boolean,
            'custom_css'          => $this->faker->word,
            'ratings'             => $this->faker->boolean,
            'read_rules'          => $this->faker->boolean,
            'can_chat'            => $this->faker->boolean,
            'can_comment'         => $this->faker->boolean,
            'can_download'        => $this->faker->boolean,
            'can_request'         => $this->faker->boolean,
            'can_invite'          => $this->faker->boolean,
            'can_upload'          => $this->faker->boolean,
            'show_poster'         => $this->faker->boolean,
            'peer_hidden'         => $this->faker->boolean,
            'private_profile'     => $this->faker->boolean,
            'block_notifications' => $this->faker->boolean,
            'stat_hidden'         => $this->faker->boolean,
            'twostep'             => false,
            'remember_token'      => Str::random(10),
            'api_token'           => $this->faker->uuid,
            //'last_login'          => $this->faker->dateTime(),
            'last_action'         => $this->faker->dateTime(),
            //'disabled_at'         => $this->faker->dateTime(),
            //'deleted_by'          => $this->faker->randomNumber(),
            'locale'              => $this->faker->word,
            'chat_status_id'      => function () {
                return ChatStatus::factory()->create()->id;
            },
        ];
    }
}
