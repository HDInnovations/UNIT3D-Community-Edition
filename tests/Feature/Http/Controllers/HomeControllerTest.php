<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesTableSeeder::class);
    }

    /** @test */
    public function when_not_authenticated_homepage_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function when_authenticated_homepage_returns200(): void
    {
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
    }

    /** @test */
    public function when_authenticated_and_two_step_required_homepage_redirects_to_two_step(): void
    {
        $user = User::factory()->create([
            'twostep' => true,
        ]);

        $this->actingAs($user)
            ->get(route('home.index'))
            ->assertRedirect(route('verificationNeeded'));
    }
}
