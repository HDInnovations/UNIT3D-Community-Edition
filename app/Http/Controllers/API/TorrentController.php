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
 * @author     HDVinnie
 */

namespace App\Http\Controllers\API;

use App\Helpers\Bencode;
use App\Helpers\MediaInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Http\Resources\TorrentResource;
use App\Http\Resources\TorrentsResource;
use App\Models\Category;
use App\Models\TagTorrent;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\User;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TorrentController extends BaseController
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * RequestController Constructor.
     *
     * @param ChatRepository           $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Display a listing of the resource.
     *
     * @return TorrentsResource
     */
    public function index()
    {
        return new TorrentsResource(Torrent::with(['category', 'tags'])->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Torrent $torrent)
    {
        $user = $request->user();
        $requestFile = $request->file('torrent');

        if ($request->hasFile('torrent') == false) {
            return $this->sendError('Validation Error.', 'You Must Provide A Torrent File For Upload!');
        } elseif ($requestFile->getError() != 0 && $requestFile->getClientOriginalExtension() != 'torrent') {
            return $this->sendError('Validation Error.', 'You Must Provide A Valid Torrent File For Upload!');
        }

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        // Deplace and decode the torrent temporarily
        $decodedTorrent = TorrentTools::normalizeTorrent($requestFile);
        $infohash = Bencode::get_infohash($decodedTorrent);
        $meta = Bencode::get_meta($decodedTorrent);
        $fileName = uniqid().'.torrent'; // Generate a unique name
        file_put_contents(getcwd().'/files/torrents/'.$fileName, Bencode::bencode($decodedTorrent));

        // Find the right category
        $category = Category::withCount('torrents')->findOrFail($request->input('category_id'));

        // Create the torrent (DB)
        $torrent = new Torrent();
        $torrent->name = $request->input('name');
        $torrent->slug = Str::slug($torrent->name);
        $torrent->description = $request->input('description');
        $torrent->mediainfo = self::anonymizeMediainfo($request->input('mediainfo'));
        $torrent->info_hash = $infohash;
        $torrent->file_name = $fileName;
        $torrent->num_file = $meta['count'];
        $torrent->announce = $decodedTorrent['announce'];
        $torrent->size = $meta['size'];
        $torrent->nfo = ($request->hasFile('nfo')) ? TorrentTools::getNfo($request->file('nfo')) : '';
        $torrent->category_id = $category->id;
        $torrent->user_id = $user->id;
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->type = $request->input('type');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $request->input('internal');
        $torrent->moderated_at = Carbon::now();
        $torrent->moderated_by = 1; //System ID

        // Validation
        $v = validator($torrent->toArray(), [
            'name'        => 'required|unique:torrents',
            'slug'        => 'required',
            'description' => 'required',
            'info_hash'   => 'required|unique:torrents',
            'file_name'   => 'required',
            'num_file'    => 'required|numeric',
            'announce'    => 'required',
            'size'        => 'required',
            'category_id' => 'required',
            'user_id'     => 'required',
            'imdb'        => 'required|numeric',
            'tvdb'        => 'required|numeric',
            'tmdb'        => 'required|numeric',
            'mal'         => 'required|numeric',
            'igdb'        => 'required|numeric',
            'type'        => 'required',
            'anon'        => 'required',
            'stream'      => 'required',
            'sd'          => 'required',
        ]);

        if ($v->fails()) {
            if (file_exists(getcwd().'/files/torrents/'.$fileName)) {
                unlink(getcwd().'/files/torrents/'.$fileName);
            }

            return $this->sendError('Validation Error.', $v->errors());
        } else {
            // Save The Torrent
            $torrent->save();

            // Count and save the torrent number in this category
            $category->num_torrent = $category->torrents_count;
            $category->save();

            // Backup the files contained in the torrent
            $fileList = TorrentTools::getTorrentFiles($decodedTorrent);
            foreach ($fileList as $file) {
                $f = new TorrentFile();
                $f->name = $file['name'];
                $f->size = $file['size'];
                $f->torrent_id = $torrent->id;
                $f->save();
                unset($f);
            }

            $meta = null;

            // Torrent Tags System
            if ($torrent->category->tv_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
            }
            if ($torrent->category->movie_meta) {
                if ($torrent->tmdb && $torrent->tmdb != 0) {
                    $meta = $client->scrape('movie', null, $torrent->tmdb);
                } else {
                    $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
            }

            if (isset($meta) && $meta->genres) {
                foreach ($meta->genres as $genre) {
                    $tag = new TagTorrent();
                    $tag->torrent_id = $torrent->id;
                    $tag->tag_name = $genre;
                    $tag->save();
                }
            }

            // check for trusted user and update torrent
            if ($user->group->is_trusted) {
                $appurl = config('app.url');
                $user = $torrent->user;
                $user_id = $user->id;
                $username = $user->username;
                $anon = $torrent->anon;

                // Announce To Shoutbox
                if ($anon == 0) {
                    $this->chat->systemMessage(
                        "User [url={$appurl}/users/".$username.']'.$username."[/url] has uploaded [url={$appurl}/torrents/".$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                    );
                } else {
                    $this->chat->systemMessage(
                        "An anonymous user has uploaded [url={$appurl}/torrents/".$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                    );
                }

                TorrentHelper::approveHelper($torrent->id);
            }

            return $this->sendResponse(route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => auth('api')->user()->rsskey]), 'Torrent uploaded successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return TorrentResource
     */
    public function show($id)
    {
        $torrent = Torrent::findOrFail($id);

        TorrentResource::withoutWrapping();

        return new TorrentResource($torrent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Torrent  $torrent
     *
     * @return TorrentsResource
     */
    public function filter(Request $request, Torrent $torrent)
    {
        $search = $request->input('name');
        $description = $request->input('description');
        $uploader = $request->input('uploader');
        $imdb = $request->input('imdb');
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $igdb = $request->input('igdb');
        $start_year = $request->input('start_year');
        $end_year = $request->input('end_year');
        $categories = $request->input('categories');
        $types = $request->input('types');
        $genres = $request->input('genres');
        $freeleech = $request->input('freeleech');
        $doubleupload = $request->input('doubleupload');
        $featured = $request->input('featured');
        $stream = $request->input('stream');
        $highspeed = $request->input('highspeed');
        $sd = $request->input('sd');
        $internal = $request->input('internal');
        $alive = $request->input('alive');
        $dying = $request->input('dying');
        $dead = $request->input('dead');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }

        $usernames = explode(' ', $uploader);
        $uploader = null;
        foreach ($usernames as $username) {
            $uploader .= $username.'%';
        }

        $keywords = explode(' ', $description);
        $description = '';
        foreach ($keywords as $keyword) {
            $description .= '%'.$keyword.'%';
        }

        $torrent = $torrent->newQuery();

        if ($request->has('name') && $request->input('name') != null) {
            $torrent->where(function ($query) use ($search) {
                $query->where('torrents.name', 'like', $search);
            });
        }

        if ($request->has('description') && $request->input('description') != null) {
            $torrent->where(function ($query) use ($description) {
                $query->where('torrents.description', 'like', $description)->orWhere('mediainfo', 'like', $description);
            });
        }

        if ($request->has('uploader') && $request->input('uploader') != null) {
            $match = User::whereRaw('(username like ?)', [$uploader])->orderBy('username', 'ASC')->first();
            if (null === $match) {
                return ['result' => [], 'count' => 0];
            }
            $torrent->where('torrents.user_id', '=', $match->id)->where('anon', '=', 0);
        }

        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrent->where('torrents.imdb', '=', str_replace('tt', '', $imdb));
        }

        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrent->where('torrents.tvdb', '=', $tvdb);
        }

        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrent->where('torrents.tmdb', '=', $tmdb);
        }

        if ($request->has('mal') && $request->input('mal') != null) {
            $torrent->where('torrents.mal', '=', $mal);
        }

        if ($request->has('igdb') && $request->input('igdb') != null) {
            $torrent->where('torrents.igdb', '=', $igdb);
        }

        if ($request->has('start_year') && $request->has('end_year') && $request->input('start_year') != null && $request->input('end_year') != null) {
            $torrent->whereBetween('torrents.release_year', [$start_year, $end_year]);
        }

        if ($request->has('categories') && $request->input('categories') != null) {
            $torrent->whereIn('torrents.category_id', $categories);
        }

        if ($request->has('types') && $request->input('types') != null) {
            $torrent->whereIn('torrents.type', $types);
        }

        if ($request->has('genres') && $request->input('genres') != null) {
            $genreID = TagTorrent::select(['torrent_id'])->distinct()->whereIn('tag_name', $genres)->get();
            $torrent->whereIn('torrents.id', $genreID);
        }

        if ($request->has('freeleech') && $request->input('freeleech') != null) {
            $torrent->where('torrents.free', '=', $freeleech);
        }

        if ($request->has('doubleupload') && $request->input('doubleupload') != null) {
            $torrent->where('torrents.doubleup', '=', $doubleupload);
        }

        if ($request->has('featured') && $request->input('featured') != null) {
            $torrent->where('torrents.featured', '=', $featured);
        }

        if ($request->has('stream') && $request->input('stream') != null) {
            $torrent->where('torrents.stream', '=', $stream);
        }

        if ($request->has('highspeed') && $request->input('highspeed') != null) {
            $torrent->where('torrents.highspeed', '=', $highspeed);
        }

        if ($request->has('sd') && $request->input('sd') != null) {
            $torrent->where('torrents.sd', '=', $sd);
        }

        if ($request->has('internal') && $request->input('internal') != null) {
            $torrent->where('torrents.internal', '=', $internal);
        }

        if ($request->has('alive') && $request->input('alive') != null) {
            $torrent->where('torrents.seeders', '>=', $alive);
        }

        if ($request->has('dying') && $request->input('dying') != null) {
            $torrent->where('torrents.seeders', '=', $dying)->where('times_completed', '>=', 3);
        }

        if ($request->has('dead') && $request->input('dead') != null) {
            $torrent->where('torrents.seeders', '=', $dead);
        }

        if (! empty($torrent)) {
            return new TorrentsResource($torrent->paginate(25));
        } else {
            return $this->sendResponse('404', 'No Torrents Found');
        }
    }
    /**
     * Anonymize A Torrent Media Info.
     *
     * @param $mediainfo
     *
     * @return array
     */
    private static function anonymizeMediainfo($mediainfo)
    {
        if ($mediainfo === null) {
            return;
        }
        $complete_name_i = strpos($mediainfo, 'Complete name');
        if ($complete_name_i !== false) {
            $path_i = strpos($mediainfo, ': ', $complete_name_i);
            if ($path_i !== false) {
                $path_i += 2;
                $end_i = strpos($mediainfo, "\n", $path_i);
                $path = substr($mediainfo, $path_i, $end_i - $path_i);
                $new_path = MediaInfo::stripPath($path);

                return substr_replace($mediainfo, $new_path, $path_i, strlen($path));
            }
        }

        return $mediainfo;
    }
}
