<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\CategoryController
 */
class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('staff.categories.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.category.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Models\Category::factory()->create();

        $response = $this->delete(route('staff.categories.destroy', ['id' => $category->id]));

        $response->assertOk();
        $this->assertDeleted($staff.category);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Models\Category::factory()->create();

        $response = $this->get(route('staff.categories.edit', ['id' => $category->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.category.edit');
        $response->assertViewHas('category', $category);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $categories = \App\Models\Category::factory()->times(3)->create();

        $response = $this->get(route('staff.categories.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.category.index');
        $response->assertViewHas('categories', $categories);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->post(route('staff.categories.store'), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Models\Category::factory()->create();

        $response = $this->patch(route('staff.categories.update', ['id' => $category->id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
