<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SubscriptionController
 */
class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function subscribe_forum_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $forum = \App\Models\Forum::factory()->create();
        $subscription = \App\Models\Subscription::factory()->create();

        $response = $this->post(route('subscribe_forum', ['route' => $subscription->route, $forum]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function subscribe_topic_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $topic = \App\Models\Topic::factory()->create();
        $subscription = \App\Models\Subscription::factory()->create();

        $response = $this->post(route('subscribe_topic', ['route' => $subscription->route, $topic]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function unsubscribe_forum_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $forum = \App\Models\Forum::factory()->create();
        $subscription = \App\Models\Subscription::factory()->create();

        $response = $this->post(route('unsubscribe_forum', ['route' => $subscription->route, $forum]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function unsubscribe_topic_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $topic = \App\Models\Topic::factory()->create();
        $subscription = \App\Models\Subscription::factory()->create();

        $response = $this->post(route('unsubscribe_topic', ['route' => $subscription->route, $topic]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
