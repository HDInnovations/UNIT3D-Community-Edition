<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     Mr.G
 */

namespace App\Http\Controllers;

use App\History;
use App\Peer;
use App\Torrent;
use App\User;
use App\UserFreeleech;
use App\Group;

use Carbon\Carbon;

use App\Services\Bencode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class AnnounceController extends Controller
{

    /**
     * Announce code
     *
     * @access public
     * @param $Passkey User passkey
     * @return Bencoded response for the torrent client
     */
    public function announce(Request $request, $passkey)
    {
        // Browser safety check
        $this->BrowserCheck($request->server('HTTP_USER_AGENT'));

        // If Passkey Is Not Provided Exsist Return Error to Client
        if ($passkey == null) {
            return response(Bencode::bencode(['failure reason' => 'Please call Passkey']), 200, ['Content-Type' => 'text/plain']);
        }

        // If User Client Is Not Sending Required Data Return Error to Client
        if (!$request->has('info_hash') || !$request->has('peer_id') || !$request->has('port') || !$request->has('left') || !$request->has('uploaded') || !$request->has('downloaded')) {
            return response(Bencode::bencode(['failure reason' => 'Bad Data from client']), 200, ['Content-Type' => 'text/plain']);
        }

        // Check Passkey Against Users Table
        $user = User::where("passkey", '=', $passkey)->first();

        // If Passkey Doesnt Exsist Return Error to Client
        if (!$user) {
            return response(Bencode::bencode(['failure reason' => 'Passkey is invalid']), 200, ['Content-Type' => 'text/plain']);
        }

        // If User Is Banned Return Error to Client
        if ($user->group->id == 5) {
            return response(Bencode::bencode(['failure reason' => 'I think your no longer welcome here']), 200, ['Content-Type' => 'text/plain']);
        }

        // Standard Information Fields
        $event = $request->get('event');
        $hash = bin2hex($request->get('info_hash'));
        $peer_id = bin2hex($request->get('peer_id'));
        $md5_peer_id = md5($peer_id);
        $ip = $request->ip();
        $port = (int)$request->get('port');
        $left = (float)$request->get('left');
        $uploaded = (float)$request->get('uploaded');
        $real_uploaded = $uploaded;
        $downloaded = (float )$request->get('downloaded');
        $real_downloaded = $downloaded;

        //Extra Information Fields
        $tracker_id = $request->has('trackerid') ? bin2hex($request->get('tracker_id')) : null;
        $compact = ($request->has('compact') && $request->get('compact') == 1) ? true : false;
        $key = $request->has('key') ? bin2hex($request->get('key')) : null;
        $corrupt = $request->has('corrupt') ? $request->get('corrupt') : null;
        $ipv6 = $request->has('ipv6') ? bin2hex($request->get('ipv6')) : null;
        $no_peer_id = ($request->has('no_peer_id') && $request->get('no_peer_id') == 1) ? true : false;

        // If User Client Is Sending Negitive Values Return Error to Client
        if ($uploaded < 0 || $downloaded < 0 || $left < 0) {
            return response(Bencode::bencode(['failure reason' => 'Data from client is negative']), 200, ['Content-Type' => 'text/plain']);
        }

        // If User Client Does Not Support Compact Return Error to Client
        if (!$compact) {
            return response(Bencode::bencode(['failure reason' => "Your client doesn't support compact, please update your client"]), 200, ['Content-Type' => 'text/plain']);
        }

        // Check Info Hash Agaist Torrents Table
        $torrent = Torrent::where('info_hash', '=', $hash)->first();

        // If Info Hash Doesnt Exsist Return Error to Client
        if (!$torrent || $torrent->id < 0) {
            return response(Bencode::bencode(['failure reason' => 'Torrent not found']), 200, ['Content-Type' => 'text/plain']);
        }

        $peers = Peer::where('hash', '=', $hash)->take(100)->get()->toArray();
        $seeders = 0;
        $leechers = 0;

        foreach ($peers as &$p) {
            if ($p['left'] > 0) {
                $leechers++; // Counts the number of leechers
            } else {
                $seeders++; // Counts the number of seeders
            }

            unset($p['id'], $p['md5_peer_id'], $p['hash'], $p['agent'], $p['uploaded'], $p['downloaded'], $p['left'], $p['torrent_id'],
                $p['user_id'], $p['seeder'], $p['created_at'], $p['updated_at']);
        }

        // Pull Count On Users Peers Per Torrent
        $limit = Peer::where('hash', '=', $hash)->where('user_id', '=', $user->id)->count();

        // If Users Peer Count On A Single Torrent Is Greater Than 3 Return Error to Client
        if ($limit > 3) {
            return response(Bencode::bencode(['failure reason' => 'You have reached the rate limit']), 200, ['Content-Type' => 'text/plain']);
        }

        // Get The Current Peer
        $client = Peer::where('hash', '=', $hash)->where('md5_peer_id', '=', $md5_peer_id)->where('user_id', '=', $user->id)->first();

        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;

        // Creates a new client if not existing
        if (!$client && $event == 'completed') {
            return response(Bencode::bencode(['failure reason' => 'Torrent is complete but no record found.']), 200, ['Content-Type' => 'text/plain']);
        } elseif (!$client) {
            if ($uploaded > 0 || $downloaded > 0) {
                $ghost = true;
            }
            $client = new Peer();
        }

        // Get history information
        $history = History::where("info_hash", "=", $hash)->where("user_id", "=", $user->id)->first();

        if (!$history) {
            $history = new History([
                "user_id" => $user->id,
                "info_hash" => $hash
            ]);
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
        $personal_freeleech = UserFreeleech::where('user_id', '=', $user->id)->first();

        if (config('other.freeleech') == true || $torrent->free == 1 || $personal_freeleech || $user->group->is_freeleech == 1) {
            $mod_downloaded = 0;
        } else {
            $mod_downloaded = $downloaded;
        }

        if (config('other.doubleup') == true || $torrent->doubleup == 1) {
            $mod_uploaded = $uploaded * 2;
        } else {
            $mod_uploaded = $uploaded;
        }

        if ($event == 'started') {
            // Set the torrent data
            $history->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
            $history->active = true;
            $history->seeder = ($left == 0) ? true : false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_downloaded;
            $history->save();

            // user update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->hash = $hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
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
            $history->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
            $history->active = true;
            $history->seeder = ($left == 0) ? true : false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_downloaded;
            $history->completed_at = Carbon::now();
            $history->save();

            // user update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->hash = $hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
            $client->uploaded = $real_uploaded;
            $client->downloaded = $real_downloaded;
            $client->seeder = true;
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
            $history->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
            $history->active = false;
            $history->seeder = false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = 0;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = 0;
            $history->save();

            // user update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->hash = $hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
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
            $history->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
            $history->active = true;
            $history->seeder = ($left == 0) ? true : false;
            $history->uploaded += $mod_uploaded;
            $history->actual_uploaded += $uploaded;
            $history->client_uploaded = $real_uploaded;
            $history->downloaded += $mod_downloaded;
            $history->actual_downloaded += $downloaded;
            $history->client_downloaded = $real_uploaded;
            $history->save();

            // user update
            $user->uploaded += $mod_uploaded;
            $user->downloaded += $mod_downloaded;
            $user->save();
            // End User update

            //Peer update
            $client->peer_id = $peer_id;
            $client->md5_peer_id = $md5_peer_id;
            $client->hash = $hash;
            $client->ip = $request->ip();
            $client->port = $port;
            $client->agent = $request->server('HTTP_USER_AGENT') ?: "Unknown";
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

        $torrent->seeders = Peer::whereRaw('torrent_id = ? AND `left` = 0', [$torrent->id])->count();
        $torrent->leechers = Peer::whereRaw('torrent_id = ? AND `left` > 0', [$torrent->id])->count();
        $torrent->save();

        $res = array();
        $res['interval'] = (60 * 45); // Set to 45 for debug
        $res['min interval'] = (60 * 30); // Set to 30 for debug
        $res['tracker_id'] = $md5_peer_id; // A string that the client should send back on its next announcements.
        $res['complete'] = $seeders;
        $res['incomplete'] = $leechers;
        $res['peers'] = $this->givePeers($peers, $compact, $no_peer_id);

        return response(Bencode::bencode($res), 200, ['Content-Type' => 'text/plain']);
    }

    private function BrowserCheck($user_agent)
    {
        if (preg_match("/^Mozilla|^Opera|^Links|^Lynx/i", $user_agent)) {
            abort(500, "This application failed to load");
            die();
        }
    }

    private function givePeers($peers, $compact, $no_peer_id)
    {
        if ($compact) {
            $pcomp = "";
            foreach ($peers as &$p) {
                if (isset($p['ip']) && isset($p['port'])) {
                    $pcomp .= pack('Nn', ip2long($p['ip']), (int)$p['port']);
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
