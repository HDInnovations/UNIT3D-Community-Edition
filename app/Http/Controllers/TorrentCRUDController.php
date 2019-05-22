<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Services\MovieScrapper;
use Carbon\Carbon;
use App\Models\Peer;
use App\Models\History;
use App\Models\Torrent;
use App\Models\Warning;
use App\Helpers\MediaInfo;
use App\Models\TagTorrent;
use App\Models\TorrentFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FreeleechToken;
use App\Models\PrivateMessage;
use App\Models\TorrentRequest;
use App\Models\BonTransactions;
use App\Models\FeaturedTorrent;
use App\Models\PersonalFreeleech;
use Log;

/**
 * Class TorrentCRUDController
 * @package App\Http\Controllers
 */
class TorrentCRUDController extends Controller
{
    /**
     * Displays Torrent List View.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function torrents(Request $request)
    {
        $user = $request->user();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $torrents = Torrent::with(['user', 'category'])->withCount(['thanks', 'comments'])->orderBy('sticky', 'desc')->orderBy('created_at', 'desc')->paginate(25);
        $repository = $this->faceted;

        return view('torrent.torrents', [
            'personal_freeleech' => $personal_freeleech,
            'repository'         => $repository,
            'torrents'           => $torrents,
            'user'               => $user,
            'sorting'            => '',
            'direction'          => 1,
            'links'              => null,
        ]);
    }

    /**
     * Display The Torrent.
     *
     * @param Request $request
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     */
    public function torrent(Request $request, $slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->with('comments')->findOrFail($id);
        $uploader = $torrent->user;
        $user = $request->user();
        $freeleech_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $comments = $torrent->comments()->latest()->paginate(5);
        $total_tips = BonTransactions::where('torrent_id', '=', $id)->sum('cost');
        $user_tips = BonTransactions::where('torrent_id', '=', $id)->where('sender', '=', $request->user()->id)->sum('cost');
        $last_seed_activity = History::where('info_hash', '=', $torrent->info_hash)->where('seeder', '=', 1)->latest('updated_at')->first();

        $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        if ($torrent->category_id == 2) {
            if ($torrent->tmdb && $torrent->tmdb != 0) {
                $movie = $client->scrape('tv', null, $torrent->tmdb);
            } else {
                $movie = $client->scrape('tv', 'tt'.$torrent->imdb);
            }
        } else {
            if ($torrent->tmdb && $torrent->tmdb != 0) {
                $movie = $client->scrape('movie', null, $torrent->tmdb);
            } else {
                $movie = $client->scrape('movie', 'tt'.$torrent->imdb);
            }
        }

        if ($movie->recommendations) {
            $movie->recommendations['results'] = array_map(function ($recomentaion) {
                $recomentaion['exists'] = Torrent::where('tmdb', $recomentaion['id'])->get()->isNotEmpty();

                return $recomentaion;
            }, $movie->recommendations['results']);
        }

        if ($torrent->featured == 1) {
            $featured = FeaturedTorrent::where('torrent_id', '=', $id)->first();
        } else {
            $featured = null;
        }

        $general = null;
        $video = null;
        $settings = null;
        $audio = null;
        $general_crumbs = null;
        $text_crumbs = null;
        $subtitle = null;
        $view_crumbs = null;
        $video_crumbs = null;
        $settings = null;
        $audio_crumbs = null;
        $subtitle = null;
        $subtitle_crumbs = null;
        if ($torrent->mediainfo != null) {
            $parser = new MediaInfo();
            $parsed = $parser->parse($torrent->mediainfo);
            $view_crumbs = $parser->prepareViewCrumbs($parsed);
            $general = $parsed['general'];
            $general_crumbs = $view_crumbs['general'];
            $video = $parsed['video'];
            $video_crumbs = $view_crumbs['video'];
            $settings = ($parsed['video'] !== null && isset($parsed['video'][0]) && isset($parsed['video'][0]['encoding_settings'])) ? $parsed['video'][0]['encoding_settings'] : null;
            $audio = $parsed['audio'];
            $audio_crumbs = $view_crumbs['audio'];
            $subtitle = $parsed['text'];
            $text_crumbs = $view_crumbs['text'];
        }

        return view('torrent.torrent', [
            'torrent'            => $torrent,
            'comments'           => $comments,
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'freeleech_token'    => $freeleech_token,
            'movie'              => $movie,
            'total_tips'         => $total_tips,
            'user_tips'          => $user_tips,
            'client'             => $client,
            'featured'           => $featured,
            'general'            => $general,
            'general_crumbs'     => $general_crumbs,
            'video_crumbs'       => $video_crumbs,
            'audio_crumbs'       => $audio_crumbs,
            'text_crumbs'        => $text_crumbs,
            'video'              => $video,
            'audio'              => $audio,
            'subtitle'           => $subtitle,
            'settings'           => $settings,
            'uploader'           => $uploader,
            'last_seed_activity' => $last_seed_activity,
        ]);
    }

