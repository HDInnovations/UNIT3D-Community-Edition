<?php

use App\Models\Follow;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\FollowController
 */
test('destroy returns an ok response', function () {
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

    $this->assertDeleted($follow);
});

test('store returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $userToFollow = User::factory()->create();

    $response = $this->actingAs($user)->post(route('follow.store', ['username' => $userToFollow->username]));

    $response->assertRedirect(route('users.show', ['username' => $userToFollow->username]))
        ->assertSessionHas('success', sprintf('You are now following %s', $userToFollow->username));
});
