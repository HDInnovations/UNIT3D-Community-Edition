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
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @credits    Rhilip <https://github.com/Rhilip> Roardom <roardom@protonmail.com>
 */

namespace App\Http\Controllers;

use App\Exceptions\TrackerException;
use App\Jobs\ProcessAnnounce;
use App\Models\BlacklistClient;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;
use Exception;
use Illuminate\Support\Facades\Redis;

final class AnnounceController extends Controller
{
    // Torrent Moderation Codes
    protected const PENDING = 0;
    protected const REJECTED = 2;
    protected const POSTPONED = 3;

    // Announce Intervals
    private const MIN = 1_800;
    private const MAX = 3_600;

    // Port Blacklist
    private const BLACK_PORTS = [
        // Hyper Text Transfer Protocol (HTTP) - port used for web traffic
        8080,
        8081,
        // Kazaa - peer-to-peer file sharing, some known vulnerabilities, and at least one worm (Benjamin) targeting it.
        1214,
        // IANA registered for Microsoft WBT Server, used for Windows Remote Desktop and Remote Assistance connections
        3389,
        // eDonkey 2000 P2P file sharing service. http://www.edonkey2000.com/
        4662,
        // Gnutella (FrostWire, Limewire, Shareaza, etc.), BearShare file sharing app
        6346,
        6347,
        // Port used by p2p software, such as WinMX, Napster.
        6699,
    ];

    private const HEADERS = [
        'Content-Type'  => 'text/plain; charset=utf-8',
        'Cache-Control' => 'private, no-cache, no-store, must-revalidate, max-age=0',
        'Pragma'        => 'no-cache',
        'Expires'       => 0,
        'Connection'    => 'close'
    ];

    /**
     * Announce Code.
     *
     * @throws Exception
     * @throws Throwable
     */
    public function index(Request $request, string $passkey): ?Response
    {
        try {
            // Check client.
            $this->checkClient($request);

            // Check passkey.
            $this->checkPasskey($passkey);

            // Check and then get Announce queries.
            $queries = $this->checkAnnounceFields($request);

            // Check user via supplied passkey.
            $user = $this->checkUser($passkey, $queries);

            // Check users group.
            $group = $this->checkGroup($user);

            // Get Torrent Info Array from queries and judge if user can reach it.
            $torrent = $this->checkTorrent($queries['info_hash']);

            // Check if a user is announcing a torrent as completed but no peer is in db.
            $this->checkPeer($torrent, $queries, $user);

            // Lock Min Announce Interval.
            $this->checkMinInterval($torrent, $queries, $user);

            // Check User Max Connections Per Torrent.
            $this->checkMaxConnections($torrent, $user);

            // Check Download Slots.
            if (config('announce.slots_system.enabled')) {
                $this->checkDownloadSlots($queries, $torrent, $user, $group);
            }

            // Generate A Response For The Torrent Client.
            $response = $this->generateSuccessAnnounceResponse($queries, $torrent, $user);

            // Process Annnounce Job.
            $this->processAnnounceJob($queries, $user, $group, $torrent);
        } catch (TrackerException $exception) {
            $response = $this->generateFailedAnnounceResponse($exception);
        } finally {
            return $this->sendFinalAnnounceResponse($response);
        }
    }

