<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\PageController
 */
class PageControllerTest extends TestCase
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

    public function testCreateReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.pages.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.page.create');
    }

    public function testDestroyReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->delete(route('staff.pages.destroy', ['id' => $page->id]));

        $response->assertRedirect(route('staff.pages.index'));
    }

    public function testEditReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.pages.edit', ['id' => $page->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.page.edit');
        $response->assertViewHas('page');
    }

    public function testIndexReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.pages.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.page.index');
        $response->assertViewHas('pages');
    }

    public function testStoreReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.pages.store'), [
            'name'    => $page->name,
            'slug'    => $page->slug,
            'content' => $page->content,
        ]);

        $response->assertRedirect(route('staff.pages.index'));
    }

    public function testUpdateReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.pages.update', ['id' => $page->id]), [
            'name'    => $page->name,
            'slug'    => $page->slug,
            'content' => $page->content,
        ]);

        $response->assertRedirect(route('staff.pages.index'));
    }
}
