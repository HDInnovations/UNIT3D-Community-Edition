<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Torrent;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AnnounceController
 */
class AnnounceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function announce_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create([
            'can_download' => true,
        ]);

        $info_hash = '16679042096019090177'; // 20 bytes
        $peer_id = '19045931013802080695'; // 20 bytes

        Torrent::factory()->create([
            'info_hash' => \bin2hex($info_hash),
            'status'    => 1, // Approved
        ]);

        $headers = [
            'accept-language' => null,
            'referer'         => null,
            'accept-charset'  => null,
            'want-digest'     => null,
        ];

        $response = $this->withHeaders($headers)->get(route('announce', [
            'passkey'    => $user->passkey,
            'info_hash'  => $info_hash,
            'peer_id'    => $peer_id,
            'port'       => 7022,
            'left'       => 0,
            'uploaded'   => 1,
            'downloaded' => 1,
            'compact'    => 1,
        ]))
            ->assertOk();

        $this->assertArrayNotHasKey('failure reason', [$response->getContent()]);
    }
}
