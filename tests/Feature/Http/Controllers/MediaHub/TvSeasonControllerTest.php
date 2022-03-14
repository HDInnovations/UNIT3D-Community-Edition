<?php

namespace Tests\Feature\Http\Controllers\MediaHub;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MediaHub\TvSeasonController
 */
class TvSeasonControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $season = \App\Models\Season::factory()->create();
        $tv = \App\Models\Tv::factory()->create();

        $response = $this->get(route('mediahub.season.show', ['id' => $id]));

        $response->assertOk();
        $response->assertViewIs('mediahub.tv.season.show');
        $response->assertViewHas('season', $season);
        $response->assertViewHas('show');

        // TODO: perform additional assertions
    }

    // test cases...
}
