<?php

use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\ChatStatus;
use App\Models\Message;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use Database\Seeders\BotsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

/**
 * @see \App\Http\Controllers\API\ChatController
 */
beforeEach(function () {
});

test('audibles returns an ok response', function () {
    $userAudible = UserAudible::factory()->create();

    $response = $this->actingAs($userAudible->user)->get('api/chat/audibles');

    $response->assertOk()
        ->assertJson(['data' => [[
            'id'      => $userAudible->id,
            'user_id' => $userAudible->user_id,
            'user'    => ['id' => $userAudible->user_id],
        ]]]);
});

test('bot messages returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(BotsTableSeeder::class);

    $user = User::factory()->create();

    $bot = Bot::where('slug', 'systembot')->firstOrFail();

    $systemUser = User::where('username', 'System')->firstOrFail();

    Message::factory()->create([
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
});

test('bots returns an ok response', function () {
    $this->seed(BotsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('api/chat/bots');

    $response->assertOk()
        ->assertJson(['data' => [
            ['slug' => 'systembot'],
            ['slug' => 'nerdbot'],
            ['slug' => 'casinobot'],
            ['slug' => 'betbot'],
            ['slug' => 'triviabot'],
        ]]);
});

test('config returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('api/chat/config');

    $response->assertOk()
        ->assertJson([
            'system_chatroom' => 'General',
            'message_limit'   => 100,
            'nerd_bot'        => true,
        ]);
});

test('create message returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(BotsTableSeeder::class);

    $user = User::factory()->create();

    $chatroom = Chatroom::factory()->create();

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
});

test('delete bot echo returns an ok response', function () {
    $user = User::factory()->create();

    $userEcho = UserEcho::factory()->create([
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
});

test('delete message returns an ok response', function () {
    $user = User::factory()->create();

    $message = Message::factory()->create();

    $response = $this->actingAs($user)->get(sprintf('api/chat/message/%s/delete', $message->id));

    $response->assertOk()
        ->assertSee('success');
});

test('delete room echo returns an ok response', function () {
    $user = User::factory()->create();

    $userEcho = UserEcho::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(sprintf('api/chat/echoes/%s/delete/chatroom', $user->id), [
        'room_id' => $userEcho['room_id'],
    ]);

    $response->assertOk()
        ->assertJson([
            'id'       => $user->id,
            'username' => $user->username,
        ]);
});

test('delete target echo returns an ok response', function () {
    $user = User::factory()->create();

    $userEcho = UserEcho::factory()->create([
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
});

test('echoes returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('api/chat/echoes');

    $response->assertOk()
        ->assertJson(['data' => [[
            'user_id' => $user->id,
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
            ],
        ]]]);
});

test('messages returns an ok response', function () {
    $user = User::factory()->create();

    $message = Message::factory()->create();

    $response = $this->actingAs($user)->get(sprintf('api/chat/messages/%s', $message['chatroom_id']));

    $response->assertOk()
        ->assertJson(['data' => [[
            'id'       => $message->id,
            'bot'      => ['id' => $message['bot_id']],
            'user'     => ['id' => $message['user_id']],
            'receiver' => ['id' => $message['receiver_id']],
            'chatroom' => ['id' => $message['chatroom_id']],
        ]]]);
});

test('private messages returns an ok response', function () {
    $user = User::factory()->create();

    $message = Message::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(sprintf('api/chat/private/messages/%s', $message['receiver_id']));

    $response->assertOk()
        ->assertJson(['data' => [[
            'id'       => $message->id,
            'bot'      => ['id' => $message['bot_id']],
            'user'     => ['id' => $message['user_id']],
            'receiver' => ['id' => $message['receiver_id']],
            'chatroom' => ['id' => $message['chatroom_id']],
        ]]]);
});

test('rooms returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('api/chat/rooms');

    $chatroom = Chatroom::findOrFail($user->chatroom_id);

    $response->assertOk()
        ->assertJson(['data' => [[
            'id'   => $chatroom->id,
            'name' => $chatroom->name,
        ]]]);
});

test('statuses returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('api/chat/statuses');

    $chatStatus = ChatStatus::findOrFail($user->chat_status_id);

    $response->assertOk()
        ->assertJson([[
            'id'    => $chatStatus->id,
            'name'  => $chatStatus->name,
            'color' => $chatStatus->color,
            'icon'  => $chatStatus->icon,
        ]]);
});

test('toggle bot audible returns an ok response', function () {
    $user = User::factory()->create();

    $userAudible = UserAudible::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(sprintf('api/chat/audibles/%s/toggle/bot', $user->id), [
        'bot_id' => $userAudible['bot_id'],
    ]);

    $response->assertOk()
        ->assertJson([
            'id'       => $user->id,
            'username' => $user->username,
        ]);
});

test('toggle room audible returns an ok response', function () {
    $user = User::factory()->create();

    $userAudible = UserAudible::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(sprintf('api/chat/audibles/%s/toggle/chatroom', $user->id), [
        'room_id' => $userAudible['room_id'],
    ]);

    $response->assertOk()
        ->assertJson([
            'id'       => $user->id,
            'username' => $user->username,
        ]);
});

test('toggle target audible returns an ok response', function () {
    $user = User::factory()->create();

    $userAudible = UserAudible::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(sprintf('api/chat/audibles/%s/toggle/target', $user->id), [
        'target_id' => $userAudible['target_id'],
    ]);

    $response->assertOk()
        ->assertJson([
            'id'       => $user->id,
            'username' => $user->username,
        ]);
});

test('update user chat status returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(sprintf('api/chat/user/%s/status', $user->id), [
        'status_id' => $user['chat_status_id'],
    ]);

    $response->assertOk()
        ->assertJson([
            'id'       => $user->id,
            'username' => $user->username,
        ]);
});

test('update user room returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(sprintf('api/chat/user/%s/chatroom', $user->id), [
        'room_id' => $user['chatroom_id'],
    ]);

    $response->assertOk()
        ->assertJson([
            'id'       => $user->id,
            'username' => $user->username,
        ]);
});

test('update user target returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(sprintf('api/chat/user/%s/target', $user->id));

    $response->assertOk()->assertJson([
        'id'       => $user->id,
        'username' => $user->username,
    ]);
});
