<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

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
