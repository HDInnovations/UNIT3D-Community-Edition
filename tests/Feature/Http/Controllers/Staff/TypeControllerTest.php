<?php

use App\Models\Group;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\TypeController
 */
beforeEach(function () {
});

test('create returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.types.create'));

    $response->assertOk();
    $response->assertViewIs('Staff.type.create');
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $type = Type::factory()->create();

    $response = $this->actingAs($user)->delete(route('staff.types.destroy', ['id' => $type->id]));

    $response->assertRedirect(route('staff.types.index'));
});

test('edit returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $type = Type::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.types.edit', ['id' => $type->id]));

    $response->assertOk();
    $response->assertViewIs('Staff.type.edit');
    $response->assertViewHas('type');
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.types.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.type.index');
    $response->assertViewHas('types');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $type = Type::factory()->make();

    $response = $this->actingAs($user)->post(route('staff.types.store'), [
        'name'     => $type->name,
        'slug'     => $type->slug,
        'position' => $type->position,
    ]);

    $response->assertRedirect(route('staff.types.index'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $type = Type::factory()->create();

    $response = $this->actingAs($user)->patch(route('staff.types.update', ['id' => $type->id]), [
        'name'     => $type->name,
        'slug'     => $type->slug,
        'position' => $type->position,
    ]);

    $response->assertRedirect(route('staff.types.index'));
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
