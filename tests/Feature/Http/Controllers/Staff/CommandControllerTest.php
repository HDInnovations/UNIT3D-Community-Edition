<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\CommandController
 */
class CommandControllerTest extends TestCase
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
    public function clear_all_cache_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/clear-all-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function clear_cache_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/clear-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function clear_config_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/clear-config-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function clear_route_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/clear-route-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function clear_view_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/clear-view-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.commands.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.command.index');
    }

    /**
     * @test
     */
    public function maintance_disable_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/maintance-disable');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function maintance_enable_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/maintance-enable');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function set_all_cache_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/set-all-cache');

        $response->assertRedirect(route('staff.commands.index'));
    }

    /**
     * @test
     */
    public function test_email_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get('dashboard/commands/test-email');

        $response->assertRedirect(route('staff.commands.index'));
    }
}
