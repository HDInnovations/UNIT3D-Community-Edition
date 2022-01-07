<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\ChatStatus;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ChatStatusController
 */
class ChatStatusControllerTest extends TestCase
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

        $user = $this->createStaffUser();
        $chat_status = ChatStatus::factory()->create();

        $response = $this->actingAs($user)->delete(route('staff.statuses.destroy', ['id' => $chat_status->id]));
        $response->assertRedirect(route('staff.statuses.index'));
    }

    public function testIndexReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.statuses.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.chat.status.index');
        $response->assertViewHas('chatstatuses');
    }

    public function testStoreReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = ChatStatus::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.statuses.store'), [
            'name'  => $chat_status->name,
            'color' => $chat_status->color,
            'icon'  => $chat_status->icon,
        ]);

        $response->assertRedirect(route('staff.statuses.index'));
    }

    public function testUpdateReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = ChatStatus::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.statuses.update', ['id' => $chat_status->id]), [
            'name'  => $chat_status->name,
            'color' => $chat_status->color,
            'icon'  => $chat_status->icon,
        ]);

        $response->assertRedirect(route('staff.statuses.index'));
    }
}
