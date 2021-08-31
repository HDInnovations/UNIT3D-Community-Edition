<?php

use App\Models\Category;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\CategoryController
 */
beforeEach(function () {
});

test('create returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.categories.create'));

    $response->assertOk();
    $response->assertViewIs('Staff.category.create');
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $category = Category::factory()->create();
    $user = createStaffUser();

    $response = $this->actingAs($user)->delete(route('staff.categories.destroy', ['id' => $category->id]));

    $response->assertRedirect(route('staff.categories.index'));
});

test('edit returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.categories.edit', ['id' => $category->id]));

    $response->assertOk();
    $response->assertViewIs('Staff.category.edit');
    $response->assertViewHas('category');
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.categories.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.category.index');
    $response->assertViewHas('categories');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $category = Category::factory()->make();

    $response = $this->actingAs($user)->post(route('staff.categories.store'), [
        'name'       => $category->name,
        'slug'       => $category->slug,
        'position'   => $category->position,
        'image'      => $category->image,
        'icon'       => $category->icon,
        'movie_meta' => $category->movie_meta,
        'tv_meta'    => $category->tv_meta,
        'game_meta'  => $category->game_meta,
        'music_meta' => $category->music_meta,
        'no_meta'    => $category->no_meta,
    ]);

    $response->assertRedirect(route('staff.categories.index'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $category = Category::factory()->create();
    $user = createStaffUser();

    $response = $this->actingAs($user)->patch(route('staff.categories.update', ['id' => $category->id]), [
        'name'       => $category->name,
        'slug'       => $category->slug,
        'position'   => $category->position,
        'image'      => $category->image,
        'icon'       => $category->icon,
        'movie_meta' => $category->movie_meta,
        'tv_meta'    => $category->tv_meta,
        'game_meta'  => $category->game_meta,
        'music_meta' => $category->music_meta,
        'no_meta'    => $category->no_meta,
    ]);

    $response->assertRedirect(route('staff.categories.index'));
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
