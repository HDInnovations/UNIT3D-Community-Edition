<?php

use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\HomeController
 */
beforeEach(function () {
    $this->seed(GroupsTableSeeder::class);
});

test('when not authenticated homepage redirects to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

test('when authenticated homepage returns200', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home.index'))
        ->assertOk()
        ->assertViewIs('home.index')
        ->assertViewHas('user')
        ->assertViewHas('personal_freeleech')
        ->assertViewHas('users')
        ->assertViewHas('groups')
        ->assertViewHas('articles')
        ->assertViewHas('newest')
        ->assertViewHas('seeded')
        ->assertViewHas('dying')
        ->assertViewHas('leeched')
        ->assertViewHas('dead')
        ->assertViewHas('topics')
        ->assertViewHas('posts')
        ->assertViewHas('featured')
        ->assertViewHas('poll')
        ->assertViewHas('uploaders')
        ->assertViewHas('past_uploaders')
        ->assertViewHas('freeleech_tokens')
        ->assertViewHas('bookmarks');
});

test('when authenticated and two step required homepage redirects to two step', function () {
    $user = User::factory()->create([
        'twostep' => true,
    ]);

    $this->actingAs($user)
        ->get(route('home.index'))
        ->assertRedirect(route('verificationNeeded'));
});
