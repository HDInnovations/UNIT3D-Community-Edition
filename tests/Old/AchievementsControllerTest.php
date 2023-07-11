<?php

namespace Tests\Old;

use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AchievementsController
 */
final class AchievementsControllerTest extends TestCase
{
    #[Test]
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
