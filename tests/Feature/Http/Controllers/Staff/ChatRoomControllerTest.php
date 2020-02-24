<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Chatroom;
use App\Models\Group;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ChatRoomController
 */
class ChatRoomControllerTest extends TestCase
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

        $user = $this->createStaffUser();
        $chatroom = factory(Chatroom::class)->create();

        $response = $this->actingAs($user)->delete(route('staff.rooms.destroy', ['id' => $chatroom->id]));

        $response->assertRedirect(route('staff.rooms.index'));
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.rooms.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.chat.room.index');
        $response->assertViewHas('chatrooms');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chatroom = factory(Chatroom::class)->make();

        $response = $this->actingAs($user)->post(route('staff.rooms.store'), [
            'name' => $chatroom->name,
        ]);

        $response->assertRedirect(route('staff.rooms.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chatroom = factory(Chatroom::class)->create();

        $response = $this->actingAs($user)->post(route('staff.rooms.update', ['id' => $chatroom->id]), [
            'name' => $chatroom->name,
        ]);

        $response->assertRedirect(route('staff.rooms.index'));
    }
}
