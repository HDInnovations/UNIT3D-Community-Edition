<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Album;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AlbumController
 */
class AlbumControllerTest extends TestCase
{
    public function testCreateReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('albums.create'));

        $response->assertOk()
            ->assertViewIs('album.create');
    }

    public function testDestroyReturnsAnOkResponse()
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

    public function testIndexReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('albums.index'));

        $response->assertOk()
            ->assertViewIs('album.index');
    }

    public function testShowReturnsAnOkResponse()
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

    public function testStoreReturnsAnOkResponse()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $album = Album::factory()->raw([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('albums.store'), $album);

        $response->assertRedirect(route('albums.create'));

        $this->assertEquals('Meta Data Not Found. Gallery System Is Being Refactored', session()->get('errors')->default->first());
    }
}
