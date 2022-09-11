<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FollowController
 */
class FollowControllerTest extends TestCase
{
    /** @test */
    public function destroy_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $userToFollow = User::factory()->create();

        $follow = Follow::factory()->create([
            'user_id'   => $user->id,
            'target_id' => $userToFollow->id,
        ]);

        $response = $this->actingAs($user)->delete(route('follow.destroy', ['username' => $userToFollow->username]));

        $response->assertRedirect(route('users.show', ['username' => $userToFollow->username]))
            ->assertSessionHas('success', sprintf('You are no longer following %s', $userToFollow->username));

        $this->assertModelMissing($follow);
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $userToFollow = User::factory()->create();

        $response = $this->actingAs($user)->post(route('follow.store', ['username' => $userToFollow->username]));

        $response->assertRedirect(route('users.show', ['username' => $userToFollow->username]))
            ->assertSessionHas('success', sprintf('You are now following %s', $userToFollow->username));
    }
}
