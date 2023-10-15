<?php

use App\Models\Torrent;
use App\Models\User;

test('index returns an ok response', function (): void {
    $user = User::factory()->create([
        'can_download' => true,
    ]);

    $info_hash = '16679042096019090177'; // 20 bytes
    $peer_id = '19045931013802080695'; // 20 bytes

    Torrent::factory()->create([
        'info_hash' => $info_hash,
        'status'    => Torrent::APPROVED,
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
    ]));
    $response ->assertOk();

    $this->assertStringNotContainsString('failure reason', $response->getContent());
});