    /**
     * Check Client Is Valid.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkClient(Request $request): void
    {
        // Query count check
        if ($request->query->count() < 6) {
            throw new TrackerException(129);
        }

        // Miss Header User-Agent is not allowed.
        if (!$request->header('User-Agent')) {
            throw new TrackerException(120);
        }

        // Block Other Browser, Crawler (May Cheater or Faker Client) by check Requests headers
        if(
            $request->header('accept-language')
            || $request->header('referer')
            || $request->header('accept-charset')

            /**
             * This header check may block Non-bittorrent client `Aria2` to access tracker,
             * Because they always add this header which other clients don't have.
             *
             * @see https://blog.rhilip.info/archives/1010/ ( in Chinese )
             */
            || $request->header('want-digest')
        ) {
            throw new TrackerException(122);
        }

        $userAgent = $request->header('User-Agent');

        // Should also block User-Agent strings that are too long. (For Database reasons)
        if (\strlen((string) $userAgent) > 64) {
            throw new TrackerException(123);
        }

        // Block Browser by checking the User-Agent
        if (preg_match('/(Mozilla|Browser|Chrome|Safari|AppleWebKit|Opera|Links|Lynx|Bot|Unknown)/i', (string) $userAgent)) {
            throw new TrackerException(121);
        }

        // Block Blacklisted Clients
        $blacklistedPeerIdPrefixes = cache()->rememberForever('client_blacklist', fn () => BlacklistClient::pluck('peer_id_prefix')->toArray());

        $peerId = $request->query->get('peer_id');

        foreach ($blacklistedPeerIdPrefixes as $blacklistedPeerIdPrefix) {
            if (str_starts_with($peerId, $blacklistedPeerIdPrefix)) {
                throw new TrackerException(128, [':ua' => $request->header('User-Agent')]);
            }
        }
    }

    /**
     * Check Passkey Exist and Valid.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkPasskey($passkey): void
    {
        // If Passkey Is Not Provided Return Error to Client
        if ($passkey === null) {
            throw new TrackerException(130, [':attribute' => 'passkey']);
        }

        // If Passkey Length Is Wrong
        if(\strlen((string) $passkey) !== 32) {
            throw new TrackerException(132, [':attribute' => 'passkey', ':rule' => 32]);
        }

        // If Passkey Format Is Wrong
        if (strspn(strtolower((string) $passkey), 'abcdef0123456789') !== 32) {
            throw new TrackerException(131, [':attribute' => 'passkey', ':reason' => 'Passkey format is incorrect']);
        }
    }

    /**
     * Extract and validate Announce fields.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkAnnounceFields(Request $request): array
    {
        $queries = [];

        // Part.1 Extract required announce fields
        foreach (['info_hash', 'peer_id', 'port', 'uploaded', 'downloaded', 'left'] as $item) {
            $itemData = $request->query->get($item);

            if (null !== $itemData) {
                $queries[$item] = $itemData;
            } else {
                throw new TrackerException(130, [':attribute' => $item]);
            }
        }

        foreach (['info_hash', 'peer_id'] as $item) {
            if (\strlen((string) $queries[$item]) !== 20) {
                throw new TrackerException(133, [':attribute' => $item, ':rule' => 20]);
            }
        }

        foreach (['port', 'uploaded', 'downloaded', 'left'] as $item) {
            $itemData = $queries[$item];

            if (!is_numeric($itemData) || $itemData < 0) {
                throw new TrackerException(134, [':attribute' => $item]);
            }

            $queries[$item] = (int) $itemData;
        }

        // Part.2 Extract optional announce fields
        foreach ([
            'event'   => '',
            'numwant' => 25,
            'corrupt' => 0,
            'key'     => '',
        ] as $item => $value) {
            $queries[$item] = $request->query->get($item, $value);
        }

        foreach (['numwant', 'corrupt'] as $item) {
            if (!is_numeric($queries[$item]) || $queries[$item] < 0) {
                throw new TrackerException(134, [':attribute' => $item]);
            }

            $queries[$item] = (int) $queries[$item];
        }

        $queries['event'] = strtolower($queries['event']);

        if (!\in_array($queries['event'], ['started', 'completed', 'stopped', 'paused', ''])) {
            throw new TrackerException(136, [':event' => $queries['event']]);
        }

        // Part.3 check Port is Valid and Allowed
        /**
         * Normally , the port must in 1 - 65535 , that is ( $port > 0 && $port < 0xffff )
         * However, in some case , When `&event=stopped` the port may set to 0.
         */
        if ($queries['port'] === 0 && $queries['event'] !== 'stopped') {
            throw new TrackerException(137, [':event' => $queries['event']]);
        }

        if (
            !is_numeric($queries['port'])
            || $queries['port'] < 1024 // Block system-reserved ports since 99.9% of the time they're fake and thus not connectable
            || $queries['port'] > 0xFFFF
            || \in_array($queries['port'], self::BLACK_PORTS, true)
        ) {
            throw new TrackerException(135, [':port' => $queries['port']]);
        }

        // Part.4 Get request ip and convert it to packed form
        $queries['ip-address'] = inet_pton($request->getClientIp());

        // Part.5 Get Users Agent
        $queries['user-agent'] = $request->headers->get('user-agent');

        return $queries;
    }

    /**
     * Get User Via Validated Passkey.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkUser(string $passkey, array $queries): object
    {
        // Check Passkey Against Users Table
        $user = cache()->remember('user:'.$passkey, 8 * 3600, fn () => User::query()
            ->select(['id', 'group_id', 'can_download'])
            ->where('passkey', '=', $passkey)
            ->first());

        // If User Doesn't Exist Return Error to Client
        if ($user === null) {
            throw new TrackerException(140);
        }

        // If User Download Rights Are Disabled Return Error to Client
        if ($user->can_download === false && $queries['left'] !== 0) {
            throw new TrackerException(142);
        }

        return $user;
    }

    /**
     * Get Users Group.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkGroup($user): object
    {
        $deniedGroups = cache()->remember('denied_groups', 8 * 3600, fn () => DB::table('groups')
            ->selectRaw("min(case when slug = 'banned' then id end) as banned_id")
            ->selectRaw("min(case when slug = 'validating' then id end) as validating_id")
            ->selectRaw("min(case when slug = 'disabled' then id end) as disabled_id")
            ->first());

        // Get The Users Group
        $group = cache()->remember('group:'.$user->group_id, 8 * 3600, fn () => Group::query()
            ->select(['id', 'download_slots', 'is_immune', 'is_freeleech', 'is_double_upload'])
            ->find($user->group_id));

        // If User Account Is Unactivated/Validating Return Error to Client
        if ($user->group_id === $deniedGroups->validating_id) {
            throw new TrackerException(141, [':status' => 'Unactivated/Validating']);
        }

        // If User Is Banned Return Error to Client
        if ($user->group_id === $deniedGroups->banned_id) {
            throw new TrackerException(141, [':status' => 'Banned']);
        }

        // If User Is Disabled Return Error to Client
        if ($user->group_id === $deniedGroups->disabled_id) {
            throw new TrackerException(141, [':status' => 'Disabled']);
        }

        return $group;
    }

    /**
     * Check If Torrent Exist In Database.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkTorrent(string $infoHash): object
    {
        $torrent = cache()->remember(
            'announce-torrents:by-infohash:'.$infoHash,
            8 * 3600,
            fn () => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->select(['id', 'free', 'doubleup', 'seeders', 'leechers', 'times_completed', 'status'])
                ->where('info_hash', '=', $infoHash)
                ->firstOr(fn (): string => '-1')
        );

        // If Torrent Doesn't Exsist Return Error to Client
        if ($torrent === '-1') {
            throw new TrackerException(150);
        }

        // If Torrent Is Pending Moderation Return Error to Client
        if ($torrent->status === self::PENDING) {
            throw new TrackerException(151, [':status' => 'PENDING In Moderation']);
        }

        // If Torrent Is Rejected Return Error to Client
        if ($torrent->status === self::REJECTED) {
            throw new TrackerException(151, [':status' => 'REJECTED In Moderation']);
        }

        // If Torrent Is Postponed Return Error to Client
        if ($torrent->status === self::POSTPONED) {
            throw new TrackerException(151, [':status' => 'POSTPONED In Moderation']);
        }

        // Don't use eager loading so that we can make use of mysql prepared statement caching.
        // If we use eager loading, then laravel will use `where torrent_id in (123)` instead of `where torrent_id = ?`
        $torrent->setRelation(
            'peers',
            Peer::select(['id', 'torrent_id', 'peer_id', 'user_id', 'downloaded', 'uploaded', 'left', 'seeder', 'active', 'ip', 'port', 'updated_at'])
                ->where('torrent_id', '=', $torrent->id)
                ->get()
        );

        return $torrent;
    }

    /**
     * Check If Peer Exist In Database.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkPeer(object $torrent, array $queries, object $user): void
    {
        if (
            $queries['event'] === 'completed'
            && $torrent->peers
                ->where('peer_id', '=', $queries['peer_id'])
                ->where('user_id', '=', $user->id)
                ->isEmpty()
        ) {
            throw new TrackerException(152);
        }
    }

    /**
     * Check A Peers Min Annnounce Interval.
     *
     * @throws TrackerException
     * @throws Exception
     * @throws Throwable
     */
    private function checkMinInterval(object $torrent, array $queries, object $user): void
    {
        $event = match ($queries['event']) {
            'started'   => 'started',
            'completed' => 'completed',
            'stopped'   => 'stopped',
            default     => 'empty',
        };

        $now = now()->timestamp;

        // Detect broken (namely qBittorrent) clients sending duplicate announces
        // and eliminate them from screwing up stats.

        $duplicateAnnounceKey = config('cache.prefix').'announce-lock:'.$user->id.'-'.$torrent->id.'-'.$queries['peer_id'].'-'.$event;

        $lastAnnouncedAt = Redis::connection('announce')->command('SET', [$duplicateAnnounceKey, $now, ['NX', 'GET', 'EX' => 30]]);

        if ($lastAnnouncedAt !== false) {
            throw new TrackerException(162, [':elapsed' => $now - $lastAnnouncedAt]);
        }

        // Block clients disrespecting the min interval

        $lastAnnouncedKey = config('cache.prefix').'peer-last-announced:'.$user->id.'-'.$torrent->id.'-'.$queries['peer_id'];

        $randomMinInterval = intdiv(random_int(85, 95) * self::MIN, 100);

        $lastAnnouncedAt = Redis::connection('announce')->command('SET', [$lastAnnouncedKey, $now, ['NX', 'GET', 'EX' => $randomMinInterval]]);

        // Delete the timer if the user paused the torrent, and it's been at
        // least 5 minutes since they last announced.
        if ($event === 'stopped' && $lastAnnouncedAt < $now - 5 * 60) {
            Redis::connection('announce')->command('DEL', [$lastAnnouncedKey]);
        } elseif ($lastAnnouncedAt !== false && !\in_array($event, ['completed', 'stopped'])) {
            throw new TrackerException(162, [':elapsed' => $now - $lastAnnouncedAt]);
        }
    }

    /**
     * Check A Users Max Connections.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkMaxConnections(object $torrent, object $user): void
    {
        // Pull Count On Users Peers Per Torrent For Rate Limiting
        $connections = $torrent->peers
            ->where('user_id', '=', $user->id)
            ->where('active', '=', true)
            ->count();

        // If Users Peer Count On A Single Torrent Is Greater Than X Return Error to Client
        if ($connections > config('announce.rate_limit')) {
            throw new TrackerException(138, [':limit' => config('announce.rate_limit')]);
        }
    }

    /**
     * Check A Users Download Slots.
     *
     * @throws TrackerException
     * @throws Throwable
     */
    private function checkDownloadSlots(array $queries, object $torrent, object $user, object $group): void
    {
        $max = $group->download_slots;

        $peer = $torrent->peers
            ->where('peer_id', '=', $queries['peer_id'])
            ->where('user_id', '=', $user->id)
            ->first();

        $cacheKey = 'user-leeching-count:'.$user->id;

        $count = cache()->get($cacheKey, 0);

        $isNewPeer = $peer === null;
        $isDeadPeer = $queries['event'] === 'stopped';
        $isSeeder = $queries['left'] === 0;

        $newLeech = $isNewPeer && !$isDeadPeer && !$isSeeder;
        $stoppedLeech = !$isNewPeer && $isDeadPeer && !$isSeeder;
        $leechBecomesSeed = !$isNewPeer && !$isDeadPeer && $isSeeder && $peer->left > 0;
        $seedBecomesLeech = !$isNewPeer && !$isDeadPeer && !$isSeeder && $peer->left === 0;

        if ($max !== null && $max >= 0 && ($newLeech || $seedBecomesLeech) && $count >= $max) {
            throw new TrackerException(164, [':max' => $max]);
        }

        if ($newLeech || $seedBecomesLeech) {
            cache()->increment($cacheKey);
        } elseif ($stoppedLeech || $leechBecomesSeed) {
            cache()->decrement($cacheKey);
        }
    }

    /**
     * Generate A Successful Announce Response For Client.
     *
     * @throws Exception
     */
    private function generateSuccessAnnounceResponse(array $queries, object $torrent, object $user): string
    {
        // Build Response For Bittorrent Client
        // Keys must be ordered alphabetically
        $response = 'd8:completei'
            .$torrent->seeders
            .'e10:downloadedi'
            .$torrent->times_completed
            .'e10:incompletei'
            .$torrent->leechers
            .'e8:intervali'
            .random_int(self::MIN, self::MAX)
            .'e12:min intervali'
            .self::MIN
            .'e';

        $peersIpv4 = '';
        $peersIpv6 = '';
        $peerCount = 0;

        /**
         * For non `stopped` event only where either the torrent has at least one leech, or the user is a leech.
         * We query peers from database and send peerlist, otherwise just quick return.
         */
        if ($queries['event'] !== 'stopped' && ($queries['left'] !== 0 || $torrent->leechers !== 0)) {
            $limit = (min($queries['numwant'], 25));

            // Get Torrents Peers (Only include leechers in a seeder's peerlist)
            if ($queries['left'] === 0) {
                foreach ($torrent->peers as $peer) {
                    // Don't include other seeders, inactive peers, nor other peers belonging to the same user
                    if ($peer->seeder || !$peer->active || $peer->user_id === $user->id) {
                        continue;
                    }

                    switch (\strlen((string) $peer['ip'])) {
                        case 4:
                            $peersIpv4 .= $peer['ip'].pack('n', (int) $peer['port']);
                            $peerCount++;

                            break;
                        case 16:
                            $peersIpv6 .= $peer['ip'].pack('n', (int) $peer['port']);
                            $peerCount++;
                    }

                    if ($peerCount >= $limit) {
                        break;
                    }
                }
            } else {
                foreach ($torrent->peers as $peer) {
                    // Don't include inactive peers, nor other peers belonging to the same user
                    if (!$peer->active || $peer->user_id === $user->id) {
                        continue;
                    }

                    switch (\strlen((string) $peer['ip'])) {
                        case 4:
                            $peersIpv4 .= $peer['ip'].pack('n', (int) $peer['port']);
                            $peerCount++;

                            break;
                        case 16:
                            $peersIpv6 .= $peer['ip'].pack('n', (int) $peer['port']);
                            $peerCount++;
                    }

                    if ($peerCount >= $limit) {
                        break;
                    }
                }
            }
        }

        if ($peersIpv6 === '') {
            return $response.'5:peers'.\strlen($peersIpv4).':'.$peersIpv4.'e';
        }

        return $response.'5:peers'
            .\strlen($peersIpv4).':'.$peersIpv4
            .'6:peers6'
            .\strlen($peersIpv6).':'.$peersIpv6.'e';
    }

    /**
     * Process Announce Database Queries.
     */
    private function processAnnounceJob(array $queries, object $user, object $group, object $torrent): void
    {
        // Set Variables
        $event = $queries['event'];

        // Get The Current Peer
        $peer = null;

        foreach ($torrent->peers as $torrentPeer) {
            if ($torrentPeer->user_id === $user->id && $torrentPeer->peer_id === $queries['peer_id']) {
                $peer = $torrentPeer;

                break;
            }
        }

        $isNewPeer = $peer === null;

        // Calculate the change in upload/download compared to the last announce
        $uploadedDelta = max($queries['uploaded'] - ($peer?->uploaded ?? 0), 0);
        $downloadedDelta = max($queries['downloaded'] - ($peer?->downloaded ?? 0), 0);

        // If no peer record found then set deltas to 0 and change to `started` event
        if ($isNewPeer) {
            if ($queries['uploaded'] > 0 || $queries['downloaded'] > 0) {
                $event = 'started';
                $uploadedDelta = 0;
                $downloadedDelta = 0;
            }

            $peer = new Peer();
        }

        // Check if user currently has a personal freeleech
        $personalFreeleech = cache()->rememberForever(
            'personal_freeleech:'.$user->id,
            fn () => PersonalFreeleech::query()
                ->where('user_id', '=', $user->id)
                ->exists()
        );

        // Check if user has a freeleech token on this torrent
        $freeleechToken = cache()->rememberForever(
            'freeleech_token:'.$user->id.':'.$torrent->id,
            fn () => FreeleechToken::query()
                ->where('user_id', '=', $user->id)
                ->where('torrent_id', '=', $torrent->id)
                ->exists(),
        );

        // Calculate credited Download
        if (
            $personalFreeleech
            || $group->is_freeleech
            || $freeleechToken
            || config('other.freeleech')
        ) {
            $creditedDownloadedDelta = 0;
        } elseif ($torrent->free >= 1) {
            // Freeleech values in the database are from 0 to 100
            // 0 means 0% of the bytes are freeleech, i.e. 100% of the bytes are counted.
            // 100 means 100% of the bytes are freeleech, i.e. 0% of the bytes are counted.
            // This means we have to subtract the value stored in the database from 100 before multiplying.
            // Also make sure that 100% is the highest value of freeleech possible
            // in order to not subtract download from an account.
            $creditedDownloadedDelta = $downloadedDelta * (100 - min(100, $torrent->free)) / 100;
        } else {
            $creditedDownloadedDelta = $downloadedDelta;
        }

        // Calculate credited upload
        if (
            $torrent->doubleup
            || $group->is_double_upload
            || config('other.doubleup')
        ) {
            $creditedUploadedDelta = $uploadedDelta * 2;
        } else {
            $creditedUploadedDelta = $uploadedDelta;
        }

        // User Updates
        if (($creditedUploadedDelta > 0 || $creditedDownloadedDelta > 0) && $event !== 'started') {
            $user->update([
                'uploaded'   => DB::raw('uploaded + '.(int) $creditedUploadedDelta),
                'downloaded' => DB::raw('downloaded + '.(int) $creditedDownloadedDelta),
            ]);
        }

        // Peer Updates
        // Don't Dispatch ProcessAnnounce Job To Queue If Connectable Check Is Disabled For Performance Reasons
        if (config('announce.connectable_check')) {
            /**
             * Process Peers Job.
             *
             * @see ProcessAnnounce
             */
            ProcessAnnounce::dispatch(
                bin2hex($queries['peer_id']),
                bin2hex($queries['ip-address']),
                $queries['port'],
                bin2hex($queries['user-agent']),
                $queries['uploaded'],
                $queries['downloaded'],
                $queries['left'],
                $queries['left'] === 0,
                $torrent->id,
                $user->id,
                $event !== 'stopped',
            );
        } else {
            /**
             * Peer batch upsert.
             *
             * @see \App\Console\Commands\AutoUpsertPeers
             */
            Redis::connection('announce')->command('RPUSH', [
                config('cache.prefix').':peers:batch',
                serialize([
                    'peer_id'     => $queries['peer_id'],
                    'ip'          => $queries['ip-address'],
                    'port'        => $queries['port'],
                    'agent'       => $queries['user-agent'],
                    'uploaded'    => $queries['uploaded'],
                    'downloaded'  => $queries['downloaded'],
                    'left'        => $queries['left'],
                    'seeder'      => $queries['left'] === 0,
                    'torrent_id'  => $torrent->id,
                    'user_id'     => $user->id,
                    'active'      => $event !== 'stopped',
                    'connectable' => false,
                ])
            ]);
        }

        /**
         * History batch upsert.
         *
         * @see \App\Console\Commands\AutoUpsertHistories
         */
        Redis::connection('announce')->command('RPUSH', [
            config('cache.prefix').':histories:batch',
            serialize([
                'user_id'           => $user->id,
                'torrent_id'        => $torrent->id,
                'agent'             => $queries['user-agent'],
                'uploaded'          => $event === 'started' ? 0 : $creditedUploadedDelta,
                'actual_uploaded'   => $event === 'started' ? 0 : $uploadedDelta,
                'client_uploaded'   => $queries['uploaded'],
                'downloaded'        => $event === 'started' ? 0 : $creditedDownloadedDelta,
                'actual_downloaded' => $event === 'started' ? 0 : $downloadedDelta,
                'client_downloaded' => $queries['downloaded'],
                'seeder'            => $queries['left'] === 0,
                'active'            => $event !== 'stopped',
                'seedtime'          => 0,
                'immune'            => $group->is_immune,
                'completed_at'      => $event === 'completed' ? now() : null,
            ])
        ]);

        if (config('announce.log_announces')) {
            /**
             * Announce batch upsert.
             *
             * @see \App\Console\Commands\AutoUpsertAnnounces
             */
            Redis::connection('announce')->command('RPUSH', [
                config('cache.prefix').':announces:batch',
                serialize([
                    'user_id'    => $user->id,
                    'torrent_id' => $torrent->id,
                    'uploaded'   => $queries['uploaded'],
                    'downloaded' => $queries['downloaded'],
                    'left'       => $queries['left'],
                    'corrupt'    => $queries['corrupt'],
                    'peer_id'    => $queries['peer_id'],
                    'port'       => $queries['port'],
                    'numwant'    => $queries['numwant'],
                    'event'      => $queries['event'],
                    'key'        => $queries['key'],
                ])
            ]);
        }

        // Torrent updates

        $isNewPeer = $isNewPeer || !$peer->active;
        $isDeadPeer = $event === 'stopped';
        $isSeeder = $queries['left'] === 0;

        $newSeed = $isNewPeer && !$isDeadPeer && $isSeeder;
        $newLeech = $isNewPeer && !$isDeadPeer && !$isSeeder;
        $stoppedSeed = !$isNewPeer && $isDeadPeer && $isSeeder;
        $stoppedLeech = !$isNewPeer && $isDeadPeer && !$isSeeder;
        $leechBecomesSeed = !$isNewPeer && !$isDeadPeer && $isSeeder && $peer->left > 0;
        $seedBecomesLeech = !$isNewPeer && !$isDeadPeer && !$isSeeder && $peer->left === 0;

        $seederCountDelta = ($newSeed || $leechBecomesSeed) <=> ($stoppedSeed || $seedBecomesLeech);
        $leecherCountDelta = ($newLeech || $seedBecomesLeech) <=> ($stoppedLeech || $leechBecomesSeed);
        $completedCountDelta = (int) ($event === 'completed');

        if ($seederCountDelta !== 0 || $leecherCountDelta !== 0 || $completedCountDelta !== 0) {
            $torrent->update([
                'seeders'         => DB::raw('seeders + '.$seederCountDelta),
                'leechers'        => DB::raw('leechers + '.$leecherCountDelta),
                'times_completed' => DB::raw('times_completed + '.$completedCountDelta),
            ]);

            cache()->forget('announce-torrents:by-infohash:'.$queries['info_hash']);
        }
    }

    private function generateFailedAnnounceResponse(TrackerException $trackerException): string
    {
        $message = $trackerException->getMessage();

        return 'd14:failure reason'.\strlen($message).':'.$message.'8:intervali'.self::MIN.'e12:min intervali'.self::MIN.'ee';
    }

    /**
     * Send Final Announce Response.
     */
    private function sendFinalAnnounceResponse(string $response): Response
    {
        return response($response, headers: self::HEADERS);
    }
}
