<?php

namespace Tests\Feature\Http\Controllers;

use App\Helpers\Bencode;
use App\Models\Torrent;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AnnounceController
 */
class AnnounceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function announce_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create([
            'can_download' => true,
        ]);

        $infoHash = sha1(random_bytes(20));

        factory(Torrent::class)->create([
            'info_hash' => bin2hex($infoHash),
            'status'    => 1, // Approved
        ]);

        $response = $this->get(route('announce', [
            'passkey'    => $user->passkey,
            'info_hash'  => $infoHash,
            'peer_id'    => bin2hex(random_bytes(10)),
            'port'       => 7022,
            'left'       => 1,
            'uploaded'   => 1,
            'downloaded' => 1,
            'compact'    => 1,
        ]))
            ->assertOk();

        $decoded = Bencode::bdecode($response->getContent());

        $this->assertArrayNotHasKey('failure reason', $decoded);
    }
}
