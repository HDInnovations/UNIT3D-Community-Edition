<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FollowController
 */
final class FollowControllerTest extends TestCase
{
    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $userToFollow = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('users.followers.destroy', ['user' => $userToFollow]));

        $response->assertRedirect(route('users.show', ['user' => $userToFollow]))
            ->assertSessionHas('success', sprintf('You are no longer following %s', $userToFollow->username));

        $this->assertDatabaseMissing('follows', [
            'user_id'   => $user->id,
            'target_id' => $userToFollow->id,
        ]);
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $userToFollow = User::factory()->create();

        $response = $this->actingAs($user)->post(route('users.followers.store', ['user' => $userToFollow]));

        $response->assertRedirect(route('users.show', ['user' => $userToFollow]))
            ->assertSessionHas('success', sprintf('You are now following %s', $userToFollow->username));
    }
}
