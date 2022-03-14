<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ChatBotController
 */
class ChatBotControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $bot = \App\Models\Bot::factory()->create();

        $response = $this->delete(route('staff.bots.destroy', ['id' => $id]));

        $response->assertOk();
        $this->assertDeleted($staff.bot);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function disable_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $bot = \App\Models\Bot::factory()->create();

        $response = $this->post(route('staff.bots.disable', ['id' => $id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $bot = \App\Models\Bot::factory()->create();

        $response = $this->get(route('staff.bots.edit', ['id' => $id]));

        $response->assertOk();
        $response->assertViewIs('Staff.chat.bot.edit');
        $response->assertViewHas('user');
        $response->assertViewHas('bot', $bot);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function enable_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $bot = \App\Models\Bot::factory()->create();

        $response = $this->post(route('staff.bots.enable', ['id' => $id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $bots = \App\Models\Bot::factory()->times(3)->create();

        $response = $this->get(route('staff.bots.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.chat.bot.index');
        $response->assertViewHas('bots', $bots);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $bot = \App\Models\Bot::factory()->create();

        $response = $this->patch(route('staff.bots.update', ['id' => $id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
