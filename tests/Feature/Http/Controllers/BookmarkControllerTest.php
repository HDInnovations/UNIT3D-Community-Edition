<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bookmark;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BookmarkController
 */
class BookmarkControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupsTableSeeder::class);
    }

    /** @test */
    public function destroy_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $bookmark = factory(Bookmark::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('bookmarks.destroy', ['id' => $bookmark->torrent_id]));

        $response->assertRedirect(route('torrent', ['id' => $bookmark->torrent_id]))
            ->assertSessionHas('success', 'Torrent Has Been Unbookmarked Successfully!');
    }

    /** @test */
    public function index_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        factory(Bookmark::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('bookmarks.index', ['username' => $user->username]));

        $response->assertOk()
            ->assertViewIs('user.bookmarks')
            ->assertViewHas('user')
            ->assertViewHas('personal_freeleech')
            ->assertViewHas('bookmarks')
            ->assertViewHas('route');
    }

    /** @test */
    public function store_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $bookmark = factory(Bookmark::class)->make([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post(route('bookmarks.store', ['id' => $bookmark->torrent_id]))
            ->assertRedirect(route('torrent', ['id' => $bookmark->torrent_id]))
            ->assertSessionHas('success', 'Torrent Has Been Bookmarked Successfully!');
    }
}
