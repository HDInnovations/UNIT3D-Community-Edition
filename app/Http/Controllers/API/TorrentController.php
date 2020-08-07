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

namespace App\Http\Controllers\API;

use App\Helpers\Bencode;
use App\Helpers\MediaInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Http\Resources\TorrentResource;
use App\Http\Resources\TorrentsResource;
use App\Models\Category;
use App\Models\FeaturedTorrent;
use App\Models\TagTorrent;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\User;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentController extends BaseController
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * RequestController Constructor.
     *
     * @param \App\Repositories\ChatRepository $chatRepository
     */
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return TorrentsResource
     */
    public function index()
    {
        return new TorrentsResource(Torrent::with(['category', 'type', 'tags'])->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $torrent
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Torrent $torrent)
    {
        $user = $request->user();
        $requestFile = $request->file('torrent');
        if (! $request->hasFile('torrent')) {
            return $this->sendError('Validation Error.', 'You Must Provide A Torrent File For Upload!');
        }

        if ($requestFile->getError() !== 0 && $requestFile->getClientOriginalExtension() !== 'torrent') {
            return $this->sendError('Validation Error.', 'You Must Provide A Valid Torrent File For Upload!');
        }

        $movieScrapper = new \App\Services\MovieScrapper(\config('api-keys.tmdb'), \config('api-keys.tvdb'), \config('api-keys.omdb'));
        // Deplace and decode the torrent temporarily
        $decodedTorrent = TorrentTools::normalizeTorrent($requestFile);
        $infohash = Bencode::get_infohash($decodedTorrent);

        try {
            $meta = Bencode::get_meta($decodedTorrent);
        } catch (\Exception $e) {
            return $this->sendError('Validation Error.', 'You Must Provide A Valid Torrent File For Upload!');
        }

        $fileName = \sprintf('%s.torrent', \uniqid()); // Generate a unique name
        Storage::disk('torrents')->put($fileName, Bencode::bencode($decodedTorrent));

        // Find the right category
        $category = Category::withCount('torrents')->findOrFail($request->input('category_id'));

        // Create the torrent (DB)
        $torrent = \app()->make(Torrent::class);
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
        $torrent->type_id = $request->input('type_id');
        $torrent->resolution_id = $request->input('resolution_id');
        $torrent->user_id = $user->id;
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $user->group->is_modo || $user->group->is_internal ? $request->input('internal') : 0;
        $torrent->featured = $user->group->is_modo || $user->group->is_internal ? $request->input('featured') : 0;
        $torrent->doubleup = $user->group->is_modo || $user->group->is_internal ? $request->input('doubleup') : 0;
        $torrent->free = $user->group->is_modo || $user->group->is_internal ? $request->input('free') : 0;
        $torrent->sticky = $user->group->is_modo || $user->group->is_internal ? $request->input('sticky') : 0;
        $torrent->moderated_at = Carbon::now();
        $torrent->moderated_by = User::where('username', 'System')->first()->id; //System ID

        // Set freeleech and doubleup if featured
        if ($torrent->featured == 1) {
            $torrent->free = '1';
            $torrent->doubleup = '1';
        }

        // Validation
        $v = \validator($torrent->toArray(), [
            'name'           => 'required|unique:torrents',
            'slug'           => 'required',
            'description'    => 'required',
            'info_hash'      => 'required|unique:torrents',
            'file_name'      => 'required',
            'num_file'       => 'required|numeric',
            'announce'       => 'required',
            'size'           => 'required',
            'category_id'    => 'required|exists:categories,id',
            'type_id'        => 'required|exists:types,id',
            'resolution_id'  => 'nullable|exists:resolutions,id',
            'user_id'        => 'required|exists:users,id',
            'imdb'           => 'required|numeric',
            'tvdb'           => 'required|numeric',
            'tmdb'           => 'required|numeric',
            'mal'            => 'required|numeric',
            'igdb'           => 'required|numeric',
            'anon'           => 'required',
            'stream'         => 'required',
            'sd'             => 'required',
            'internal'       => 'required',
            'featured'       => 'required',
            'free'           => 'required',
            'doubleup'       => 'required',
            'sticky'         => 'required',
        ]);

        if ($v->fails()) {
            if (Storage::disk('torrents')->exists($fileName)) {
                Storage::disk('torrents')->delete($fileName);
            }

            return $this->sendError('Validation Error.', $v->errors());
        }
        // Save The Torrent
        $torrent->save();
        // Set torrent to featured
        if ($torrent->featured == 1) {
            $featuredTorrent = new FeaturedTorrent();
            $featuredTorrent->user_id = $user->id;
            $featuredTorrent->torrent_id = $torrent->id;
            $featuredTorrent->save();
        }
        // Count and save the torrent number in this category
        $category->num_torrent = $category->torrents_count;
        $category->save();
        // Backup the files contained in the torrent
        $fileList = TorrentTools::getTorrentFiles($decodedTorrent);
        foreach ($fileList as $file) {
            $torrentFile = new TorrentFile();
            $torrentFile->name = $file['name'];
            $torrentFile->size = $file['size'];
            $torrentFile->torrent_id = $torrent->id;
            $torrentFile->save();
            unset($torrentFile);
        }
        $meta = null;
        // Torrent Tags System
        if ($torrent->category->tv_meta) {
            if ($torrent->tmdb && $torrent->tmdb != 0) {
                $meta = $movieScrapper->scrape('tv', null, $torrent->tmdb);
            } else {
                $meta = $movieScrapper->scrape('tv', 'tt'.$torrent->imdb);
            }
        }
        if ($torrent->category->movie_meta) {
            if ($torrent->tmdb && $torrent->tmdb != 0) {
                $meta = $movieScrapper->scrape('movie', null, $torrent->tmdb);
            } else {
                $meta = $movieScrapper->scrape('movie', 'tt'.$torrent->imdb);
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
            $appurl = \config('app.url');
            $user = $torrent->user;
            $user_id = $user->id;
            $username = $user->username;
            $anon = $torrent->anon;
            $featured = $torrent->featured;
            $free = $torrent->free;
            $doubleup = $torrent->doubleup;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chatRepository->systemMessage(
                    \sprintf('User [url=%s/users/', $appurl).$username.']'.$username.\sprintf('[/url] has uploaded [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            } else {
                $this->chatRepository->systemMessage(
                    \sprintf('An anonymous user has uploaded [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            }

            if ($anon == 1 && $featured == 1) {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] has been added to the Featured Torrents Slider by an anonymous user! Grab It While You Can! :fire:'
                );
            } elseif ($anon == 0 && $featured == 1) {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.\sprintf('[/url] has been added to the Featured Torrents Slider by [url=%s/users/', $appurl).$username.']'.$username.'[/url]! Grab It While You Can! :fire:'
                );
            }

            if ($free == 1 && $featured == 0) {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] has been granted 100%% FreeLeech! Grab It While You Can! :fire:'
                );
            }

            if ($doubleup == 1 && $featured == 0) {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] has been granted Double Upload! Grab It While You Can! :fire:'
                );
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return $this->sendResponse(\route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => \auth('api')->user()->rsskey]), 'Torrent uploaded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $torrent
     *
     * @return TorrentsResource
     */
    public function filter(Request $request, Torrent $torrent)
    {
        $search = $request->input('name');
        $description = $request->input('description');
        $size = $request->input('size');
        $info_hash = $request->input('info_hash');
        $file_name = $request->input('file_name');
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
        $resolutions = $request->input('resolutions');
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

        $terms = \explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%'.$term.'%';
        }

        $usernames = \explode(' ', $uploader);
        $uploader = null;
        foreach ($usernames as $username) {
            $uploader .= $username.'%';
        }

        $keywords = \explode(' ', $description);
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

        if ($request->has('size') && $request->input('size') != null) {
            $torrent->where('torrents.size', '=', $size);
        }

        if ($request->has('info_hash') && $request->input('info_hash') != null) {
            $torrent->where('torrents.info_hash', '=', $info_hash);
        }

        if ($request->has('file_name') && $request->input('file_name') != null) {
            $torrent = $torrent->whereHas('files', function ($q) use ($file_name) {
                $q->where('name', $file_name);
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
            $torrent->where('torrents.imdb', '=', \str_replace('tt', '', $imdb));
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
            $torrent->whereIn('torrents.type_id', $types);
        }

        if ($request->has('resolutions') && $request->input('resolutions') != null) {
            $torrent->whereIn('torrents.resolution_id', $resolutions);
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

        if ($request->has('reseed') && $request->input('reseed') != null) {
            $torrent->where('torrents.seeders', '=', 0)->where('torrents.leechers', '>=', 1);
        }

        if (! empty($torrent)) {
            return new TorrentsResource($torrent->paginate(25));
        }

        return $this->sendResponse('404', 'No Torrents Found');
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
        $complete_name_i = \strpos($mediainfo, 'Complete name');
        if ($complete_name_i !== false) {
            $path_i = \strpos($mediainfo, ': ', $complete_name_i);
            if ($path_i !== false) {
                $path_i += 2;
                $end_i = \strpos($mediainfo, "\n", $path_i);
                $path = \substr($mediainfo, $path_i, $end_i - $path_i);
                $new_path = MediaInfo::stripPath($path);

                return \substr_replace($mediainfo, $new_path, $path_i, \strlen($path));
            }
        }

        return $mediainfo;
    }
}
