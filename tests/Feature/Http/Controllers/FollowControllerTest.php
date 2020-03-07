<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;
use UsersTableSeeder;

/**
 * @see \App\Http\Controllers\FollowController
 */
class FollowControllerTest extends TestCase
{
    /** @test */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();

        $userToFollow = factory(User::class)->create();

        $follow = factory(Follow::class)->create([
            'user_id'   => $user->id,
            'target_id' => $userToFollow->id,
        ]);

        $response = $this->actingAs($user)->delete(route('follow.destroy', ['username' => $userToFollow->username]));

        $response->assertRedirect(route('users.show', ['username' => $userToFollow->username]))
            ->assertSessionHas('success', sprintf('You are no longer following %s', $userToFollow->username));

        $this->assertDeleted($follow);
    }

    /** @test */
    public function store_returns_an_ok_response()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();

        $userToFollow = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('follow.store', ['username' => $userToFollow->username]));

        $response->assertRedirect(route('users.show', ['username' => $userToFollow->username]))
            ->assertSessionHas('success', sprintf('You are now following %s', $userToFollow->username));
    }
}
