<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Album;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AlbumController
 */
class AlbumControllerTest extends TestCase
{
    /** @test */
    public function create_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('albums.create'));

        $response->assertOk()
            ->assertViewIs('album.create');

        // TODO: perform additional assertions
    }

    /** @test */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $album = Album::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('albums.destroy', ['id' => $album->id]));

        $response->assertRedirect(route('albums.index'))
            ->assertSessionHas('success', 'Album has successfully been deleted');
    }

    /** @test */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('albums.index'));

        $response->assertOk()
            ->assertViewIs('album.index');
    }

    /** @test */
    public function show_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $album = Album::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('albums.show', ['id' => $album->id]));

        $response->assertOk()
            ->assertViewIs('album.show')
            ->assertViewHas('album')
            ->assertViewHas('albums');
    }

    /** @test */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $album = Album::factory()->raw([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('albums.store'), $album);

        $response->assertRedirect(route('albums.create'));

        // TODO It would be ideal to use assertSessionHasErrors() here, but that
        // will require fixing the SUT.

        $this->assertEquals('Bad IMDB Request!', session()->get('errors')->default->first());
    }
}
