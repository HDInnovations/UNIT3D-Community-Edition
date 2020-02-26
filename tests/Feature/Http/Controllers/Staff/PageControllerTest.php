<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\Page;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\PageController
 */
class PageControllerTest extends TestCase
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
    public function create_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.pages.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.page.create');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = factory(Page::class)->create();

        $response = $this->actingAs($user)->delete(route('staff.pages.destroy', ['id' => $page->id]));

        $response->assertRedirect(route('staff.pages.index'));
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = factory(Page::class)->create();

        $response = $this->actingAs($user)->get(route('staff.pages.edit', ['id' => $page->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.page.edit');
        $response->assertViewHas('page');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.pages.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.page.index');
        $response->assertViewHas('pages');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = factory(Page::class)->make();

        $response = $this->actingAs($user)->post(route('staff.pages.store'), [
            'name'    => $page->name,
            'slug'    => $page->slug,
            'content' => $page->content,
        ]);

        $response->assertRedirect(route('staff.pages.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = factory(Page::class)->create();

        $response = $this->actingAs($user)->post(route('staff.pages.update', ['id' => $page->id]), [
            'name'    => $page->name,
            'slug'    => $page->slug,
            'content' => $page->content,
        ]);

        $response->assertRedirect(route('staff.pages.index'));
    }
}
