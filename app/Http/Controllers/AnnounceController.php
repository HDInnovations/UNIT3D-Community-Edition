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

namespace App\Http\Controllers;

use App\Helpers\Bencode;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use App\Models\Torrent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnounceController extends Controller
{
    // Torrent Moderation Codes
    protected const PENDING = 0;
    protected const APPROVED = 1;
    protected const REJECTED = 2;
    protected const POSTPONED = 3;

    /**
     * Announce Code.
     *
     * @param Request $request
     * @param $passkey
     *
     * @throws \Exception
     *
     * @return Bencode response for the torrent client
     */
    public function announce(Request $request, $passkey)
    {
        // For Performance Logging Only!
        /*\DB::listen(function($sql) {
            \Log::info($sql->sql);
            \Log::info($sql->bindings);
            \Log::info($sql->time);
        });*/

        // Check Announce Request Method
        $method = $request->method();
        if (!$request->isMethod('get')) {
            info('Announce Request Method Was Not GET');

            return response(Bencode::bencode(['failure reason' => 'Invalid Request Type: Client Request Was Not A HTTP GET.']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Request Agent Information
        $agent = $request->server('HTTP_USER_AGENT');

        // Blacklist
        if (config('client-blacklist.enabled') == true) {
            // Check If Browser Is Blacklisted
            $blockedBrowsers = config('client-blacklist.browsers');
            if (in_array($agent, $blockedBrowsers)) {
                abort(405, 'What Are You Trying To Do?');
                die();
            }

            // Check If Client Is Blacklisted
            $blockedClients = config('client-blacklist.clients');
            if (in_array($agent, $blockedClients)) {
                //info('Blacklist Client Attempted To Connect To Announce');
                return response(Bencode::bencode(['failure reason' => 'The Client You Are Trying To Use Has Been Blacklisted']))->withHeaders(['Content-Type' => 'text/plain']);
            }
        }

        // If Passkey Is Not Provided Return Error to Client
        if ($passkey == null) {
            //info('Client Attempted To Connect To Announce Without A Passkey');
            return response(Bencode::bencode(['failure reason' => 'Please Call Passkey']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Infohash Is Not Provided Return Error to Client
        if (!$request->has('info_hash')) {
            //info('Client Attempted To Connect To Announce Without A Infohash');
            return response(Bencode::bencode(['failure reason' => 'Missing info_hash']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Peerid Is Not Provided Return Error to Client
        if (!$request->has('peer_id')) {
            //info('Client Attempted To Connect To Announce Without A Peerid');
            return response(Bencode::bencode(['failure reason' => 'Missing peer_id']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Port Is Not Provided Return Error to Client
        if (!$request->has('port')) {
            //info('Client Attempted To Connect To Announce Without A Specified Port');
            return response(Bencode::bencode(['failure reason' => 'Missing port']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If "Left" Is Not Provided Return Error to Client
        if (!$request->has('left')) {
            //info('Client Attempted To Connect To Announce Without Supplying Any "Left" Information');
            return response(Bencode::bencode(['failure reason' => 'Missing left']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If "Upload" Is Not Provided Return Error to Client
        if (!$request->has('uploaded')) {
            //info('Client Attempted To Connect To Announce Without Supplying Any "Upload" Information');
            return response(Bencode::bencode(['failure reason' => 'Missing upload']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If "Download" Is Not Provided Return Error to Client
        if (!$request->has('downloaded')) {
            //info('Client Attempted To Connect To Announce Without Supplying Any "Download" Information');
            return response(Bencode::bencode(['failure reason' => 'Missing download']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Check Passkey Against Cache or Users Table
        $user = Cache::get("user.{$passkey}") ?? User::where('passkey', '=', $passkey)->first();

        // If Passkey Doesn't Exist Return Error to Client
        if ($user === null) {
            //info('Client Attempted To Connect To Announce With A Invalid Passkey');
            return response(Bencode::bencode(['failure reason' => 'Passkey is invalid']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Caached System Required Groups
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $validating_group = cache()->rememberForever('validating_group', function () {
            return Group::where('slug', '=', 'validating')->pluck('id');
        });
        $disabled_group = cache()->rememberForever('disabled_group', function () {
            return Group::where('slug', '=', 'disabled')->pluck('id');
        });

        // If User Is Banned Return Error to Client
        if ($user->group_id == $banned_group[0]) {
            //info('A Banned User (' . $user->username . ') Attempted To Announce');
            return response(Bencode::bencode(['failure reason' => 'You are no longer welcome here']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If User Is Disabled Return Error to Client
        if ($user->group_id == $disabled_group[0]) {
            //info('A Disabled User (' . $user->username . ') Attempted To Announce');
            return response(Bencode::bencode(['failure reason' => 'Your account is disabled. Please login.']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If User Account Is Unactivated Return Error to Client
        if ($user->active == 0) {
            //info('A Unactivated User (' . $user->username . ') Attempted To Announce');
            return response(Bencode::bencode(['failure reason' => 'Your account is not activated']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If User Is Validating Return Error to Client
        if ($user->group_id == $validating_group[0]) {
            //info('A Validating User (' . $user->username . ') Attempted To Announce');
            return response(Bencode::bencode(['failure reason' => 'Your account is still validating']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Standard Information Fields
        $event = $request->input('event');
        $info_hash = bin2hex($request->input('info_hash'));
        $peer_id = bin2hex($request->input('peer_id'));
        $md5_peer_id = md5($peer_id);
        $ip = $request->ip();
        $port = (int) $request->input('port');
        $left = (float) $request->input('left');
        $uploaded = (float) $request->input('uploaded');
        $real_uploaded = $uploaded;
        $downloaded = (float) $request->input('downloaded');
        $real_downloaded = $downloaded;

        //Extra Information Fields
        $tracker_id = $request->has('trackerid') ? bin2hex($request->input('tracker_id')) : null;
        $compact = $request->input('compact') == 1;
        $key = $request->has('key') ? bin2hex($request->input('key')) : null;
        $corrupt = $request->has('corrupt') ? $request->input('corrupt') : null;
        $ipv6 = $request->has('ipv6') ? bin2hex($request->input('ipv6')) : null;
        $no_peer_id = $request->input('no_peer_id') == 1;

        // If User Download Rights Are Disabled Return Error to Client
        if ($user->can_download == 0 && $left != 0) {
            //info('A User With Revoked Download Privileges Attempted To Announce');
            return response(Bencode::bencode(['failure reason' => 'You download privileges are Revoked']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If User Client Is Sending Negative Values Return Error to Client
        if ($uploaded < 0 || $downloaded < 0 || $left < 0) {
            //info('Client Attempted To Send Data With A Negative Value');
            return response(Bencode::bencode(['failure reason' => 'Data from client is a negative value']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If User Client Does Not Support Compact Return Error to Client
        if (!$compact) {
            //info('Client Attempted To Connect To Announce But Doesn't Support Compact');
            return response(Bencode::bencode(['failure reason' => "Your client doesn't support compact, please update your client"]))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Check Info Hash Against Cache or Torrents Table
        $torrent = Cache::get("torrent.{$info_hash}") ?? Torrent::select(['id', 'status', 'free', 'doubleup', 'times_completed', 'seeders', 'leechers'])->withAnyStatus()->where('info_hash', '=', $info_hash)->first();

        // If Torrent Doesnt Exsist Return Error to Client
        if ($torrent === null) {
            //info('Client Attempted To Connect To Announce But The Torrent Doesn't Exist Using Hash '  . $info_hash);
            return response(Bencode::bencode(['failure reason' => 'Torrent not found']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Torrent Is Pending Moderation Return Error to Client
        if ($torrent->status == self::PENDING) {
            //info('Client Attempted To Connect To Announce But The Torrent Is Pending Moderation');
            return response(Bencode::bencode(['failure reason' => 'Torrent is still pending moderation']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Torrent Is Rejected Return Error to Client
        if ($torrent->status == self::REJECTED) {
            //info('Client Attempted To Connect To Announce But The Torrent Is Rejected');
            return response(Bencode::bencode(['failure reason' => 'Torrent has been rejected']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Torrent Is Postponed Return Error to Client
        if ($torrent->status == self::POSTPONED) {
            //info('Client Attempted To Connect To Announce But The Torrent Is Postponed');
            return response(Bencode::bencode(['failure reason' => 'Torrent has been postponed']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Get Torrents Peers
        $peers = Peer::where('torrent_id', '=', $torrent->id)->take(50)->get()->toArray();

        // Pull Count On Users Peers Per Torrent For Rate Limiting
        $connections = Cache::remember("user_connections.{$torrent->id}", 1800, function () use ($torrent, $user) {
            return Peer::where('torrent_id', '=', $torrent->id)->where('user_id', '=', $user->id)->count();
        });

        // If Users Peer Count On A Single Torrent Is Greater Than X Return Error to Client
        if ($connections > config('announce.rate_limit')) {
            //info('Client Attempted To Connect To Announce But Has Hit Rate Limits');
            return response(Bencode::bencode(['failure reason' => 'You have reached the rate limit']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Get The Current Peer
        $peer = Peer::where('torrent_id', '=', $torrent->id)->where('md5_peer_id', $md5_peer_id)->where('user_id', '=', $user->id)->first();

        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;
        if ($peer === null && $event == 'completed') {
            return response(Bencode::bencode(['failure reason' => 'Torrent is complete but no record found.']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Creates a new peer if not existing
        if ($peer === null) {
            if ($uploaded > 0 || $downloaded > 0) {
                $ghost = true;
                $event = 'started';
            }
            $peer = new Peer();
        }

        // Get history information
        $history = History::where('info_hash', '=', $info_hash)->where('user_id', '=', $user->id)->first();

        // If no History record found then create one
        if ($history === null) {
            $history = new History();
            $history->user_id = $user->id;
            $history->info_hash = $info_hash;
        }

        if ($ghost) {
            $uploaded = ($real_uploaded >= $history->client_uploaded) ? ($real_uploaded - $history->client_uploaded) : 0;
            $downloaded = ($real_downloaded >= $history->client_downloaded) ? ($real_downloaded - $history->client_downloaded) : 0;
        } else {
            $uploaded = ($real_uploaded >= $peer->uploaded) ? ($real_uploaded - $peer->uploaded) : 0;
            $downloaded = ($real_downloaded >= $peer->downloaded) ? ($real_downloaded - $peer->downloaded) : 0;
        }

        $old_update = $peer->updated_at ? $peer->updated_at->timestamp : Carbon::now()->timestamp;

        // Modification of Upload and Download
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $freeleech_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        $group = Group::whereId($user->group_id)->first();

        if (config('other.freeleech') == 1 || $torrent->free == 1 || $personal_freeleech || $group->is_freeleech == 1 || $freeleech_token) {
            $mod_downloaded = 0;
        } else {
            $mod_downloaded = $downloaded;
        }

        $mod_uploaded = config('other.doubleup') == 1 || $torrent->doubleup == 1 ? $uploaded * 2 : $uploaded;

        if ($event == 'started') {
            // Peer Update
            $peer->peer_id = $peer_id;
            $peer->md5_peer_id = $md5_peer_id;
            $peer->info_hash = $info_hash;
            $peer->ip = $request->ip();
            $peer->port = $port;
            $peer->agent = $agent;
            $peer->uploaded = $real_uploaded;
            $peer->downloaded = $real_downloaded;
            $peer->seeder = $left == 0;
            $peer->left = $left;
            $peer->torrent_id = $torrent->id;
            $peer->user_id = $user->id;
            $peer->save();
            // End Peer Update

            // History Update
            $history->agent = $agent;
            $history->active = 1;
            $history->seeder = $left == 0;
            $history->immune = $user->group->is_immune == 1;
            $history->uploaded += 0;
            $history->actual_uploaded += 0;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += 0;
            $history->actual_downloaded += 0;
            $history->client_downloaded = $real_downloaded;
            $history->save();
        // End History Update

            // Never push stats to users account on start event
        } elseif ($event == 'completed') {
            // Peer Update
            $peer->peer_id = $peer_id;
            $peer->md5_peer_id = $md5_peer_id;
            $peer->info_hash = $info_hash;
            $peer->ip = $request->ip();
            $peer->port = $port;
            $peer->agent = $agent;
            $peer->uploaded = $real_uploaded;
            $peer->downloaded = $real_downloaded;
            $peer->seeder = 1;
            $peer->left = 0;
            $peer->torrent_id = $torrent->id;
            $peer->user_id = $user->id;
            $peer->save();
            // End Peer Update

            // History Update
            $history->agent = $agent;
            $history->active = 1;
            $history->seeder = $left == 0;
            $history->immune = $user->group->is_immune == 1;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_downloaded;
            $history->completed_at = Carbon::now();
            // Seedtime Allocation
            if ($left == 0) {
                $new_update = $peer->updated_at->timestamp;
                $diff = $new_update - $old_update;
                $history->seedtime += $diff;
            }
            $history->save();
            // End History Update

            // User Update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User Update

            // Torrent Completed Update
            $torrent->times_completed++;
        // End Torrent Completed Update
        } elseif ($event == 'stopped') {
            //Peer Update
            $peer->peer_id = $peer_id;
            $peer->md5_peer_id = $md5_peer_id;
            $peer->info_hash = $info_hash;
            $peer->ip = $request->ip();
            $peer->port = $port;
            $peer->agent = $agent;
            $peer->uploaded = $real_uploaded;
            $peer->downloaded = $real_downloaded;
            $peer->seeder = $left == 0;
            $peer->left = $left;
            $peer->torrent_id = $torrent->id;
            $peer->user_id = $user->id;
            $peer->save();
            //End Peer Update

            // History Update
            $history->agent = $agent;
            $history->active = 0;
            $history->seeder = $left == 0;
            $history->immune = $user->group->is_immune == 1;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = 0;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = 0;
            // Seedtime allocation
            if ($left == 0) {
                $new_update = $peer->updated_at->timestamp;
                $diff = $new_update - $old_update;
                $history->seedtime += $diff;
            }
            $history->save();
            // End History Update

            // Peer Delete (Now that history is updated)
            $peer->delete();
            // End Peer Delete

            // User Update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
        // End User Update
        } else {
            // Peer Update
            $peer->peer_id = $peer_id;
            $peer->md5_peer_id = $md5_peer_id;
            $peer->info_hash = $info_hash;
            $peer->ip = $request->ip();
            $peer->port = $port;
            $peer->agent = $agent;
            $peer->uploaded = $real_uploaded;
            $peer->downloaded = $real_downloaded;
            $peer->seeder = $left == 0;
            $peer->left = $left;
            $peer->torrent_id = $torrent->id;
            $peer->user_id = $user->id;
            $peer->save();
            // End Peer Update

            // History Update
            $history->agent = $agent;
            $history->active = 1;
            $history->seeder = $left == 0;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_downloaded;
            // Seedtime allocation
            if ($left == 0) {
                $new_update = $peer->updated_at->timestamp;
                $diff = $new_update - $old_update;
                $history->seedtime += $diff;
            }
            $history->save();
            // End History Update

            // User Update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User Update
        }

        // Torrent Update
        $torrent->seeders = Peer::where('torrent_id', '=', $torrent->id)->where('left', '=', '0')->count();
        $torrent->leechers = Peer::where('torrent_id', '=', $torrent->id)->where('left', '>', '0')->count();
        $torrent->save();
        // End Torrent Update

        // Build Response For Bittorrent Client
        $res = [];
        $min = 2400; // 40 Minutes
        $max = 3600; // 60 Minutes
        $res['interval'] = rand($min, $max);
        $res['min interval'] = 1800; // 30 Minutes
        $res['tracker_id'] = $md5_peer_id; // A string that the client should send back on its next announcements.
        $res['complete'] = $torrent->seeders;
        $res['incomplete'] = $torrent->leechers;
        $res['peers'] = $this->givePeers($peers, $compact, $no_peer_id, FILTER_FLAG_IPV4);
        $res['peers6'] = $this->givePeers($peers, $compact, $no_peer_id, FILTER_FLAG_IPV6);

        return response(Bencode::bencode($res))->withHeaders(['Content-Type' => 'text/plain']);
        // End Build Response For Bittorrent Client
    }

    /**
     * @param $peers
     * @param $compact
     * @param $no_peer_id
     * @param $filter_flag
     *
     * @return string
     */
    private function givePeers($peers, $compact, $no_peer_id, $filter_flag = FILTER_FLAG_IPV4)
    {
        if ($compact) {
            $pcomp = '';
            foreach ($peers as &$p) {
                if (isset($p['ip']) && isset($p['port']) && filter_var($p['ip'], FILTER_VALIDATE_IP, $filter_flag)) {
                    $pcomp .= inet_pton($p['ip']);
                    $pcomp .= pack('n', (int) $p['port']);
                }
            }

            return $pcomp;
        }
        if ($no_peer_id) {
            foreach ($peers as &$p) {
                unset($p['peer_id']);
            }

            return $peers;
        } else {
            return $peers;
        }

        return $peers;
    }
}
