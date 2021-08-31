<?php

use App\Models\Bookmark;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\BookmarkController
 */
beforeEach(function () {
    $this->seed(GroupsTableSeeder::class);
});

test('destroy returns an ok response', function () {
    $user = User::factory()->create();

    $bookmark = Bookmark::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete(route('bookmarks.destroy', ['id' => $bookmark->torrent_id]));

    $response->assertRedirect(route('bookmarks.index', ['username' => $user->username]))
        ->assertSessionHas('success', 'Torrent Has Been Unbookmarked Successfully!');
});

test('index returns an ok response', function () {
    $user = User::factory()->create();

    Bookmark::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('bookmarks.index', ['username' => $user->username]));

    $response->assertOk()
        ->assertViewIs('bookmark.index')
        ->assertViewHas('user');
});

test('store returns an ok response', function () {
    $user = User::factory()->create();

    $bookmark = Bookmark::factory()->make([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('bookmarks.store', ['id' => $bookmark->torrent_id]))
        ->assertRedirect(route('torrent', ['id' => $bookmark->torrent_id]))
        ->assertSessionHas('success', 'Torrent Has Been Bookmarked Successfully!');
});
