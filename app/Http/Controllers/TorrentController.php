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

use App\Helpers\Bbcode;
use App\Helpers\Bencode;
use App\Helpers\Linkify;
use App\Helpers\MediaInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Models\Audit;
use App\Models\BonTransactions;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\FeaturedTorrent;
use App\Models\Graveyard;
use App\Models\History;
use App\Models\Keyword;
use App\Models\Movie;
use App\Models\Peer;
use App\Models\PlaylistTorrent;
use App\Models\PrivateMessage;
use App\Models\Region;
use App\Models\Resolution;
use App\Models\Subtitle;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\Tv;
use App\Models\Type;
use App\Models\Warning;
use App\Repositories\ChatRepository;
use App\Services\Tmdb\TMDBScraper;
use hdvinnie\LaravelJoyPixels\LaravelJoyPixels;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\PlatformLogo;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentController extends Controller
{
    /**
     * TorrentController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display a listing of the Torrent resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('torrent.torrents');
    }

    /**
     * Display The Torrent reasource.
     *
     * @throws \JsonException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     * @throws \ReflectionException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException
     */
    public function show(Request $request, int|string $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();

        $torrent = Torrent::withAnyStatus()->with(['user', 'comments', 'category', 'type', 'resolution', 'subtitles', 'playlists'])->findOrFail($id);
        $freeleechToken = \cache()->rememberForever(
            'freeleech_token:'.$user->id.':'.$torrent->id,
            fn () => $user->freeleechTokens()->where('torrent_id', '=', $torrent->id)->exists()
        );
        $personalFreeleech = \cache()->rememberForever(
            'personal_freeleech:'.$user->id,
            fn () => $user->personalFreeleeches()->exists()
        );
        $totalTips = BonTransactions::where('torrent_id', '=', $id)->sum('cost');
        $userTips = BonTransactions::where('torrent_id', '=', $id)->where('sender', '=', $user->id)->sum('cost');
        $lastSeedActivity = History::where('torrent_id', '=', $torrent->id)->where('seeder', '=', 1)->latest('updated_at')->first();
        $audits = Audit::with('user')->where('model_entry_id', '=', $torrent->id)->where('model_name', '=', 'Torrent')->latest()->get();

        $meta = null;
        $trailer = null;
        $platforms = null;
        if ($torrent->category->tv_meta && $torrent->tmdb && $torrent->tmdb != 0) {
            $meta = Tv::with('genres', 'cast', 'companies', 'networks', 'recommendations')->where('id', '=', $torrent->tmdb)->first();
            $trailer = ( new \App\Services\Tmdb\Client\TV($torrent->tmdb))->get_trailer();
        }

        if ($torrent->category->movie_meta && $torrent->tmdb && $torrent->tmdb != 0) {
            $meta = Movie::with('genres', 'cast', 'companies', 'collection', 'recommendations')->where('id', '=', $torrent->tmdb)->first();
            $trailer = ( new \App\Services\Tmdb\Client\Movie($torrent->tmdb))->get_trailer();
        }

        if ($torrent->category->game_meta && ($torrent->igdb || $torrent->igdb != 0)) {
            $meta = Game::with([
                'cover'    => ['url', 'image_id'],
                'artworks' => ['url', 'image_id'],
                'genres'   => ['name'],
                'videos'   => ['video_id', 'name'],
                'involved_companies.company',
                'involved_companies.company.logo',
                'platforms', ])
                ->find($torrent->igdb);
            $link = collect($meta->videos)->take(1)->pluck('video_id');
            $trailer = isset($link[0]) ? 'https://www.youtube.com/embed/'.$link[0] : '/img/no-video.png';
            $platforms = PlatformLogo::whereIn('id', collect($meta->platforms)->pluck('platform_logo')->toArray())->get();
        }

        $featured = $torrent->featured == 1 ? FeaturedTorrent::where('torrent_id', '=', $id)->first() : null;

        $mediaInfo = null;
        if ($torrent->mediainfo !== null) {
            $mediaInfo = (new MediaInfo())->parse($torrent->mediainfo);
        }

        $playlists = $user->playlists;

        return \view('torrent.torrent', [
            'torrent'            => $torrent,
            'user'               => $user,
            'personal_freeleech' => $personalFreeleech,
            'freeleech_token'    => $freeleechToken,
            'meta'               => $meta,
            'trailer'            => $trailer,
            'platforms'          => $platforms,
            'total_tips'         => $totalTips,
            'user_tips'          => $userTips,
            'featured'           => $featured,
            'mediaInfo'          => $mediaInfo,
            'last_seed_activity' => $lastSeedActivity,
            'playlists'          => $playlists,
            'audits'             => $audits,
        ]);
    }

    /**
     * Show the form for editing the specified Torrent resource.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $categories = Category::all()
            ->sortBy('position')
            ->mapWithKeys(fn ($cat) => [
                $cat['id'] => [
                    'name' => $cat['name'],
                    'type' => match (1) {
                        $cat->movie_meta => 'movie',
                        $cat->tv_meta => 'tv',
                        $cat->game_meta => 'game',
                        $cat->music_meta => 'music',
                        $cat->no_meta => 'no'
                    },
                ]
            ]);
        $types = Type::all()->sortBy('position')->mapWithKeys(fn ($type) => [$type['id'] => ['name' => $type['name']]]);

        \abort_unless($user->group->is_modo || $user->id === $torrent->user_id, 403);

        return \view('torrent.edit_torrent', [
            'categories'   => $categories,
            'types'        => $types,
            'resolutions'  => Resolution::all()->sortBy('position'),
            'regions'      => Region::all()->sortBy('position'),
            'distributors' => Distributor::all()->sortBy('position'),
            'keywords'     => Keyword::where('torrent_id', '=', $torrent->id)->pluck('name'),
            'torrent'      => $torrent,
            'user'         => $user,
        ]);
    }

    /**
     * Update the specified Torrent resource in storage.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        \abort_unless($user->group->is_modo || $user->id === $torrent->user_id, 403);
        $torrent->name = $request->input('name');
        $torrent->description = $request->input('description');
        $torrent->category_id = $request->input('category_id');
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->season_number = $request->input('season_number');
        $torrent->episode_number = $request->input('episode_number');
        $torrent->type_id = $request->input('type_id');
        $torrent->resolution_id = $request->input('resolution_id');
        $torrent->region_id = $request->input('region_id');
        $torrent->distributor_id = $request->input('distributor_id');
        $torrent->mediainfo = $request->input('mediainfo');
        $torrent->bdinfo = $request->input('bdinfo');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $request->input('internal');
        $torrent->personal_release = $request->input('personal_release');

        $category = Category::findOrFail($request->input('category_id'));

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

        $v = \validator($torrent->toArray(), [
            'name'           => 'required',
            'description'    => 'required',
            'category_id'    => 'required|exists:categories,id',
            'type_id'        => 'required|exists:types,id',
            'resolution_id'  => $resolutionRule,
            'region_id'      => 'nullable|exists:regions,id',
            'distributor_id' => 'nullable|exists:distributors,id',
            'imdb'           => 'required|numeric',
            'tvdb'           => 'required|numeric',
            'tmdb'           => 'required|numeric',
            'mal'            => 'required|numeric',
            'igdb'           => 'required|numeric',
            'season_number'  => $seasonRule,
            'episode_number' => $episodeRule,
            'anon'           => 'required',
            'stream'         => 'required',
            'sd'             => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('torrent', ['id' => $torrent->id])
                ->withErrors($v->errors());
        }

        $torrent->save();

        // Cover Image for No-Meta Torrents
        if ($request->hasFile('torrent-cover')) {
            $image_cover = $request->file('torrent-cover');
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(400, 600)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner')) {
            $image_cover = $request->file('torrent-banner');
            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // Torrent Keywords System
        Keyword::where('torrent_id', '=', $torrent->id)->delete();

        foreach (TorrentTools::parseKeywords($request->input('keywords')) as $keyword) {
            Keyword::upsert(['torrent_id' => $torrent->id, 'name' => $keyword], ['torrent_id' => 'name'], ['name']);
        }

        // TMDB Meta
        $tmdbScraper = new TMDBScraper();
        if ($torrent->category->tv_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->tv($torrent->tmdb);
        }

        if ($torrent->category->movie_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->movie($torrent->tmdb);
        }

        return \to_route('torrent', ['id' => $torrent->id])
            ->withSuccess('Successfully Edited!');
    }

    /**
     * Delete A Torrent.
     *
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $v = \validator($request->all(), [
            'id'      => 'required|exists:torrents',
            'message' => 'required|alpha_dash|min:1',
        ]);

        if ($v) {
            $user = $request->user();
            $id = $request->id;
            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            if ($user->group->is_modo || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()))) {
                foreach (History::where('torrent_id', '=', $torrent->id)->get() as $pm) {
                    $pmuser = new PrivateMessage();
                    $pmuser->sender_id = 1;
                    $pmuser->receiver_id = $pm->user_id;
                    $pmuser->subject = \sprintf('Torrent Deleted! - %s', $torrent->name);
                    $pmuser->message = \sprintf('[b]Attention:[/b] Torrent %s has been removed from our site. Our system shows that you were either the uploader, a seeder or a leecher on said torrent. We just wanted to let you know you can safely remove it from your client.
                                        [b]Removal Reason:[/b] %s
                                        [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]', $torrent->name, $request->message);
                    $pmuser->save();
                }

                // Reset Requests
                $torrent->requests()->update([
                    'filled_by'     => null,
                    'filled_when'   => null,
                    'torrent_id'    => null,
                    'approved_by'   => null,
                    'approved_when' => null,
                ]);

                //Remove Torrent related info
                \cache()->forget(\sprintf('torrent:%s', $torrent->info_hash));
                $torrent->comments()->delete();
                Peer::where('torrent_id', '=', $id)->delete();
                History::where('torrent_id', '=', $id)->delete();
                Warning::where('torrent', '=', $id)->delete();
                TorrentFile::where('torrent_id', '=', $id)->delete();
                PlaylistTorrent::where('torrent_id', '=', $id)->delete();
                Subtitle::where('torrent_id', '=', $id)->delete();
                Graveyard::where('torrent_id', '=', $id)->delete();

                $freeleechTokens = $torrent->freeleechTokens();

                foreach ($freeleechTokens as $freeleechToken) {
                    \cache()->forget('freeleech_token:'.$freeleechToken->user_id.':'.$torrent->id);
                }

                $freeleechTokens->delete();

                if ($torrent->featured == 1) {
                    FeaturedTorrent::where('torrent_id', '=', $id)->delete();
                }

                $torrent->delete();

                return \to_route('torrents')
                    ->withSuccess('Torrent Has Been Deleted!');
            }
        } else {
            $errors = '';
            foreach ($v->errors()->all() as $error) {
                $errors .= $error."\n";
            }

            Log::notice(\sprintf('Deletion of torrent failed due to: %s', $errors));

            return \to_route('home.index')
                ->withErrors('Unable to delete Torrent');
        }
    }

    /**
     * Torrent Upload Form.
     */
    public function create(Request $request, int $categoryId = 0, string $title = '', string $imdb = '0', string $tmdb = '0'): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $categories = [];
        foreach (Category::all()->sortBy('position') as $cat) {
            $temp = [
                'name' => $cat->name,
            ];
            $temp['type'] = match (1) {
                $cat->movie_meta => 'movie',
                $cat->tv_meta    => 'tv',
                $cat->game_meta  => 'game',
                $cat->music_meta => 'music',
                $cat->no_meta    => 'no',
                default          => 'no',
            };
            $categories[(int) $cat->id] = $temp;
        }

        return \view('torrent.upload', [
            'categories'   => $categories,
            'types'        => Type::all()->sortBy('position'),
            'resolutions'  => Resolution::all()->sortBy('position'),
            'regions'      => Region::all()->sortBy('position'),
            'distributors' => Distributor::all()->sortBy('position'),
            'user'         => $user,
            'category_id'  => $categoryId,
            'title'        => $title,
            'imdb'         => \str_replace('tt', '', $imdb),
            'tmdb'         => $tmdb,
        ]);
    }

    /**
     * Preview torrent description.
     */
    public function preview(Request $request): \Illuminate\Http\JsonResponse
    {
        // Preview The Upload
        $joyPixel = \app()->make(LaravelJoyPixels::class);
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        $previewContent = $joyPixel->toImage(
            $linkify->linky(
                $bbcode->parse($request->input('description'), true)
            )
        );

        return \response()->json($previewContent);
    }

    /**
     * Upload A Torrent.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        // Find the right category
        $category = Category::withCount('torrents')->findOrFail($request->input('category_id'));

        $requestFile = $request->file('torrent');
        if (! $request->hasFile('torrent')) {
            return \to_route('upload_form', ['category_id' => $category->id])
                ->withErrors('You Must Provide A Torrent File For Upload!')->withInput();
        }

        if ($requestFile->getError() != 0 || $requestFile->getClientOriginalExtension() != 'torrent') {
            return \to_route('upload_form', ['category_id' => $category->id])
                ->withErrors('Supplied Torrent File Is Corrupt!')->withInput();
        }

        // Deplace and decode the torrent temporarily
        $decodedTorrent = TorrentTools::normalizeTorrent($requestFile);
        $infohash = Bencode::get_infohash($decodedTorrent);

        $v2 = Bencode::is_v2_or_hybrid($decodedTorrent);
        if ($v2) {
            return \to_route('upload_form', ['category_id' => $category->id])
                ->withErrors('BitTorrent v2 (BEP 52) is not supported!')->withInput();
        }

        try {
            $meta = Bencode::get_meta($decodedTorrent);
        } catch (\Exception) {
            return \to_route('upload_form', ['category_id' => $category->id])
                ->withErrors('You Must Provide A Valid Torrent File For Upload!')->withInput();
        }

        foreach (TorrentTools::getFilenameArray($decodedTorrent) as $name) {
            if (! TorrentTools::isValidFilename($name)) {
                return \to_route('upload_form', ['category_id' => $category->id])
                    ->withErrors('Invalid Filenames In Torrent Files!')->withInput();
            }
        }

        $fileName = \uniqid('', true).'.torrent'; // Generate a unique name
        \file_put_contents(\getcwd().'/files/torrents/'.$fileName, Bencode::bencode($decodedTorrent));

        // Create the torrent (DB)
        $torrent = new Torrent();
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
        $torrent->internal = $request->input('internal');
        $torrent->personal_release = $request->input('personal_release');
        $torrent->moderated_at = Carbon::now();
        $torrent->moderated_by = 1; //System ID
        $torrent->free = $user->group->is_modo || $user->group->is_internal ? $request->input('free') : 0;

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
            'name'           => 'required|unique:torrents',
            'description'    => 'required',
            'info_hash'      => 'required|unique:torrents',
            'file_name'      => 'required',
            'num_file'       => 'required|numeric',
            'announce'       => 'required',
            'size'           => 'required',
            'category_id'    => 'required|exists:categories,id',
            'type_id'        => 'required|exists:types,id',
            'resolution_id'  => $resolutionRule,
            'region_id'      => 'nullable|exists:regions,id',
            'distributor_id' => 'nullable|exists:distributors,id',
            'user_id'        => 'required|exists:users,id',
            'imdb'           => 'required|numeric',
            'tvdb'           => 'required|numeric',
            'tmdb'           => 'required|numeric',
            'mal'            => 'required|numeric',
            'igdb'           => 'required|numeric',
            'season_number'  => $seasonRule,
            'episode_number' => $episodeRule,
            'anon'           => 'required',
            'stream'         => 'required',
            'sd'             => 'required',
            'free'           => 'sometimes|between:0,100',
        ]);

        if ($v->fails()) {
            if (\file_exists(\getcwd().'/files/torrents/'.$fileName)) {
                \unlink(\getcwd().'/files/torrents/'.$fileName);
            }

            return \to_route('upload_form', ['category_id' => $category->id])
                ->withErrors($v->errors())->withInput();
        }

        // Save The Torrent
        $torrent->save();

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

        // TMDB Meta
        $tmdbScraper = new TMDBScraper();
        if ($torrent->category->tv_meta !== 0 && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->tv($torrent->tmdb);
        }

        if ($torrent->category->movie_meta !== 0 && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->movie($torrent->tmdb);
        }

        // Torrent Keywords System
        foreach (TorrentTools::parseKeywords($request->input('keywords')) as $keyword) {
            Keyword::upsert(['torrent_id' => $torrent->id, 'name' => $keyword], ['torrent_id' => 'name'], ['name']);
        }

        // Cover Image for No-Meta Torrents
        if ($request->hasFile('torrent-cover')) {
            $image_cover = $request->file('torrent-cover');
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(400, 600)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner')) {
            $image_cover = $request->file('torrent-banner');
            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // check for trusted user and update torrent
        if ($user->group->is_trusted && ! $request->mod_queue_opt_in) {
            $appurl = \config('app.url');
            $user = $torrent->user;
            $username = $user->username;
            $anon = $torrent->anon;

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

            if ($torrent->free >= 1) {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] has been granted '.$torrent->free.'% FreeLeech! Grab It While You Can! :fire:'
                );
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return \to_route('download_check', ['id' => $torrent->id])
            ->withSuccess('Your torrent file is ready to be downloaded and seeded!');
    }
}
