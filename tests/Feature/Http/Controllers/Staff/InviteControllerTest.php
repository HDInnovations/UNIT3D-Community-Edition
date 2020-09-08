<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Database\Seeders\GroupsTableSeeder;
use App\Models\Group;
use App\Models\User;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\InviteController
 */
class InviteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser()
    {
        return User::factory()->create([
            'group_id' => function () {
                return Group::factory()->create([
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
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.invites.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.invite.index');
        $response->assertViewHas('invites');
        $response->assertViewHas('invitecount');
    }
}
