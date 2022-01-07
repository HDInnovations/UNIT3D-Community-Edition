<?php

namespace Tests\Todo\Feature\Http\Controllers;

use App\Models\Bookmark;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BookmarkController
 */
class BookmarkControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupsTableSeeder::class);
    }

    public function testDestroyReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test is incomplete. Needs too be converted to Livewire test.');

        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('bookmarks.destroy', ['id' => $bookmark->torrent_id]));

        $response->assertRedirect(route('bookmarks.index', ['username' => $user->username]))
            ->assertSessionHas('success', 'Torrent Has Been Unbookmarked Successfully!');
    }

    public function testIndexReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test is incomplete. Needs too be converted to Livewire test.');

        $user = User::factory()->create();

        Bookmark::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('bookmarks.index', ['username' => $user->username]));

        $response->assertOk()
            ->assertViewIs('bookmark.index')
            ->assertViewHas('user');
    }

    public function testStoreReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test is incomplete. Needs too be converted to Livewire test.');

        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->make([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post(route('bookmarks.store', ['id' => $bookmark->torrent_id]))
            ->assertRedirect(route('torrent', ['id' => $bookmark->torrent_id]))
            ->assertSessionHas('success', 'Torrent Has Been Bookmarked Successfully!');
    }
}
