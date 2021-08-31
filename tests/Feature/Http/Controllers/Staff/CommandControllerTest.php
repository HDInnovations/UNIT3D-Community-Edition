<?php

use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\CommandController
 */
beforeEach(function () {
});

test('clear all cache returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/clear-all-cache');

    $response->assertRedirect(route('staff.commands.index'));
});

test('clear cache returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/clear-cache');

    $response->assertRedirect(route('staff.commands.index'));
});

test('clear config returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/clear-config-cache');

    $response->assertRedirect(route('staff.commands.index'));
});

test('clear route returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/clear-route-cache');

    $response->assertRedirect(route('staff.commands.index'));
});

test('clear view returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/clear-view-cache');

    $response->assertRedirect(route('staff.commands.index'));
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.commands.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.command.index');
});

test('maintance disable returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/maintance-disable');

    $response->assertRedirect(route('staff.commands.index'));
});

test('set all cache returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/set-all-cache');

    $response->assertRedirect(route('staff.commands.index'));
});

test('email returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get('dashboard/commands/test-email');

    $response->assertRedirect(route('staff.commands.index'));
});

// Helpers
function createStaffUser()
{
    return User::factory()->create([
        'group_id' => fn () => Group::factory()->create([
            'is_owner' => true,
            'is_admin' => true,
            'is_modo'  => true,
        ])->id,
    ]);
}
