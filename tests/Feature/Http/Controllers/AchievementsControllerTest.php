<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AchievementsController
 */
class AchievementsControllerTest extends TestCase
{
    /** @test */
    public function index_returns_an_ok_response()
    {
        $response = $this->get(route('achievements.index'));

        $response->assertOk();
        $response->assertViewIs('achievement.index');
        $response->assertViewHas('route');
        $response->assertViewHas('user');
        $response->assertViewHas('achievements');
        $response->assertViewHas('pending');
    }

    /** @test */
    public function show_returns_an_ok_response()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->get(route('achievements.show', ['username' => $username]));

        $response->assertOk();
        $response->assertViewIs('achievement.show');
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('achievements');
    }
}
