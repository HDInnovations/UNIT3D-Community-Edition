<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupsTableSeeder::class);
    }

    /** @test */
    public function whenNotAuthenticatedHomepageReturns302()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    /** @test */
    public function whenAuthenticatedHomepageReturns200()
    {
        $user = factory(User::class)->create();

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
    }
}
