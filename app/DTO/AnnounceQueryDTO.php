<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\DTO;

readonly class AnnounceQueryDTO
{
    private string $agent;
    private string $infoHash;
    private string $peerId;
    private string $ip;

    public function __construct(
        public int $port,
        public int $uploaded,
        public int $downloaded,
        public int $left,
        public int $corrupt,
        public int $numwant,
        public string $event,
        public string $key,
        string $agent,
        string $infoHash,
        string $peerId,
        string $ip,
    ) {
        $this->agent = bin2hex($agent);
        $this->infoHash = bin2hex($infoHash);
        $this->peerId = bin2hex($peerId);
        $this->ip = bin2hex($ip);
    }

    public function getAgent(): string
    {
        /** @var string */
        return hex2bin($this->agent);
    }

    public function getInfoHash(): string
    {
        /** @var string */
        return hex2bin($this->infoHash);
    }

    public function getPeerId(): string
    {
        /** @var string */
        return hex2bin($this->peerId);
    }

    public function getIp(): string
    {
        /** @var string */
        return hex2bin($this->ip);
    }
}
