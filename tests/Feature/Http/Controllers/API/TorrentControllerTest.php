<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Category;
use App\Models\Torrent;
use App\Models\User;
use BotsTableSeeder;
use ChatroomTableSeeder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use UsersTableSeeder;

/**
 * @see \App\Http\Controllers\API\TorrentController
 */
class TorrentControllerTest extends TestCase
{
    /**
     * @test
     */
    public function filter_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->getJson('api/torrents/filter');

        $response->assertOk()
            ->assertJson([
                'data'  => [],
                'links' => [
                    'first' => sprintf('%s/api/torrents/filter?page=1', appurl()),
                    'last'  => sprintf('%s/api/torrents/filter?page=1', appurl()),
                    'prev'  => null,
                    'next'  => null,
                    'self'  => sprintf('%s/api/torrents', appurl()),
                ],
                'meta' => [
                    'current_page' => 1,
                    'from'         => null,
                    'last_page'    => 1,
                    'path'         => sprintf('%s/api/torrents/filter', appurl()),
                    'per_page'     => 25,
                    'to'           => null,
                    'total'        => 0,
                ],
            ]);
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->getJson(route('torrents.index'));

        $response->assertOk()
            ->assertJson([
                'data'  => [],
                'links' => [
                    'first' => sprintf('%s/api/torrents?page=1', appurl()),
                    'last'  => sprintf('%s/api/torrents?page=1', appurl()),
                    'prev'  => null,
                    'next'  => null,
                    'self'  => sprintf('%s/api/torrents', appurl()),
                ],
                'meta' => [
                    'current_page' => 1,
                    'from'         => null,
                    'last_page'    => 1,
                    'path'         => sprintf('%s/api/torrents', appurl()),
                    'per_page'     => 15,
                    'to'           => null,
                    'total'        => 0,
                ],
            ]);
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $user = factory(User::class)->create();

        $torrent = factory(Torrent::class)->create([
            'user_id' => $user->id,
            'status'  => 1,
        ]);

        $response = $this->actingAs($user, 'api')->getJson(sprintf('api/torrents/%s', $torrent->id));

        $response->assertOk()
            ->assertJson([
                'type' => 'torrent',
                'id'   => $torrent->id,
            ]);
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);
        $this->seed(BotsTableSeeder::class);

        $user = factory(User::class)->create();

        $category = factory(Category::class)->create();

        $torrent = factory(Torrent::class)->make();

        $response = $this->actingAs($user, 'api')->postJson('api/torrents/upload', [
            'torrent' => new UploadedFile(
                base_path('tests/Resources/Pony Music - Mind Fragments (2014).torrent'),
                'Pony Music - Mind Fragments (2014).torrent'
            ),
            'category_id' => $category->id,
            'name'        => 'Pony Music - Mind Fragments (2014)',
            'description' => 'One song that represents the elements of being lost, abandoned, sadness and innocence.',
            'imdb'        => $torrent->imdb,
            'tvdb'        => $torrent->tvdb,
            'tmdb'        => $torrent->tmdb,
            'mal'         => $torrent->mal,
            'igdb'        => $torrent->igdb,
            'type'        => $torrent->type,
            'anonymous'   => $torrent->anon,
            'stream'      => $torrent->stream,
            'sd'          => $torrent->sd,
            'internal'    => $torrent->internal,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Torrent uploaded successfully.',
            ]);
    }
}
