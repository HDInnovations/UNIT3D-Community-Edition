<?php

use App\Models\Group;
use App\Models\PrivateMessage;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\MassActionController
 */
beforeEach(function () {
});

test('create returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.mass-pm.create'));

    $response->assertOk();
    $response->assertViewIs('Staff.masspm.index');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $message = PrivateMessage::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.mass-pm.store'), [
        'sender_id'   => $user->id,
        'receiver_id' => $message->receiver_id,
        'subject'     => $message->subject,
        'message'     => $message->message,
        'read'        => $message->read,
        'related_to'  => $message->related_to,
    ]);

    $response->assertRedirect(route('staff.mass-pm.create'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.mass-actions.validate'));

    $response->assertRedirect(route('staff.dashboard.index'));
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
