<?php

use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\AchievementsController
 */
test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('achievements.index'));

    $response->assertOk()
        ->assertViewIs('achievement.index')
        ->assertViewHas('route')
        ->assertViewHas('user')
        ->assertViewHas('achievements')
        ->assertViewHas('pending');
});

test('show returns an ok response', function () {
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
});