    /**
     * Edit A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     */
    public function edit(Request $request, $slug, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));

        abort_unless($user->group->is_modo || $user->id == $torrent->user_id, 403);
        $torrent->name = $request->input('name');
        $torrent->slug = Str::slug($torrent->name);
        $torrent->description = $request->input('description');
        $torrent->category_id = $request->input('category_id');
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->type = $request->input('type');
        $torrent->mediainfo = $request->input('mediainfo');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $request->input('internal');

        $v = validator($torrent->toArray(), [
            'name'        => 'required',
            'slug'        => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'imdb'        => 'required|numeric',
            'tvdb'        => 'required|numeric',
            'tmdb'        => 'required|numeric',
            'mal'         => 'required|numeric',
            'type'        => 'required',
            'anon'        => 'required',
            'stream'      => 'required',
            'sd'          => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->withErrors($v->errors());
        } else {
            $torrent->save();

            // Torrent Tags System
            if ($torrent->category_id == 2) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $movie = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $movie = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
            } else {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $movie = $client->scrape('movie', null, $torrent->tmdb);
                } else {
                    $movie = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
            }

            if ($movie->genres) {
                foreach ($movie->genres as $genre) {
                    $exist = TagTorrent::where('torrent_id', '=', $torrent->id)->where('tag_name', '=', $genre)->first();
                    if (! $exist) {
                        $tag = new TagTorrent();
                        $tag->torrent_id = $torrent->id;
                        $tag->tag_name = $genre;
                        $tag->save();
                    }
                }
            }

            if ($user->group->is_modo) {
                // Activity Log
                \LogActivity::addToLog("Staff Member {$user->username} has edited torrent, ID: {$torrent->id} NAME: {$torrent->name} .");
            } else {
                // Activity Log
                \LogActivity::addToLog("Member {$user->username} has edited torrent, ID: {$torrent->id} NAME: {$torrent->name} .");
            }

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->withSuccess('Successfully Edited!');
        }
    }

    /**
     * Delete A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $v = validator($request->all(), [
            'id'      => 'required|exists:torrents',
            'slug'    => 'required|exists:torrents',
            'message' => 'required|alpha_dash|min:1',
        ]);

        if ($v) {
            $user = $request->user();
            $id = $request->id;
            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            if ($user->group->is_modo || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()))) {
                $users = History::where('info_hash', '=', $torrent->info_hash)->get();
                foreach ($users as $pm) {
                    $pmuser = new PrivateMessage();
                    $pmuser->sender_id = 1;
                    $pmuser->receiver_id = $pm->user_id;
                    $pmuser->subject = 'Torrent Deleted!';
                    $pmuser->message = "[b]Attention:[/b] Torrent {$torrent->name} has been removed from our site. Our system shows that you were either the uploader, a seeder or a leecher on said torrent. We just wanted to let you know you can safley remove it from your client.
                                        [b]Removal Reason:[/b] {$request->message}
                                        [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]";
                    $pmuser->save();
                }

                if ($user->group->is_modo) {
                    // Activity Log
                    \LogActivity::addToLog("Staff Member {$user->username} has deleted torrent, ID: {$torrent->id} NAME: {$torrent->name} .");
                } else {
                    // Activity Log
                    \LogActivity::addToLog("Member {$user->username} has deleted torrent, ID: {$torrent->id} NAME: {$torrent->name} .");
                }

                // Reset Requests
                $torrentRequest = TorrentRequest::where('filled_hash', '=', $torrent->info_hash)->get();
                foreach ($torrentRequest as $req) {
                    if ($req) {
                        $req->filled_by = null;
                        $req->filled_when = null;
                        $req->filled_hash = null;
                        $req->approved_by = null;
                        $req->approved_when = null;
                        $req->save();
                    }
                }

                //Remove Torrent related info
                Peer::where('torrent_id', '=', $id)->delete();
                History::where('info_hash', '=', $torrent->info_hash)->delete();
                Warning::where('id', '=', $id)->delete();
                TorrentFile::where('torrent_id', '=', $id)->delete();
                if ($torrent->featured == 1) {
                    FeaturedTorrent::where('torrent_id', '=', $id)->delete();
                }
                Torrent::withAnyStatus()->where('id', '=', $id)->delete();

                return redirect()->to('/torrents')
                    ->withSuccess('Torrent Has Been Deleted!');
            }
        } else {
            $errors = '';
            foreach ($v->errors()->all() as $error) {
                $errors .= $error."\n";
            }
            Log::notice("Deletion of torrent failed due to: \n\n{$errors}");

            return redirect()->route('home')
                ->withErrors('Unable to delete Torrent');
        }
    }
}