<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use BotsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UsersTableSeeder;

/**
 * @see \App\Http\Controllers\API\ChatController
 */
class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function audibles_returns_an_ok_response()
    {
        $userAudible = factory(UserAudible::class)->create();

        $response = $this->actingAs($userAudible->user)->get('api/chat/audibles');

        $response->assertOk()
            ->assertJson(['data' => [[
                'id' => $userAudible['user_id'],
            ]]]);
    }

    /** @test */
    public function bot_messages_returns_an_ok_response()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(BotsTableSeeder::class);

        $user = factory(User::class)->create();

        $bot = Bot::where('slug', 'systembot')->firstOrFail();

        $systemUser = User::where('username', 'System')->firstOrFail();

        factory(Message::class)->create([
            'user_id'     => $user->id,
            'receiver_id' => $systemUser->id,
            'bot_id'      => $bot->id,
        ]);

        $response = $this->actingAs($user)->get(sprintf('api/chat/bot/%s', $bot->id));

        $response->assertOk()
            ->assertJson(['data' => [[
                'bot' => [
                    'id' => $bot->id,
                ],
                'receiver' => [
                    'id' => $systemUser->id,
                ],
                'user' => [
                    'id' => $user->id,
                ],
            ]]]);
    }

    /** @test */
    public function bots_returns_an_ok_response()
    {
        $this->seed(BotsTableSeeder::class);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/bots');

        $response->assertOk()
            ->assertJson(['data' => [
                ['slug' => 'systembot'],
                ['slug' => 'nerdbot'],
                ['slug' => 'casinobot'],
                ['slug' => 'betbot'],
                ['slug' => 'triviabot'],
            ]]);
    }

    /** @test */
    public function config_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/config');

        $response->assertOk()
            ->assertJson([
                'system_chatroom' => 'General',
                'message_limit'   => 100,
                'nerd_bot'        => true,
            ]);
    }

    /** @test */
    public function create_message_returns_an_ok_response()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(BotsTableSeeder::class);

        $user = factory(User::class)->create();

        $chatroom = factory(Chatroom::class)->create();

        $bot = Bot::where('slug', 'systembot')->firstOrFail();

        $systemUser = User::where('username', 'System')->firstOrFail();

        $response = $this->actingAs($user)->post('api/chat/messages', [
            'receiver_id' => $systemUser->id,
            'chatroom_id' => $chatroom->id,
            'bot_id'      => $bot->id,
            'message'     => sprintf('/msg %s hi', $user->username),
        ]);

        $response->assertOk()
            ->assertJson(['data' => [
                'user'     => ['id' => $user->id],
                'receiver' => ['id' => $user->id],
            ]]);
    }

    /** @test */
    public function delete_bot_echo_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $userEcho = factory(UserEcho::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->post(sprintf('api/chat/echoes/%s/delete/bot', $user->id), [
                'bot_id' => $userEcho['bot_id'],
            ]);

        $response->assertOk()
            ->assertJson([
                'id'       => $user->id,
                'username' => $user->username,
            ]);
    }

    /** @test */
    public function delete_message_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $message = factory(Message::class)->create();

        $response = $this->actingAs($user)->get(sprintf('api/chat/message/%s/delete', $message->id));

        $response->assertOk()
            ->assertSee('success');
    }

    /** @test */
    public function delete_room_echo_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $userEcho = factory(UserEcho::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(sprintf('api/chat/echoes/%s/delete/chatroom', $user->id), [
            'room_id' => $userEcho['room_id'],
        ]);

        $response->assertOk()
            ->assertJson([
                'id'       => $userEcho['room_id'],
                'username' => $user->username,
            ]);
    }

    /** @test */
    public function delete_target_echo_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $userEcho = factory(UserEcho::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(sprintf('api/chat/echoes/%s/delete/target', $user->id), [
            'target_id' => $userEcho['target_id'],
        ]);

        $response->assertOk()
            ->assertJson([
                'id'       => $user->id,
                'username' => $user->username,
            ]);
    }

    /** @test */
    public function echoes_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/echoes');

        $response->assertOk()
            ->assertJson(['data' => [[
                'user_id' => $user->id,
                'user'    => [
                    'id'       => $user->id,
                    'username' => $user->username,
                ],
            ]]]);
    }

    /** @test */
    public function messages_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/messages/{room_id}');

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /** @test */
    public function private_messages_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/private/messages/{target_id}');

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /** @test */
    public function rooms_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/rooms');

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /** @test */
    public function statuses_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/chat/statuses');

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /** @test */
    public function toggle_bot_audible_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $userAudible = factory(UserAudible::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(sprintf('api/chat/audibles/%s/toggle/bot', $user->id), [
            'bot_id' => $userAudible['bot_id'],
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /** @test */
    public function toggle_room_audible_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $userAudible = factory(UserAudible::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(sprintf('api/chat/audibles/%s/toggle/chatroom', $user->id), [
            'room_id' => $userAudible['room_id'],
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    public function toggle_target_audible_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/chat/audibles/{user_id}/toggle/target', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    public function update_user_chat_status_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/chat/user/{id}/status', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    public function update_user_room_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/chat/user/{id}/chatroom', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    public function update_user_target_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/chat/user/{id}/target', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
