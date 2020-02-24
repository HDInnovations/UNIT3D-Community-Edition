<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\ChatStatus;
use App\Models\Group;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ChatStatusController
 */
class ChatStatusControllerTest extends TestCase
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
        $chat_status = factory(ChatStatus::class)->create();

        $response = $this->actingAs($user)->delete(route('staff.statuses.destroy', ['id' => $chat_status->id]));
        $response->assertRedirect(route('staff.statuses.index'));
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.statuses.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.chat.status.index');
        $response->assertViewHas('chatstatuses');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = factory(ChatStatus::class)->make();

        $response = $this->actingAs($user)->post(route('staff.statuses.store'), [
            'name'  => $chat_status->name,
            'color' => $chat_status->color,
            'icon'  => $chat_status->icon,
        ]);

        $response->assertRedirect(route('staff.statuses.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = factory(ChatStatus::class)->create();

        $response = $this->actingAs($user)->post(route('staff.statuses.update', ['id' => $chat_status->id]), [
            'name'  => $chat_status->name,
            'color' => $chat_status->color,
            'icon'  => $chat_status->icon,
        ]);

        $response->assertRedirect(route('staff.statuses.index'));
    }
}
