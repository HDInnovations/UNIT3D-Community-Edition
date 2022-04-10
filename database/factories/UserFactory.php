<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function setupRole()
    {
        $role = Role::find(random_int(8, Role::orderByDesc('id')->select('id')->first()->id));

        return $role->id;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'about'               => $this->faker->text,
            'active'              => $this->faker->boolean,
            'api_token'           => $this->faker->unique()->lexify('??????????????????????'),
            'block_notifications' => $this->faker->boolean,
            'can_chat'            => $this->faker->boolean,
            'can_comment'         => $this->faker->boolean,
            'can_download'        => $this->faker->boolean,
            'can_invite'          => $this->faker->boolean,
            'can_request'         => $this->faker->boolean,
            'can_upload'          => $this->faker->boolean,
            'censor'              => $this->faker->boolean,
            'chat_hidden'         => $this->faker->boolean,
            'chat_status_id'      => \App\Models\ChatStatus::factory()->create()->id,
            'chatroom_id'         => \App\Models\Chatroom::factory()->create()->id,
            'custom_css'          => $this->faker->word,
            'deleted_by'          => $this->faker->randomNumber,
            'disabled_at'         => $this->faker->dateTime,
            'downloaded'          => $this->faker->randomNumber,
            'email'               => $this->faker->email,
            'fl_tokens'           => $this->faker->randomNumber,
            'hidden'              => $this->faker->boolean,
            'hitandruns'          => $this->faker->randomNumber,
            'image'               => $this->faker->image,
            'internal_id'         => \App\Models\Internal::factory()->create()->id,
            'invites'             => $this->faker->randomNumber,
            'last_action'         => $this->faker->dateTime,
            'last_login'          => $this->faker->dateTime,
            'locale'              => $this->faker->locale,
            'nav'                 => $this->faker->boolean,
            'own_flushes'         => $this->faker->boolean,
            'passkey'             => $this->faker->unique()->lexify('??????????????????????'),
            'password'            => Hash::make('password'),
            'peer_hidden'         => $this->faker->boolean,
            'private_profile'     => $this->faker->boolean,
            'ratings'             => $this->faker->boolean,
            'read_rules'          => $this->faker->boolean,
            'remember_token'      => $this->faker->word,
            'role_id'             => $this->setupRole(),
            'rsskey'              => $this->faker->unique()->lexify('??????????????????????'),
            'seedbonus'           => $this->faker->randomFloat,
            'show_poster'         => $this->faker->boolean,
            'signature'           => $this->faker->text,
            'standalone_css'      => $this->faker->word,
            'stat_hidden'         => $this->faker->boolean,
            'style'               => $this->faker->boolean,
            'title'               => $this->faker->sentence,
            'torrent_filters'     => $this->faker->boolean,
            'torrent_layout'      => $this->faker->boolean,
            'twostep'             => $this->faker->boolean,
            'uploaded'            => $this->faker->randomNumber,
            'username'            => $this->faker->unique()->username,
        ];
    }
}
