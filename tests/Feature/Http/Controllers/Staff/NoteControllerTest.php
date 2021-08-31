<?php

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\NoteController
 */
beforeEach(function () {
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $staff = createStaffUser();
    $note = Note::factory()->create();
    $user = User::whereId($note->user_id)->first();

    $response = $this->actingAs($staff)->delete(route('staff.notes.destroy', ['id' => $note->id]));

    $response->assertRedirect(route('users.show', ['username' => $user->username]));
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.notes.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.note.index');
    $response->assertViewHas('notes');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $staff = createStaffUser();
    $user = User::factory()->create();
    $note = Note::factory()->make();

    $response = $this->actingAs($staff)->post(route('staff.notes.store', ['username' => $user->username]), [
        'user_id'  => $user->id,
        'staff_id' => $staff->id,
        'message'  => $note->message,
    ]);

    $response->assertRedirect(route('users.show', ['username' => $user->username]));
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
