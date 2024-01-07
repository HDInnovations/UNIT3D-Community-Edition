<?php
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

namespace App\Services;

use App\Models\BlacklistClient;
use App\Models\Group;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Unit3dAnnounce
{
    /**
     * @return bool|array{}|array{
     *     created_at: double,
     *     last_request_at: double,
     *     last_announce_response_at: double,
     *     requests_per_1s: double,
     *     requests_per_10s: double,
     *     requests_per_60s: double,
     *     requests_per_900s: double,
     *     requests_per_7200s: double,
     *     announce_responses_per_1s: double,
     *     announce_responses_per_10s: double,
     *     announce_responses_per_60s: double,
     *     announce_responses_per_900s: double,
     *     announce_responses_per_7200s: double,
     *     announce_responses_per_second: double,
     * }
     */
    public static function getStats(): bool|array
    {
        $stats = self::get('stats');

        if (\is_bool($stats)) {
            return $stats;
        }

        if (
            \array_key_exists('created_at', $stats) && \is_float($stats['created_at'])
            && \array_key_exists('last_request_at', $stats) && \is_float($stats['last_request_at'])
            && \array_key_exists('last_announce_response_at', $stats) && \is_float($stats['last_announce_response_at'])
            && \array_key_exists('requests_per_1s', $stats) && \is_float($stats['requests_per_1s'])
            && \array_key_exists('requests_per_10s', $stats) && \is_float($stats['requests_per_10s'])
            && \array_key_exists('requests_per_60s', $stats) && \is_float($stats['requests_per_60s'])
            && \array_key_exists('requests_per_900s', $stats) && \is_float($stats['requests_per_900s'])
            && \array_key_exists('requests_per_7200s', $stats) && \is_float($stats['requests_per_7200s'])
            && \array_key_exists('announce_responses_per_1s', $stats) && \is_float($stats['announce_responses_per_1s'])
            && \array_key_exists('announce_responses_per_10s', $stats) && \is_float($stats['announce_responses_per_10s'])
            && \array_key_exists('announce_responses_per_60s', $stats) && \is_float($stats['announce_responses_per_60s'])
            && \array_key_exists('announce_responses_per_900s', $stats) && \is_float($stats['announce_responses_per_900s'])
            && \array_key_exists('announce_responses_per_7200s', $stats) && \is_float($stats['announce_responses_per_7200s'])
            && \array_key_exists('announce_responses_per_second', $stats) && \is_float($stats['announce_responses_per_second'])
        ) {
            return $stats;
        }

        return [];
    }

    public static function addTorrent(Torrent $torrent): bool
    {
        return self::put('torrents', [
            'id'              => $torrent->id,
            'status'          => $torrent->status,
            'info_hash'       => bin2hex($torrent->info_hash),
            'is_deleted'      => false,
            'seeders'         => $torrent->seeders,
            'leechers'        => $torrent->leechers,
            'times_completed' => $torrent->times_completed,
            'download_factor' => max(0, 100 - $torrent->free),
            'upload_factor'   => $torrent->doubleup ? 200 : 100,
        ]);
    }

    public static function removeTorrent(Torrent $torrent): bool
    {
        return self::delete('torrents', [
            'id'        => $torrent->id,
            'info_hash' => bin2hex($torrent->info_hash),
        ]);
    }

    /**
     * @param int $torrentId
     * @return bool|array{}|array{
     *         id: int,
     *         status: string,
     *         is_deleted: bool,
     *         peers: array<
     *             string,
     *             array{
     *                 ip_address: string,
     *                 user_id: int,
     *                 torrent_id: int,
     *                 port: int,
     *                 is_seeder: bool,
     *                 is_active: bool,
     *                 updated_at: int,
     *                 uploaded: int,
     *                 downloaded: int,
     *             }
     *         >,
     *         seeders: int,
     *         leechers: int,
     *         times_completed: int,
     *         download_factor: int,
     *         upload_factor: int,
     *     }
     */
    public static function getTorrent(int $torrentId): bool|array
    {
        $torrent = self::get('torrents', $torrentId);

        if (\is_bool($torrent)) {
            return $torrent;
        }

        if (
            !\array_key_exists('id', $torrent) || !\is_int($torrent['id'])
            || !\array_key_exists('status', $torrent) || !\is_string($torrent['status'])
            || !\array_key_exists('is_deleted', $torrent) || !\is_bool($torrent['is_deleted'])
            || !\array_key_exists('peers', $torrent) || !\is_array($torrent['peers'])
            || !\array_key_exists('seeders', $torrent) || !\is_int($torrent['seeders'])
            || !\array_key_exists('leechers', $torrent) || !\is_int($torrent['leechers'])
            || !\array_key_exists('times_completed', $torrent) || !\is_int($torrent['times_completed'])
            || !\array_key_exists('download_factor', $torrent) || !\is_int($torrent['download_factor'])
            || !\array_key_exists('upload_factor', $torrent) || !\is_int($torrent['upload_factor'])
        ) {
            return [];
        }

        foreach ($torrent['peers'] as $peer) {
            if (
                !\array_key_exists('ip_address', $peer) || !\is_string($peer['ip_address'])
                || !\array_key_exists('user_id', $peer) || !\is_int($peer['user_id'])
                || !\array_key_exists('torrent_id', $peer) || !\is_int($peer['torrent_id'])
                || !\array_key_exists('port', $peer) || !\is_int($peer['port'])
                || !\array_key_exists('is_seeder', $peer) || !\is_bool($peer['is_seeder'])
                || !\array_key_exists('is_active', $peer) || \is_bool($peer['is_active'])
                || !\array_key_exists('updated_at', $peer) || \is_int($peer['updated_at'])
                || !\array_key_exists('uploaded', $peer) || \is_int($peer['uploaded'])
                || !\array_key_exists('downloaded', $peer) || \is_int($peer['downloaded'])
            ) {
                return [];
            }
        }

        return $torrent;
    }

    public static function addUser(User $user): bool
    {
        if ($user->deleted_at !== null) {
            return true;
        }

        $peers = Peer::query()
            ->where('user_id', '=', $user->id)
            ->selectRaw('SUM(seeder = 1 AND active = 1) as num_seeding, SUM(seeder = 0 AND active = 1) as num_leeching')
            ->first();

        return self::put('users', [
            'id'           => (int) $user->id,
            'group_id'     => (int) $user->group_id,
            'passkey'      => $user->passkey,
            'can_download' => (bool) $user->can_download,
            /** @phpstan-ignore-next-line  */
            'num_seeding' => (int) $peers->num_seeding,
            /** @phpstan-ignore-next-line  */
            'num_leeching' => (int) $peers->num_leeching,
        ]);
    }

    public static function removeUser(User $user): bool
    {
        return self::delete('users', [
            'id'      => $user->id,
            'passkey' => $user->passkey,
        ]);
    }

    /**
     * @param int $userId
     * @return bool|array{}|array{
     *     id: int,
     *     group_id: int,
     *     passkey: string,
     *     can_download: bool,
     *     num_seeding: int,
     *     num_leeching: int,
     * }
     */
    public static function getUser(int $userId): bool|array
    {
        $user = self::get('users', $userId);

        if (\is_bool($user)) {
            return $user;
        }

        if (
            \array_key_exists('id', $user) && \is_int($user['id'])
            && \array_key_exists('group_id', $user) && \is_int($user['group_id'])
            && \array_key_exists('passkey', $user) && \is_string($user['passkey'])
            && \array_key_exists('can_download', $user) && \is_bool($user['can_download'])
            && \array_key_exists('num_seeding', $user) && \is_int($user['num_seeding'])
            && \array_key_exists('num_leeching', $user) && \is_int($user['num_leeching'])
        ) {
            return $user;
        }

        return [];
    }

    public static function addGroup(Group $group): bool
    {
        return self::put('groups', [
            'id'               => $group->id,
            'slug'             => $group->slug,
            'download_slots'   => $group->download_slots,
            'is_immune'        => (bool) $group->is_immune,
            'is_freeleech'     => (bool) $group->is_freeleech,
            'is_double_upload' => (bool) $group->is_double_upload,
        ]);
    }

    public static function removeGroup(Group $group): bool
    {
        return self::delete('groups', [
            'id' => $group->id,
        ]);
    }

    public static function addBlacklistedAgent(String $blacklistedAgent): bool
    {
        return self::put('blacklisted-agents', [
            'name' => $blacklistedAgent,
        ]);
    }

    public static function removeBlacklistedAgent(BlacklistClient $blacklistedClient): bool
    {
        return self::delete('blacklisted-agents', [
            'name' => $blacklistedClient->name,
        ]);
    }

    public static function addFreeleechToken(int $user_id, int $torrent_id): bool
    {
        return self::put('freeleech-tokens', [
            'user_id'    => $user_id,
            'torrent_id' => $torrent_id
        ]);
    }

    public static function addPersonalFreeleech(int $user_id): bool
    {
        return self::put('personal-freeleech', [
            'user_id' => $user_id,
        ]);
    }

    public static function removePersonalFreeleech(int $user_id): bool
    {
        return self::delete('personal-freeleech', [
            'user_id' => $user_id,
        ]);
    }

    /**
     * @return bool|array<mixed>
     */
    private static function get(string $path, ?int $id = null): bool|array
    {
        if (
            config('announce.external_tracker.is_enabled') === true
            && config('announce.external_tracker.host') !== null
            && config('announce.external_tracker.port') !== null
            && config('announce.external_tracker.key') !== null
        ) {
            $route = 'http://'.config('announce.external_tracker.host').':'.config('announce.external_tracker.port').'/announce/'.config('announce.external_tracker.key').'/'.$path.'/'.$id;
            $route = rtrim($route, "/");

            $response = Http::acceptJson()->get($route);

            if (!$response->ok()) {
                Log::notice('External tracker error - GET', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'path'   => $path,
                    'id'     => $id,
                ]);

                if ($response->notFound()) {
                    return false;
                }

                return [];
            }

            return $response->json();
        }

        return true;
    }

    /**
     * @param array<mixed> $data
     */
    private static function put(string $path, array $data): bool
    {
        if (
            config('announce.external_tracker.is_enabled') === true
            && config('announce.external_tracker.host') !== null
            && config('announce.external_tracker.port') !== null
            && config('announce.external_tracker.key') !== null
        ) {
            $isSuccess = false;
            $attemptsLeft = 3;
            $route = 'http://'.config('announce.external_tracker.host').':'.config('announce.external_tracker.port').'/announce/'.config('announce.external_tracker.key').'/'.$path;

            while (!$isSuccess && $attemptsLeft > 0) {
                $response = Http::put($route, $data);

                $isSuccess = $response->ok();

                if (!$isSuccess) {
                    $attemptsLeft -= 1;

                    if ($attemptsLeft > 0) {
                        sleep(6);
                    }
                }
            }

            if (!$isSuccess) {
                Log::notice('External tracker error - PUT', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'path'   => $path,
                    'data'   => $data,
                ]);
            }

            return $isSuccess;
        }

        return true;
    }

    /**
     * @param array<mixed> $data
     */
    private static function delete(string $path, array $data): bool
    {
        if (
            config('announce.external_tracker.is_enabled') === true
            && config('announce.external_tracker.host') !== null
            && config('announce.external_tracker.port') !== null
            && config('announce.external_tracker.key') !== null
        ) {
            $isSuccess = false;
            $attemptsLeft = 3;
            $route = 'http://'.config('announce.external_tracker.host').':'.config('announce.external_tracker.port').'/announce/'.config('announce.external_tracker.key').'/'.$path;

            while (!$isSuccess && $attemptsLeft > 0) {
                $response = Http::delete($route, $data);

                $isSuccess = $response->ok();

                if (!$isSuccess) {
                    $attemptsLeft -= 1;

                    if ($attemptsLeft > 0) {
                        sleep(6);
                    }
                }
            }

            if (!$isSuccess) {
                Log::notice('External tracker error - DELETE', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'path'   => $path,
                    'data'   => $data,
                ]);
            }

            return $isSuccess;
        }

        return true;
    }
}
