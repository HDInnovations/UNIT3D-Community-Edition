<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
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

class AnnounceController extends Controller
{
    /**
     * Announce Code.
     *
     * @param Request $request
     * @param $passkey
     *
     * @return Bencode response for the torrent client
     * @throws \Exception
     */
    public function announce(Request $request, $passkey)
    {
        /*\DB::listen(function($sql) {
            \Log::info($sql->sql);
            \Log::info($sql->bindings);
            \Log::info($sql->time);
        });*/

        // Check Announce Request Method
        $method = $request->method();
        if (! $request->isMethod('get')) {
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
        if (! $request->has('info_hash')) {
            //info('Client Attempted To Connect To Announce Without A Infohash');
            return response(Bencode::bencode(['failure reason' => 'Missing info_hash']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Peerid Is Not Provided Return Error to Client
        if (! $request->has('peer_id')) {
            //info('Client Attempted To Connect To Announce Without A Peerid');
            return response(Bencode::bencode(['failure reason' => 'Missing peer_id']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Port Is Not Provided Return Error to Client
        if (! $request->has('port')) {
            //info('Client Attempted To Connect To Announce Without A Specified Port');
            return response(Bencode::bencode(['failure reason' => 'Missing port']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If "Left" Is Not Provided Return Error to Client
        if (! $request->has('left')) {
            //info('Client Attempted To Connect To Announce Without Supplying Any "Left" Information');
            return response(Bencode::bencode(['failure reason' => 'Missing left']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If "Upload" Is Not Provided Return Error to Client
        if (! $request->has('uploaded')) {
            //info('Client Attempted To Connect To Announce Without Supplying Any "Upload" Information');
            return response(Bencode::bencode(['failure reason' => 'Missing upload']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If "Download" Is Not Provided Return Error to Client
        if (! $request->has('downloaded')) {
            //info('Client Attempted To Connect To Announce Without Supplying Any "Download" Information');
            return response(Bencode::bencode(['failure reason' => 'Missing download']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Check Passkey Against Users Table
        $user = User::with(['group:is_immune,is_freeleech', 'history'])->where('passkey', '=', $passkey)->first();

        // If Passkey Doesn't Exist Return Error to Client
        if (! $user) {
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
        $compact = ($request->has('compact') && $request->input('compact') == 1) ? true : false;
        $key = $request->has('key') ? bin2hex($request->input('key')) : null;
        $corrupt = $request->has('corrupt') ? $request->input('corrupt') : null;
        $ipv6 = $request->has('ipv6') ? bin2hex($request->input('ipv6')) : null;
        $no_peer_id = ($request->has('no_peer_id') && $request->input('no_peer_id') == 1) ? true : false;

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
        if (! $compact) {
            //info('Client Attempted To Connect To Announce But Doesn't Support Compact');
            return response(Bencode::bencode(['failure reason' => "Your client doesn't support compact, please update your client"]))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Check Info Hash Against Torrents Table
        $torrent = Torrent::select(['id', 'status', 'free', 'doubleup', 'times_completed', 'seeders', 'leechers'])
            ->with('peers')
            ->withAnyStatus()
            ->where('info_hash', '=', $info_hash)
            ->first();

        // If Torrent Doesnt Exsist Return Error to Client
        if (! $torrent || $torrent->id < 0) {
            //info('Client Attempted To Connect To Announce But The Torrent Doesn't Exist Using Hash '  . $info_hash);
            return response(Bencode::bencode(['failure reason' => 'Torrent not found']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Torrent Is Pending Moderation Return Error to Client
        if ($torrent->status == 0) {
            //info('Client Attempted To Connect To Announce But The Torrent Is Pending Moderation');
            return response(Bencode::bencode(['failure reason' => 'Torrent is still pending moderation']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Torrent Is Rejected Return Error to Client
        if ($torrent->status == 2) {
            //info('Client Attempted To Connect To Announce But The Torrent Is Rejected');
            return response(Bencode::bencode(['failure reason' => 'Torrent has been rejected']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // If Torrent Is Postponed Return Error to Client
        if ($torrent->status == 2) {
            //info('Client Attempted To Connect To Announce But The Torrent Is Postponed');
            return response(Bencode::bencode(['failure reason' => 'Torrent has been postponed']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        $peers = $torrent->peers->where('info_hash', '=', $info_hash)->take(100)->toArray();

        // Pull Count On Users Peers Per Torrent
        $connections = $torrent->peers->where('user_id', '=', $user->id)->count();

        // If Users Peer Count On A Single Torrent Is Greater Than X Return Error to Client
        if ($connections > config('announce.rate_limit')) {
            //info('Client Attempted To Connect To Announce But Has Hit Rate Limits');
            return response(Bencode::bencode(['failure reason' => 'You have reached the rate limit']))->withHeaders(['Content-Type' => 'text/plain']);
        }

        // Get The Current Peer
        $client = $torrent->peers->where('md5_peer_id', $md5_peer_id)->where('user_id', '=', $user->id)->first();

        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;

        // Creates a new client if not existing
        if (! $client && $event == 'completed') {
            return response(Bencode::bencode(['failure reason' => 'Torrent is complete but no record found.']))->withHeaders(['Content-Type' => 'text/plain']);
        } elseif (! $client) {
            if ($uploaded > 0 || $downloaded > 0) {
                $ghost = true;
                $event = 'started';
            }
            $client = new Peer();
        }

        // Get history information
        $history = $user->history->where('info_hash', '=', $info_hash)->first();

        if (! $history) {
            $history = new History();
            $history->user_id = $user->id;
            $history->info_hash = $info_hash;
        }

        if ($ghost) {
            $uploaded = ($real_uploaded >= $history->client_uploaded) ? ($real_uploaded - $history->client_uploaded) : 0;
            $downloaded = ($real_downloaded >= $history->client_downloaded) ? ($real_downloaded - $history->client_downloaded) : 0;
        } else {
            $uploaded = ($real_uploaded >= $client->uploaded) ? ($real_uploaded - $client->uploaded) : 0;
            $downloaded = ($real_downloaded >= $client->downloaded) ? ($real_downloaded - $client->downloaded) : 0;
        }

        $old_update = $client->updated_at ? $client->updated_at->timestamp : Carbon::now()->timestamp;

        // Modification of upload and Download
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $freeleech_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();

        if (config('other.freeleech') == 1 || $torrent->free == 1 || $personal_freeleech || $user->group->is_freeleech == 1 || $freeleech_token) {
            $mod_downloaded = 0;
        } else {
            $mod_downloaded = $downloaded;
        }

        if (config('other.doubleup') == 1 || $torrent->doubleup == 1) {
            $mod_uploaded = $uploaded * 2;
        } else {
            $mod_uploaded = $uploaded;
        }

        if ($event == 'started') {
            // Set the torrent data
            $history->agent = $agent;
            $history->active = 1;
            $history->seeder = ($left == 0) ? true : false;
            $history->immune = ($user->group->is_immune == 1) ? true : false;
            $history->uploaded += 0;
            $history->actual_uploaded += 0;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += 0;
            $history->actual_downloaded += 0;
            $history->client_downloaded = $real_downloaded;
            $history->save();

            // Never to push stats to user on start event

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->info_hash = $info_hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $agent;
            $client->uploaded = $real_uploaded;
            $client->downloaded = $real_downloaded;
            $client->seeder = ($left == 0) ? true : false;
            $client->left = $left;
            $client->torrent_id = $torrent->id;
            $client->user_id = $user->id;
            //End Peer update

            $client->save();
        } elseif ($event == 'completed') {
            // Set the torrent data
            $history->agent = $agent;
            $history->active = 1;
            $history->seeder = ($left == 0) ? true : false;
            $history->immune = ($user->group->is_immune == 1) ? true : false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_downloaded;
            $history->completed_at = Carbon::now();
            $history->save();

            // User update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->info_hash = $info_hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $agent;
            $client->uploaded = $real_uploaded;
            $client->downloaded = $real_downloaded;
            $client->seeder = 1;
            $client->left = 0;
            $client->torrent_id = $torrent->id;
            $client->user_id = $user->id;
            $client->save();
            //End Peer update

            // Torrent completed update
            $torrent->times_completed++;

            // Seedtime allocation
            $new_update = $client->updated_at->timestamp;
            $diff = $new_update - $old_update;
            $history->seedtime += $diff;
            $history->save();
        } elseif ($event == 'stopped') {
            // Set the torrent data
            $history->agent = $agent;
            $history->active = 0;
            $history->seeder = ($left == 0) ? true : false;
            $history->immune = ($user->group->is_immune == 1) ? true : false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = 0;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = 0;
            $history->save();

            // User update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->info_hash = $info_hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $agent;
            $client->uploaded = $real_uploaded;
            $client->downloaded = $real_downloaded;
            $client->seeder = ($left == 0) ? true : false;
            $client->left = $left;
            $client->torrent_id = $torrent->id;
            $client->user_id = $user->id;
            //End Peer update

            $client->save();

            // Seedtime allocation
            if ($left == 0) {
                $new_update = $client->updated_at->timestamp;
                $diff = $new_update - $old_update;
                $history->seedtime += $diff;
                $history->save();
            }

            $client->delete();
        } else {
            // Set the torrent data
            $history->agent = $agent;
            $history->active = 1;
            $history->seeder = ($left == 0) ? true : false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_uploaded;
            $history->save();

            // User update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->info_hash = $info_hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $agent;
            $client->uploaded = $real_uploaded;
            $client->downloaded = $real_downloaded;
            $client->seeder = ($left == 0) ? true : false;
            $client->left = $left;
            $client->torrent_id = $torrent->id;
            $client->user_id = $user->id;
            //End Peer update

            $client->save();

            // Seedtime allocation
            if ($left == 0) {
                $new_update = $client->updated_at->timestamp;
                $diff = $new_update - $old_update;
                $history->seedtime += $diff;
                $history->save();
            }
        }

        $torrent->seeders = Peer::where('torrent_id', '=', $torrent->id)->where('left', '=', '0')->count();
        $torrent->leechers = Peer::where('torrent_id', '=', $torrent->id)->where('left', '>', '0')->count();
        $torrent->save();

        $res = [];
        $min = 2400; // 40 Minutes
        $max = 3600; // 60 Minutes
        $res['interval'] = rand($min, $max);
        $res['min interval'] = 1800; // 30 Minutes
        $res['tracker_id'] = $md5_peer_id; // A string that the client should send back on its next announcements.
        $res['complete'] = $torrent->seeders;
        $res['incomplete'] = $torrent->leechers;
        $res['peers'] = $this->givePeers($peers, $compact, $no_peer_id);
        $res['peers6'] = $this->givePeers6($peers, $compact, $no_peer_id);

        return response(Bencode::bencode($res))->withHeaders(['Content-Type' => 'text/plain']);
    }

    /**
     * @param $peers
     * @param $compact
     * @param $no_peer_id
     * @return string
     */
    private function givePeers($peers, $compact, $no_peer_id)
    {
        if ($compact) {
            $pcomp = '';
            foreach ($peers as &$p) {
                if (isset($p['ip']) && isset($p['port'])) {
                    if (filter_var($p['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $pcomp .= inet_pton($p['ip']);
                        $pcomp .= pack('n', (int) $p['port']);
                    }
                }
            }

            return $pcomp;
        } elseif ($no_peer_id) {
            foreach ($peers as &$p) {
                unset($p['peer_id']);
            }

            return $peers;
        } else {
            return $peers;
        }

        return $peers;
    }

    /**
     * @param $peers
     * @param $compact
     * @param $no_peer_id
     * @return string
     */
    private function givePeers6($peers, $compact, $no_peer_id)
    {
        if ($compact) {
            $pcomp = '';
            foreach ($peers as &$p) {
                if (isset($p['ip']) && isset($p['port'])) {
                    if (filter_var($p['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                        $pcomp .= inet_pton($p['ip']);
                        $pcomp .= pack('n', (int) $p['port']);
                    }
                }
            }

            return $pcomp;
        } elseif ($no_peer_id) {
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
