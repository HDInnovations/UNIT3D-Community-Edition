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
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Unit3dAnnounce
{
    public static function addTorrent(Torrent $torrent): bool
    {
        $isSuccess = self::put('torrents', [
            'id'              => $torrent->id,
            'status'          => $torrent->status,
            'info_hash'       => bin2hex($torrent->info_hash),
            'is_deleted'      => false,
            'seeders'         => $torrent->seeders,
            'leechers'        => $torrent->leechers,
            'times_completed' => $torrent->times_completed,
            'download_factor' => 100,
            'upload_factor'   => 100,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to add torrent.', ['id' => $torrent->id]);
        }

        return $isSuccess;
    }

    public static function removeTorrent(Torrent $torrent): bool
    {
        $isSuccess = self::delete('torrents', [
            'id'        => $torrent->id,
            'info_hash' => bin2hex($torrent->info_hash),
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to remove torrent', ['id' => $torrent->id]);
        }

        return $isSuccess;
    }

    public static function addUser(User $user): bool
    {
        if ($user->deleted_at !== null) {
            return true;
        }

        $user->load('group');

        if (\in_array($user->group->slug, ['banned', 'validating', 'disabled'])) {
            return true;
        }

        $peers = Peer::query()
            ->where('user_id', '=', $user->id)
            ->selectRaw('count(case when seeder = 1 then 1 end) as num_seeding, count(case when seeder = 0 then 1 end) as num_leeching')
            ->first();

        $isSuccess = self::put('users', [
            'id'              => $user->id,
            'passkey'         => $user->passkey,
            'can_download'    => (bool) $user->can_download,
            'download_slots'  => $user->group->download_slots,
            'is_immune'       => (bool) $user->group->is_immune,
            'num_seeding'     => $peers->num_seeding ?? 0,
            'num_leeching'    => $peers->num_leeching ?? 0,
            'download_factor' => $user->group->is_freeleech ? 0 : 100,
            'upload_factor'   => $user->group->is_double_upload ? 200 : 100,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to add user', ['id' => $user->id]);
        }

        return $isSuccess;
    }

    public static function removeUser(User $user): bool
    {
        $isSuccess = self::delete('users', [
            'id'      => $user->id,
            'passkey' => $user->passkey,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to remove user', ['id' => $user->id]);
        }

        return $isSuccess;
    }

    public static function addBlacklistedAgent(String $blacklistedAgent): bool
    {
        $isSuccess = self::put('blacklisted-agents', [
            'name' => $blacklistedAgent,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to add blacklisted agent.', ['name' => $blacklistedAgent]);
        }

        return $isSuccess;
    }

    public static function removeBlacklistedAgent(BlacklistClient $blacklistedClient): bool
    {
        $isSuccess = self::delete('blacklisted-agents', [
            'name' => $blacklistedClient->name,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to remove blacklisted agent.', ['name' => $blacklistedClient->name]);
        }

        return $isSuccess;
    }

    public static function addFreeleechToken(int $user_id, int $torrent_id): bool
    {
        $isSuccess = self::put('freeleech-tokens', [
            'user_id'    => $user_id,
            'torrent_id' => $torrent_id
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to add freeleech token.', ['user_id' => $user_id, 'torrent_id' => $torrent_id]);
        }

        return $isSuccess;
    }

    public static function addPersonalFreeleech(int $user_id): bool
    {
        $isSuccess = self::put('personal-freeleech', [
            'user_id' => $user_id,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to add personal freeleech.', ['user_id' => $user_id]);
        }

        return $isSuccess;
    }

    public static function removePersonalFreeleech(int $user_id): bool
    {
        $isSuccess = self::delete('personal-freeleech', [
            'user_id' => $user_id,
        ]);

        if (! $isSuccess) {
            Log::notice('TRACKER - Failed to remove personal freeleech.', ['user_id' => $user_id]);
        }

        return $isSuccess;
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

            while (! $isSuccess && $attemptsLeft > 0) {
                $response = Http::put($route, $data);

                $isSuccess = $response->ok();

                if (! $isSuccess) {
                    $attemptsLeft -= 1;

                    if ($attemptsLeft > 0) {
                        sleep(6);
                    }
                }
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

            while (! $isSuccess && $attemptsLeft > 0) {
                $response = Http::delete($route, $data);

                $isSuccess = $response->ok();

                if (! $isSuccess) {
                    $attemptsLeft -= 1;

                    if ($attemptsLeft > 0) {
                        sleep(6);
                    }
                }
            }

            return $isSuccess;
        }

        return true;
    }
}
