<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use App\Models\Group;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\NewFollow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('follow a user creates a notification for the followed user', function (): void {
    Notification::fake();

    $follower = User::factory()->create();
    $followed = User::factory()->create();

    $userNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $followed->id,
        'block_notifications' => 0,
        'show_account_follow' => 1,
    ]);

    // $follower starts following $followed
    $response = $this->actingAs($follower)->post(route('users.followers.store', ['user' => $followed]));

    $response->assertRedirect(route('users.show', ['user' => $followed->username]))
        ->assertSessionHas('success', \sprintf(trans('user.follow-user'), $followed->username));

    Notification::assertSentTo(
        [$followed],
        NewFollow::class
    );
    Notification::assertCount(1);
});

test('follow a user does not create a notification for the followed user when all notifications disabled', function (): void {
    Notification::fake();

    $group = Group::factory()->create();

    $follower = User::factory()->create([
        'group_id' => $group->id,
    ]);
    $followed = User::factory()->create([
        'group_id' => $group->id,
    ]);

    $userNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $followed->id,
        'block_notifications' => 1,
        'show_account_follow' => 1,
    ]);

    // $follower starts following $followed
    $response = $this->actingAs($follower)->post(route('users.followers.store', ['user' => $followed]));

    $response->assertRedirect(route('users.show', ['user' => $followed->username]))
        ->assertSessionHas('success', \sprintf(trans('user.follow-user'), $followed->username));

    Notification::assertCount(0);
});

test('follow a user does not create a notification for the followed user when following notifications are disabled', function (): void {
    Notification::fake();

    $group = Group::factory()->create();

    $follower = User::factory()->create([
        'group_id' => $group->id,
    ]);
    $followed = User::factory()->create([
        'group_id' => $group->id,
    ]);

    $userNotificationSettings = UserNotification::factory()->create([
        'user_id'             => $followed->id,
        'block_notifications' => 0,
        'show_account_follow' => 0,
    ]);

    // $follower starts following $followed
    $response = $this->actingAs($follower)->post(route('users.followers.store', ['user' => $followed]));

    $response->assertRedirect(route('users.show', ['user' => $followed->username]))
        ->assertSessionHas('success', \sprintf(trans('user.follow-user'), $followed->username));

    Notification::assertCount(0);
});

test('follow a user does not create a notification for the followed user when following notifications are disabled for specific group', function (): void {
    Notification::fake();

    $group = Group::factory()->create();

    $follower = User::factory()->create([
        'group_id' => $group->id,
    ]);
    $followed = User::factory()->create([
        'group_id' => $group->id,
    ]);

    $userNotificationSettings = UserNotification::factory()->create([
        'user_id'               => $followed->id,
        'block_notifications'   => 0,
        'show_account_follow'   => 1,
        'json_following_groups' => [$group->id],
    ]);

    // $follower starts following $followed
    $response = $this->actingAs($follower)->post(route('users.followers.store', ['user' => $followed]));

    $response->assertRedirect(route('users.show', ['user' => $followed->username]))
        ->assertSessionHas('success', \sprintf(trans('user.follow-user'), $followed->username));

    Notification::assertCount(0);
});
