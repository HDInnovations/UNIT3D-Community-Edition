<?php

use App\Models\Chatroom;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\ChatRoomController
 */
beforeEach(function () {
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $chatroom = Chatroom::factory()->create();

    $response = $this->actingAs($user)->delete(route('staff.rooms.destroy', ['id' => $chatroom->id]));

    $response->assertRedirect(route('staff.rooms.index'));
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.rooms.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.chat.room.index');
    $response->assertViewHas('chatrooms');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $chatroom = Chatroom::factory()->make();

    $response = $this->actingAs($user)->post(route('staff.rooms.store'), [
        'name' => $chatroom->name,
    ]);

    $response->assertRedirect(route('staff.rooms.index'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $chatroom = Chatroom::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.rooms.update', ['id' => $chatroom->id]), [
        'name' => $chatroom->name,
    ]);

    $response->assertRedirect(route('staff.rooms.index'));
});

// Helpers
function createStaffUser()
{
    return User::factory()->create([
        'group_id' => fn () => Group::factory()->create([
            'is_owner' => true,
            'is_admin' => true,
            'is_modo'  => true,
        ])->id,
    ]);
}
