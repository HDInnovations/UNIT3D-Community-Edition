<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AchievementsController
 */
class AchievementsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('achievements.index'));

        $response->assertOk()
            ->assertViewIs('achievement.index')
            ->assertViewHas('route')
            ->assertViewHas('user')
            ->assertViewHas('achievements')
            ->assertViewHas('pending');
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();

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
