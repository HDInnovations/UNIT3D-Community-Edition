<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ForumCategoryController
 */
class ForumCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $forum = \App\Models\Forum::factory()->create();

        $response = $this->get(route('forums.categories.show', ['id' => $id]));

        $response->assertOk();
        $response->assertViewIs('forum.category');
        $response->assertViewHas('forum', $forum);
        $response->assertViewHas('topics');
        $response->assertViewHas('category');
        $response->assertViewHas('num_posts');
        $response->assertViewHas('num_forums');
        $response->assertViewHas('num_topics');

        // TODO: perform additional assertions
    }

    // test cases...
}
