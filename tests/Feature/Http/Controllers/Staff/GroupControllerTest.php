<?php

use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\GroupController
 */
beforeEach(function () {
});

test('create returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.groups.create'));

    $response->assertOk();
    $response->assertViewIs('Staff.group.create');
});

test('edit returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $group = Group::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.groups.edit', ['id' => $group->id]));

    $response->assertOk();
    $response->assertViewIs('Staff.group.edit');
    $response->assertViewHas('group');
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.groups.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.group.index');
    $response->assertViewHas('groups');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $group = Group::factory()->make();

    $response = $this->actingAs($user)->post(route('staff.groups.store'), [
        'name'             => $group->name,
        'slug'             => $group->slug,
        'position'         => $group->position,
        'level'            => $group->level,
        'color'            => $group->color,
        'icon'             => $group->icon,
        'effect'           => $group->effect,
        'is_internal'      => $group->is_internal,
        'is_owner'         => $group->is_owner,
        'is_admin'         => $group->is_admin,
        'is_modo'          => $group->is_modo,
        'is_trusted'       => $group->is_trusted,
        'is_immune'        => $group->is_immune,
        'is_freeleech'     => $group->is_freeleech,
        'is_double_upload' => $group->is_double_upload,
        'can_upload'       => $group->can_upload,
        'is_incognito'     => $group->is_incognito,
        'autogroup'        => $group->autogroup,
    ]);

    $response->assertRedirect(route('staff.groups.index'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $group = Group::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.groups.update', ['id' => $group->id]), [
        'name'             => $group->name,
        'slug'             => $group->slug,
        'position'         => $group->position,
        'level'            => $group->level,
        'color'            => $group->color,
        'icon'             => $group->icon,
        'effect'           => $group->effect,
        'is_internal'      => $group->is_internal,
        'is_owner'         => $group->is_owner,
        'is_admin'         => $group->is_admin,
        'is_modo'          => $group->is_modo,
        'is_trusted'       => $group->is_trusted,
        'is_immune'        => $group->is_immune,
        'is_freeleech'     => $group->is_freeleech,
        'is_double_upload' => $group->is_double_upload,
        'can_upload'       => $group->can_upload,
        'is_incognito'     => $group->is_incognito,
        'autogroup'        => $group->autogroup,
    ]);

    $response->assertRedirect(route('staff.groups.index'));
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
