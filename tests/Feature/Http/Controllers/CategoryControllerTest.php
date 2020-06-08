<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CategoryController
 */
class CategoryControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupsTableSeeder::class);
    }

    /** @test */
    public function index_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('categories.index'));

        $response->assertOk()
            ->assertViewIs('category.index')
            ->assertViewHas('categories');
    }

    /** @test */
    public function show_returns_an_ok_response()
    {
        $category = factory(Category::class)->create();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('categories.show', ['id' => $category->id]));

        $response->assertOk()
            ->assertViewIs('category.show')
            ->assertViewHas('torrents')
            ->assertViewHas('user')
            ->assertViewHas('category')
            ->assertViewHas('personal_freeleech');
    }
}
