<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\NoteController
 */
class NoteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser()
    {
        return User::factory()->create([
            'group_id' => fn () => Group::factory()->create([
                'is_owner' => true,
                'is_admin' => true,
                'is_modo'  => true,
            ])->id,
        ]);
    }

    public function testDestroyReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $staff = $this->createStaffUser();
        $note = Note::factory()->create();
        $user = User::whereId($note->user_id)->first();

        $response = $this->actingAs($staff)->delete(route('staff.notes.destroy', ['id' => $note->id]));

        $response->assertRedirect(route('users.show', ['username' => $user->username]));
    }

    public function testIndexReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.notes.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.note.index');
        $response->assertViewHas('notes');
    }

    public function testStoreReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $staff = $this->createStaffUser();
        $user = User::factory()->create();
        $note = Note::factory()->make();

        $response = $this->actingAs($staff)->post(route('staff.notes.store', ['username' => $user->username]), [
            'user_id'  => $user->id,
            'staff_id' => $staff->id,
            'message'  => $note->message,
        ]);

        $response->assertRedirect(route('users.show', ['username' => $user->username]));
    }
}
