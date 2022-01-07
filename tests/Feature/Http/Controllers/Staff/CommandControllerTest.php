<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\CommandController
 */
class CommandControllerTest extends TestCase
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

    public function testClearAllCacheReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/clear-all-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testClearCacheReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/clear-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testClearConfigReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/clear-config-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testClearRouteReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/clear-route-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testClearViewReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/clear-view-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testIndexReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.commands.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.command.index');
    }

    public function testMaintanceDisableReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/maintance-disable');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testSetAllCacheReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/set-all-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    public function testEmailReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->post('dashboard/commands/test-email');

        $response->assertRedirect(route('staff.commands.index'));
    }
}
