<?php

namespace Tests\Feature\Http\Controllers;

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

    /** @test */
    public function destroy_returns_an_ok_response()
    {
        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('bookmarks.destroy', ['id' => $bookmark->torrent_id]));

        $response->assertRedirect(route('torrent', ['id' => $bookmark->torrent_id]))
            ->assertSessionHas('success', 'Torrent Has Been Unbookmarked Successfully!');
    }

    /** @test */
    public function index_returns_an_ok_response()
    {
        $user = User::factory()->create();

        Bookmark::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('bookmarks.index', ['username' => $user->username]));

        $response->assertOk()
            ->assertViewIs('bookmark.index')
            ->assertViewHas('user');
    }

    /** @test */
    public function store_returns_an_ok_response()
    {
        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->make([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post(route('bookmarks.store', ['id' => $bookmark->torrent_id]))
            ->assertRedirect(back())
            ->assertSessionHas('success', 'Torrent Has Been Bookmarked Successfully!');
    }
}
