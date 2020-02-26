<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\NoteController
 */
class NoteControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser()
    {
        return factory(User::class)->create([
            'group_id' => function () {
                return factory(Group::class)->create([
                    'is_owner' => true,
                    'is_admin' => true,
                    'is_modo'  => true,
                ])->id;
            },
        ]);
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $staff = $this->createStaffUser();
        $note = factory(Note::class)->create();
        $user = User::whereId($note->user_id)->first();

        $response = $this->actingAs($staff)->delete(route('staff.notes.destroy', ['id' => $note->id]));

        $response->assertRedirect(route('users.show', ['username' => $user->username]));
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.notes.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.note.index');
        $response->assertViewHas('notes');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $staff = $this->createStaffUser();
        $user = factory(User::class)->create();
        $note = factory(Note::class)->make();

        $response = $this->actingAs($staff)->post(route('staff.notes.store', ['username' => $user->username]), [
            'user_id'  => $user->id,
            'staff_id' => $staff->id,
            'message'  => $note->message,
        ]);

        $response->assertRedirect(route('users.show', ['username' => $user->username]));
    }
}
