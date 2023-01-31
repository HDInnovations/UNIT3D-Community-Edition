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
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Http\Resources\TorrentResource;
use App\Http\Resources\TorrentsResource;
use App\Models\Category;
use App\Models\FeaturedTorrent;
use App\Models\Keyword;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Services\Tmdb\TMDBScraper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentController extends BaseController
{
    public int $perPage = 25;

    public string $sortField = 'bumped_at';

    public string $sortDirection = 'desc';

    /**
     * TorrentController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): TorrentsResource
    {
        return new TorrentsResource(Torrent::with(['category', 'type', 'resolution'])
            ->latest('sticky')
            ->latest('bumped_at')
            ->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $requestFile = $request->file('torrent');
        if (! $request->hasFile('torrent')) {
            return $this->sendError('Validation Error.', 'You Must Provide A Torrent File For Upload!');
        }

        if ($requestFile->getError() !== 0 || $requestFile->getClientOriginalExtension() !== 'torrent') {
            return $this->sendError('Validation Error.', 'You Must Provide A Valid Torrent File For Upload!');
        }

        // Deplace and decode the torrent temporarily
        $decodedTorrent = TorrentTools::normalizeTorrent($requestFile);
        $infohash = Bencode::get_infohash($decodedTorrent);

        try {
            $meta = Bencode::get_meta($decodedTorrent);
        } catch (\Exception) {
            return $this->sendError('Validation Error.', 'You Must Provide A Valid Torrent File For Upload!');
        }

        foreach (TorrentTools::getFilenameArray($decodedTorrent) as $name) {
            if (! TorrentTools::isValidFilename($name)) {
                return $this->sendError('Validation Error.', 'Invalid Filenames In Torrent Files!');
            }
        }

        $fileName = \sprintf('%s.torrent', \uniqid('', true)); // Generate a unique name
        Storage::disk('torrents')->put($fileName, Bencode::bencode($decodedTorrent));

        // Find the right category
        $category = Category::withCount('torrents')->findOrFail($request->input('category_id'));

        // Create the torrent (DB)
        $torrent = \app()->make(Torrent::class);
        $torrent->name = $request->input('name');
        $torrent->description = $request->input('description');
        $torrent->mediainfo = TorrentTools::anonymizeMediainfo($request->input('mediainfo'));
        $torrent->bdinfo = $request->input('bdinfo');
        $torrent->info_hash = $infohash;
        $torrent->file_name = $fileName;
        $torrent->num_file = $meta['count'];
        $torrent->announce = $decodedTorrent['announce'];
        $torrent->size = $meta['size'];
        $torrent->nfo = ($request->hasFile('nfo')) ? TorrentTools::getNfo($request->file('nfo')) : '';
        $torrent->category_id = $category->id;
        $torrent->type_id = $request->input('type_id');
        $torrent->resolution_id = $request->input('resolution_id');
        $torrent->region_id = $request->input('region_id');
        $torrent->distributor_id = $request->input('distributor_id');
        $torrent->user_id = $user->id;
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->season_number = $request->input('season_number');
        $torrent->episode_number = $request->input('episode_number');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->personal_release = $request->input('personal_release') ?? 0;
        $torrent->internal = $user->group->is_modo || $user->group->is_internal ? $request->input('internal') : 0;
        $torrent->featured = $user->group->is_modo || $user->group->is_internal ? $request->input('featured') : 0;
        $torrent->doubleup = $user->group->is_modo || $user->group->is_internal ? $request->input('doubleup') : 0;
        $du_until = $request->input('du_until');
        if (($user->group->is_modo || $user->group->is_internal) && isset($du_until)) {
            $torrent->du_until = Carbon::now()->addDays($request->input('du_until'));
        }
        $torrent->free = $user->group->is_modo || $user->group->is_internal ? $request->input('free') : 0;
        $fl_until = $request->input('fl_until');
        if (($user->group->is_modo || $user->group->is_internal) && isset($fl_until)) {
            $torrent->fl_until = Carbon::now()->addDays($request->input('fl_until'));
        }
        $torrent->sticky = $user->group->is_modo || $user->group->is_internal ? $request->input('sticky') : 0;
        $torrent->moderated_at = Carbon::now();
        $torrent->moderated_by = User::where('username', 'System')->first()->id; //System ID

        // Set freeleech and doubleup if featured
        if ($torrent->featured == 1) {
            $torrent->free = '100';
            $torrent->doubleup = '1';
        }

        $resolutionRule = 'nullable|exists:resolutions,id';
        if ($category->movie_meta || $category->tv_meta) {
            $resolutionRule = 'required|exists:resolutions,id';
        }

        $episodeRule = 'nullable|numeric';
        if ($category->tv_meta) {
            $episodeRule = 'required|numeric';
        }

        $seasonRule = 'nullable|numeric';
        if ($category->tv_meta) {
            $seasonRule = 'required|numeric';
        }

        // Validation
        $v = \validator($torrent->toArray(), [
            'name'              => 'required|unique:torrents',
            'description'       => 'required',
            'info_hash'         => 'required|unique:torrents',
            'file_name'         => 'required',
            'num_file'          => 'required|numeric',
            'announce'          => 'required',
            'size'              => 'required',
            'category_id'       => 'required|exists:categories,id',
            'type_id'           => 'required|exists:types,id',
            'resolution_id'     => $resolutionRule,
            'region_id'         => 'nullable|exists:regions,id',
            'distributor_id'    => 'nullable|exists:distributors,id',
            'user_id'           => 'required|exists:users,id',
            'imdb'              => 'required|numeric',
            'tvdb'              => 'required|numeric',
            'tmdb'              => 'required|numeric',
            'mal'               => 'required|numeric',
            'igdb'              => 'required|numeric',
            'season_number'     => $seasonRule,
            'episode_number'    => $episodeRule,
            'anon'              => 'required',
            'stream'            => 'required',
            'sd'                => 'required',
            'personal_release'  => 'nullable',
            'internal'          => 'required',
            'featured'          => 'required',
            'free'              => 'required|between:0,100',
            'doubleup'          => 'required',
            'sticky'            => 'required',
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
        foreach (TorrentTools::getTorrentFiles($decodedTorrent) as $file) {
            $torrentFile = new TorrentFile();
            $torrentFile->name = $file['name'];
            $torrentFile->size = $file['size'];
            $torrentFile->torrent_id = $torrent->id;
            $torrentFile->save();
            unset($torrentFile);
        }

        $tmdbScraper = new TMDBScraper();
        if ($torrent->category->tv_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->tv($torrent->tmdb);
        }

        if ($torrent->category->movie_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->movie($torrent->tmdb);
        }

        // Torrent Keywords System
        foreach (TorrentTools::parseKeywords($request->input('keywords')) as $keyword) {
            $tag = new Keyword();
            $tag->name = $keyword;
            $tag->torrent_id = $torrent->id;
            $tag->save();
        }

        // check for trusted user and update torrent
        if ($user->group->is_trusted) {
            $appurl = \config('app.url');
            $user = $torrent->user;
            $username = $user->username;
            $anon = $torrent->anon;
            $featured = $torrent->featured;
            $free = $torrent->free;
            $doubleup = $torrent->doubleup;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chatRepository->systemMessage(
                    \sprintf('User [url=%s/users/', $appurl).$username.']'.$username.\sprintf('[/url] has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now! :slight_smile:'
                );
            } else {
                $this->chatRepository->systemMessage(
                    \sprintf('An anonymous user has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now! :slight_smile:'
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

            if ($free >= 1 && $featured == 0) {
                if ($torrent->fl_until === null) {
                    $this->chatRepository->systemMessage(
                        \sprintf(
                            'Ladies and Gents, [url=%s/torrents/',
                            $appurl
                        ).$torrent->id.']'.$torrent->name.'[/url] has been granted '.$free.'% FreeLeech! Grab It While You Can! :fire:'
                    );
                } else {
                    $this->chatRepository->systemMessage(
                        \sprintf(
                            'Ladies and Gents, [url=%s/torrents/',
                            $appurl
                        ).$torrent->id.']'.$torrent->name.'[/url] has been granted '.$free.'% FreeLeech for '.$request->input('fl_until').' days. :stopwatch:'
                    );
                }
            }

            if ($doubleup == 1 && $featured == 0) {
                if ($torrent->du_until === null) {
                    $this->chatRepository->systemMessage(
                        \sprintf(
                            'Ladies and Gents, [url=%s/torrents/',
                            $appurl
                        ).$torrent->id.']'.$torrent->name.'[/url] has been granted Double Upload! Grab It While You Can! :fire:'
                    );
                } else {
                    $this->chatRepository->systemMessage(
                        \sprintf(
                            'Ladies and Gents, [url=%s/torrents/',
                            $appurl
                        ).$torrent->id.']'.$torrent->name.'[/url] has been granted Double Upload for '.$request->input('du_until').' days. :stopwatch:'
                    );
                }
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return $this->sendResponse(\route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => \auth('api')->user()->rsskey]), 'Torrent uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): TorrentResource
    {
        $torrent = Torrent::findOrFail($id);

        TorrentResource::withoutWrapping();

        return new TorrentResource($torrent);
    }

    /**
     * Uses Input's To Put Together A Search.
     */
    public function filter(Request $request): TorrentsResource|\Illuminate\Http\JsonResponse
    {
        $user = \auth()->user();
        $isRegexAllowed = $user->group->is_modo;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen($field) > 2
            && $field[0] === '/'
            && $field[-1] === '/'
            && @\preg_match($field, 'Validate regex') !== false;

        $torrents = Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->withCount(['thanks', 'comments'])
            ->when($request->filled('name'), fn ($query) => $query->ofName($request->name, $isRegex($request->name)))
            ->when($request->filled('description'), fn ($query) => $query->ofDescription($request->description, $isRegex($request->description)))
            ->when($request->filled('mediainfo'), fn ($query) => $query->ofMediainfo($request->mediainfo, $isRegex($request->mediainfo)))
            ->when($request->filled('uploader'), fn ($query) => $query->ofUploader($request->uploader))
            ->when($request->filled('keywords'), fn ($query) => $query->ofKeyword(\array_map('trim', explode(',', $request->keywords))))
            ->when($request->filled('startYear'), fn ($query) => $query->releasedAfterOrIn((int) $request->startYear))
            ->when($request->filled('endYear'), fn ($query) => $query->releasedBeforeOrIn((int) $request->endYear))
            ->when($request->filled('categories'), fn ($query) => $query->ofCategory($request->categories))
            ->when($request->filled('types'), fn ($query) => $query->ofType($request->types))
            ->when($request->filled('resolutions'), fn ($query) => $query->ofResolution($request->resolutions))
            ->when($request->filled('genres'), fn ($query) => $query->ofGenre($request->genres))
            ->when($request->filled('tmdbId'), fn ($query) => $query->ofTmdb((int) $request->tmdbId))
            ->when($request->filled('imdbId'), fn ($query) => $query->ofImdb((int) $request->imdbId))
            ->when($request->filled('tvdbId'), fn ($query) => $query->ofTvdb((int) $request->tvdbId))
            ->when($request->filled('malId'), fn ($query) => $query->ofMal((int) $request->malId))
            ->when($request->filled('playlistId'), fn ($query) => $query->ofPlaylist((int) $request->playlistId))
            ->when($request->filled('collectionId'), fn ($query) => $query->ofCollection((int) $request->collectionId))
            ->when($request->filled('free'), fn ($query) => $query->ofFreeleech($request->free))
            ->when($request->filled('doubleup'), fn ($query) => $query->doubleup())
            ->when($request->filled('featured'), fn ($query) => $query->featured())
            ->when($request->filled('stream'), fn ($query) => $query->streamOptimized())
            ->when($request->filled('sd'), fn ($query) => $query->sd())
            ->when($request->filled('highspeed'), fn ($query) => $query->highspeed())
            ->when($request->filled('internal'), fn ($query) => $query->internal())
            ->when($request->filled('personalRelease'), fn ($query) => $query->personalRelease())
            ->when($request->filled('alive'), fn ($query) => $query->alive())
            ->when($request->filled('dying'), fn ($query) => $query->dying())
            ->when($request->filled('dead'), fn ($query) => $query->dead())
            ->when($request->filled('file_name'), fn ($query) => $query->ofFilename($request->file_name))
            ->when($request->filled('seasonNumber'), fn ($query) => $query->ofSeason((int) $request->seasonNumber))
            ->when($request->filled('episodeNumber'), fn ($query) => $query->ofEpisode((int) $request->episodeNumber))
            ->latest('sticky')
            ->orderBy($request->input('sortField') ?? $this->sortField, $request->input('sortDirection') ?? $this->sortDirection)
            ->paginate($request->input('perPage') ?? $this->perPage);

        if ($torrents !== null) {
            return new TorrentsResource($torrents);
        }

        return $this->sendResponse('404', 'No Torrents Found');
    }
}
