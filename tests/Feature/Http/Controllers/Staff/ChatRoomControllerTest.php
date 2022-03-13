<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Chatroom;
use App\Models\Privilege;
use App\Models\Role;
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
        $role = Role::factory() - create();
        $privileges = Privilege::all();
        foreach ($privileges as $privilege) {
            $role->privileges()->attach($privilege);
        }

        return User::factory()->create([
            'role_id' => $role->id,
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
