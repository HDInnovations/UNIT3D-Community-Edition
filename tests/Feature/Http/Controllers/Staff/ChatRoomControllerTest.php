<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Chatroom;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ChatRoomController
 */
class ChatRoomControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
    {
        return User::factory()->create([
            'group_id' => fn () => Group::factory()->create([
                'is_owner' => true,
                'is_admin' => true,
                'is_modo'  => true,
            ])->id,
        ]);
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chatroom = Chatroom::factory()->create();

        $response = $this->actingAs($user)->delete(route('staff.rooms.destroy', ['id' => $chatroom->id]));

        $response->assertRedirect(route('staff.rooms.index'));
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
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
    public function store_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chatroom = Chatroom::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.rooms.store'), [
            'name' => $chatroom->name,
        ]);

        $response->assertRedirect(route('staff.rooms.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chatroom = Chatroom::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.rooms.update', ['id' => $chatroom->id]), [
            'name' => $chatroom->name,
        ]);

        $response->assertRedirect(route('staff.rooms.index'));
    }
}
