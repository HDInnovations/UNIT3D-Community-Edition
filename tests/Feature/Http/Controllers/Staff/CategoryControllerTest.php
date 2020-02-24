<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Category;
use App\Models\Group;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\CategoryController
 */
class CategoryControllerTest extends TestCase
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

        $response = $this->actingAs($user)->get(route('staff.categories.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.category.create');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $category = factory(Category::class)->create();
        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->delete(route('staff.categories.destroy', ['id' => $category->id]));

        $response->assertRedirect(route('staff.categories.index'));
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->get(route('staff.categories.edit', ['id' => $category->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.category.edit');
        $response->assertViewHas('category');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.categories.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.category.index');
        $response->assertViewHas('categories');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $category = factory(Category::class)->make();

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
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $category = factory(Category::class)->create();
        $user = $this->createStaffUser();

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
    }
}
