<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\WatchlistController
 */
class WatchlistControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $watchlist = \App\Models\Watchlist::factory()->create();

        $response = $this->delete(route('staff.watchlist.destroy', ['id' => $watchlist->id]));

        $response->assertOk();
        $this->assertDeleted($staff.watchlist);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('staff.watchlist.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.watchlist.index');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $watchlist = \App\Models\Watchlist::factory()->create();

        $response = $this->post(route('staff.watchlist.store', ['id' => $watchlist->id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
