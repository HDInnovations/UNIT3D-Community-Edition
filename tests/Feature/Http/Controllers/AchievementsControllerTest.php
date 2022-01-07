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
    public function testIndexReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('achievements.index'));

        $response->assertOk()
            ->assertViewIs('achievement.index')
            ->assertViewHas('route')
            ->assertViewHas('user')
            ->assertViewHas('achievements')
            ->assertViewHas('pending');
    }

    public function testShowReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('achievements.show', [
            'username' => $user->username,
        ]));

        $response->assertOk()
            ->assertViewIs('achievement.show')
            ->assertViewHas('route')
            ->assertViewHas('user')
            ->assertViewHas('achievements');
    }
}
