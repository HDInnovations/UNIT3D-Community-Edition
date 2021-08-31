<?php

use App\Models\Group;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\PageController
 */
beforeEach(function () {
});

test('create returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.pages.create'));

    $response->assertOk();
    $response->assertViewIs('Staff.page.create');
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $page = Page::factory()->create();

    $response = $this->actingAs($user)->delete(route('staff.pages.destroy', ['id' => $page->id]));

    $response->assertRedirect(route('staff.pages.index'));
});

test('edit returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $page = Page::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.pages.edit', ['id' => $page->id]));

    $response->assertOk();
    $response->assertViewIs('Staff.page.edit');
    $response->assertViewHas('page');
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.pages.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.page.index');
    $response->assertViewHas('pages');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $page = Page::factory()->make();

    $response = $this->actingAs($user)->post(route('staff.pages.store'), [
        'name'    => $page->name,
        'slug'    => $page->slug,
        'content' => $page->content,
    ]);

    $response->assertRedirect(route('staff.pages.index'));
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $page = Page::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.pages.update', ['id' => $page->id]), [
        'name'    => $page->name,
        'slug'    => $page->slug,
        'content' => $page->content,
    ]);

    $response->assertRedirect(route('staff.pages.index'));
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
