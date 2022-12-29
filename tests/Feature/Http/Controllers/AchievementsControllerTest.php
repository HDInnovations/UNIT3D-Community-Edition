<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AchievementsController
 */
class AchievementsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.achievements.index', ['user' => $user]));

        $response->assertOk()
            ->assertViewIs('user.achievement.index')
            ->assertViewHas('route')
            ->assertViewHas('user')
            ->assertViewHas('achievements')
            ->assertViewHas('pending');
    }
}
