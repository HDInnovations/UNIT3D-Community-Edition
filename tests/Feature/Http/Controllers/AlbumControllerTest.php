<?php

use App\Models\Album;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\AlbumController
 */
test('create returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('albums.create'));

    $response->assertOk()
        ->assertViewIs('album.create');
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $album = Album::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete(route('albums.destroy', ['id' => $album->id]));

    $response->assertRedirect(route('albums.index'))
        ->assertSessionHas('success', 'Album has successfully been deleted');
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('albums.index'));

    $response->assertOk()
        ->assertViewIs('album.index');
});

test('show returns an ok response', function () {
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
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $album = Album::factory()->raw([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(route('albums.store'), $album);

    $response->assertRedirect(route('albums.create'));

    expect(session()->get('errors')->default->first())->toEqual('Meta Data Not Found. Gallery System Is Being Refactored');
});
