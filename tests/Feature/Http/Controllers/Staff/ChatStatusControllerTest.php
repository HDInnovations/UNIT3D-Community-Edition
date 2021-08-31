<?php

use App\Models\ChatStatus;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\ChatStatusController
 */
beforeEach(function () {
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $chat_status = ChatStatus::factory()->create();

    $response = $this->actingAs($user)->delete(route('staff.statuses.destroy', ['id' => $chat_status->id]));
    $response->assertRedirect(route('staff.statuses.index'));
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.statuses.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.chat.status.index');
    $response->assertViewHas('chatstatuses');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $chat_status = ChatStatus::factory()->make();

    $response = $this->actingAs($user)->post(route('staff.statuses.store'), [
        'name'  => $chat_status->name,
        'color' => $chat_status->color,
        'icon'  => $chat_status->icon,
    ]);

    $response->assertRedirect(route('staff.statuses.index'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $chat_status = ChatStatus::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.statuses.update', ['id' => $chat_status->id]), [
        'name'  => $chat_status->name,
        'color' => $chat_status->color,
        'icon'  => $chat_status->icon,
    ]);

    $response->assertRedirect(route('staff.statuses.index'));
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
