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
     * @return bool|null|array{
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
    public static function getTorrent(int $torrentId): null|bool|array
    {
        return self::get('torrents', $torrentId);
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
     * @return bool|null|array{
     *     id: int,
     *     group_id: int,
     *     passkey: string,
     *     can_download: bool,
     *     num_seeding: int,
     *     num_leeching: int,
     * }
     */
    public static function getUser(int $userId): null|bool|array
    {
        return self::get('users', $userId);
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
     * @param  string            $path
     * @param  int               $id
     * @return bool|array<mixed>
     */
    private static function get(string $path, int $id): bool|array
    {
        if (
            config('announce.external_tracker.is_enabled') === true
            && config('announce.external_tracker.host') !== null
            && config('announce.external_tracker.port') !== null
            && config('announce.external_tracker.key') !== null
        ) {
            $route = 'http://'.config('announce.external_tracker.host').':'.config('announce.external_tracker.port').'/announce/'.config('announce.external_tracker.key').'/'.$path.'/'.$id;

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
