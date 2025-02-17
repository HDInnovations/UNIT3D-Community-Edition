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
 */

namespace App\Http\Controllers;

use App\Helpers\Bencode;
use App\Helpers\MediaInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Http\Requests\StoreTorrentRequest;
use App\Http\Requests\UpdateTorrentRequest;
use App\Models\Audit;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\FeaturedTorrent;
use App\Models\History;
use App\Models\Keyword;
use App\Models\Movie;
use App\Models\Region;
use App\Models\Resolution;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\TorrentModerationMessage;
use App\Models\TorrentFile;
use App\Models\Tv;
use App\Models\Type;
use App\Models\User;
use App\Notifications\TorrentDeleted;
use App\Repositories\ChatRepository;
use App\Services\Tmdb\TMDBScraper;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\PlatformLogo;
use Exception;
use Illuminate\Support\Facades\Notification;
use ReflectionException;
use JsonException;

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
        return view('torrent.index');
    }

    /**
     * Display The Torrent resource.
     *
     * @throws JsonException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     * @throws ReflectionException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException
     */
    public function show(Request $request, int|string $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();

        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)
            ->with(['user', 'comments', 'category', 'type', 'resolution', 'subtitles', 'playlists', 'reports'])
            ->withCount([
                'bookmarks',
                'seeds'   => fn ($query) => $query->where('active', '=', true)->where('visible', '=', true),
                'leeches' => fn ($query) => $query->where('active', '=', true)->where('visible', '=', true),
            ])
            ->withExists([
                'bookmarks'       => fn ($query) => $query->where('user_id', '=', $user->id),
                'freeleechTokens' => fn ($query) => $query->where('user_id', '=', $user->id),
                'trump'
            ])
            ->findOrFail($id);

        $meta = null;
        $platforms = null;

        if ($torrent->category->tv_meta && $torrent->tmdb) {
            $meta = Tv::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'companies',
                'networks',
                'recommendedTv' => fn ($query) => $query
                    ->select('tv.id', 'tv.name', 'tv.poster', 'tv.first_air_date')
                    ->withMin('torrents', 'category_id')
                    ->has('torrents'),
            ])->find($torrent->tmdb);
        }

        if ($torrent->category->movie_meta && $torrent->tmdb) {
            $meta = Movie::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'companies',
                'collection',
                'recommendedMovies' => fn ($query) => $query
                    ->select('movies.id', 'movies.title', 'movies.poster', 'movies.release_date')
                    ->withMin('torrents', 'category_id')
                    ->has('torrents'),
            ])
                ->find($torrent->tmdb);
        }

        if ($torrent->category->game_meta && $torrent->igdb) {
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
            $platforms = PlatformLogo::whereIn('id', collect($meta->platforms)->pluck('platform_logo')->toArray())->get();
        }

        return view('torrent.show', [
            'torrent' => $torrent,
            'user'    => $user,
            'canEdit' => $user->group->is_editor
                || $user->group->is_modo
                || (
                    $user->id === $torrent->user_id
                    && (
                        $torrent->status !== Torrent::APPROVED
                        || now()->isBefore($torrent->created_at->addDay())
                    )
                ),
            'personal_freeleech' => cache()->get('personal_freeleech:'.$user->id),
            'meta'               => $meta,
            'platforms'          => $platforms,
            'total_tips'         => $torrent->tips()->sum('bon'),
            'user_tips'          => $torrent->tips()->where('sender_id', '=', $user->id)->sum('bon'),
            'featured'           => $torrent->featured === true ? FeaturedTorrent::where('torrent_id', '=', $id)->first() : null,
            'mediaInfo'          => $torrent->mediainfo !== null ? (new MediaInfo())->parse($torrent->mediainfo) : null,
            'last_seed_activity' => History::where('torrent_id', '=', $torrent->id)->where('seeder', '=', 1)->latest('updated_at')->first(),
            'playlists'          => $user->playlists,
            'audits'             => Audit::with('user')->where('model_entry_id', '=', $torrent->id)->where('model_name', '=', 'Torrent')->latest()->get(),
        ]);
    }

    /**
     * Show the form for editing the specified Torrent resource.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        abort_unless($user->group->is_editor || $user->group->is_modo || $user->id === $torrent->user_id, 403);

        return view('torrent.edit', [
            'categories' => Category::query()
                ->orderBy('position')
                ->get()
                ->mapWithKeys(fn ($cat) => [
                    $cat['id'] => [
                        'name' => $cat['name'],
                        'type' => match (true) {
                            $cat->movie_meta => 'movie',
                            $cat->tv_meta    => 'tv',
                            $cat->game_meta  => 'game',
                            $cat->music_meta => 'music',
                            $cat->no_meta    => 'no',
                            default          => 'no',
                        },
                    ]
                ]),
            'types'        => Type::orderBy('position')->get()->mapWithKeys(fn ($type) => [$type['id'] => ['name' => $type['name']]]),
            'resolutions'  => Resolution::orderBy('position')->get(),
            'regions'      => Region::orderBy('position')->get(),
            'distributors' => Distributor::orderBy('name')->get(),
            'keywords'     => Keyword::where('torrent_id', '=', $torrent->id)->pluck('name'),
            'torrent'      => $torrent,
            'user'         => $user,
        ]);
    }

    /**
     * Update the specified Torrent resource in storage.
     */
    public function update(UpdateTorrentRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        abort_unless(
            $user->group->is_editor
            || $user->group->is_modo
            || (
                $user->id === $torrent->user_id
                && (
                    $torrent->status !== Torrent::APPROVED
                    || now()->isBefore($torrent->created_at->addDay())
                )
            ),
            403
        );

        $torrent->update($request->validated());

        // Cover Image for No-Meta Torrents
        if ($request->hasFile('torrent-cover')) {
            $image_cover = $request->file('torrent-cover');

            abort_if(\is_array($image_cover), 400);

            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(400, 600)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner')) {
            $image_cover = $request->file('torrent-banner');

            abort_if(\is_array($image_cover), 400);

            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // Torrent Keywords System
        Keyword::where('torrent_id', '=', $torrent->id)->delete();

        $keywords = [];

        foreach (TorrentTools::parseKeywords($request->string('keywords')) as $keyword) {
            $keywords[] = ['torrent_id' => $torrent->id, 'name' => $keyword];
        }

        foreach (collect($keywords)->chunk(65_000 / 2) as $keywords) {
            Keyword::upsert($keywords->toArray(), ['torrent_id', 'name']);
        }

        $category = $torrent->category;

        // TMDB Meta
        if ($torrent->tmdb != 0) {
            switch (true) {
                case $category->tv_meta:
                    (new TMDBScraper())->tv($torrent->tmdb);

                    break;
                case $category->movie_meta:
                    (new TMDBScraper())->movie($torrent->tmdb);

                    break;
            }
        }

        return to_route('torrents.show', ['id' => $id])
            ->with('success', 'Successfully Edited!');
    }

    /**
     * Delete A Torrent.
     *
     * @throws Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'message' => [
                'required',
                'min:1',
            ],
        ]);

        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        abort_unless($user->group->is_modo || ($user->id === $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay())), 403);

        Notification::send(
            User::query()->whereHas('history', fn ($query) => $query->where('torrent_id', '=', $torrent->id))->get(),
            new TorrentDeleted($torrent, $request->message),
        );

        // Reset Requests
        $torrent->requests()->update([
            'torrent_id' => null,
        ]);

        //Remove Torrent related info
        cache()->forget(\sprintf('torrent:%s', $torrent->info_hash));

        $torrent->comments()->delete();
        $torrent->peers()->delete();
        $torrent->history()->delete();
        $torrent->hitrun()->delete();
        $torrent->files()->delete();
        $torrent->playlists()->detach();
        $torrent->subtitles()->delete();
        $torrent->resurrections()->delete();
        $torrent->featured()->delete();

        $freeleechTokens = $torrent->freeleechTokens();

        foreach ($freeleechTokens->get() as $freeleechToken) {
            cache()->forget('freeleech_token:'.$freeleechToken->user_id.':'.$torrent->id);
        }

        $freeleechTokens->delete();

        cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

        Unit3dAnnounce::removeTorrent($torrent);

        $torrent->delete();

        return to_route('torrents.index')
            ->with('success', 'Torrent Has Been Deleted!');
    }

    /**
     * Torrent Upload Form.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        abort_unless($user->can_upload ?? $user->group->can_upload, 403, __('torrent.cant-upload').' '.__('torrent.cant-upload-desc'));

        return view('torrent.create', [
            'categories' => Category::orderBy('position')
                ->get()
                ->mapWithKeys(fn ($category) => [$category->id => [
                    'name' => $category->name,
                    'type' => match (true) {
                        $category->movie_meta => 'movie',
                        $category->tv_meta    => 'tv',
                        $category->game_meta  => 'game',
                        $category->music_meta => 'music',
                        $category->no_meta    => 'no',
                        default               => 'no',
                    },
                ]])
                ->toArray(),
            'types'        => Type::orderBy('position')->get(),
            'resolutions'  => Resolution::orderBy('position')->get(),
            'regions'      => Region::orderBy('position')->get(),
            'distributors' => Distributor::orderBy('name')->get(),
            'user'         => $request->user(),
            'category_id'  => $request->category_id ?? Category::query()->first()->id,
            'title'        => urldecode((string) $request->title),
            'imdb'         => $request->imdb,
            'tmdb'         => $request->tmdb,
            'mal'          => $request->mal,
            'tvdb'         => $request->tvdb,
            'igdb'         => $request->igdb,
        ]);
    }

    /**
     * Upload A Torrent.
     */
    public function store(StoreTorrentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->can_upload ?? $user->group->can_upload, 403, __('torrent.cant-upload').' '.__('torrent.cant-upload-desc'));

        abort_if(\is_array($request->file('torrent')), 400);

        abort_if(\is_array($request->file('nfo')), 400);

        $decodedTorrent = TorrentTools::normalizeTorrent($request->file('torrent'));

        $meta = Bencode::get_meta($decodedTorrent);

        $fileName = uniqid('', true).'.torrent'; // Generate a unique name
        file_put_contents(getcwd().'/files/torrents/'.$fileName, Bencode::bencode($decodedTorrent));

        $torrent = Torrent::create([
            'mediainfo' => TorrentTools::anonymizeMediainfo($request->filled('mediainfo') ? $request->string('mediainfo') : null),
            'info_hash' => Bencode::get_infohash($decodedTorrent),
            'file_name' => $fileName,
            'num_file'  => $meta['count'],
            'folder'    => Bencode::get_name($decodedTorrent),
            'size'      => $meta['size'],
            'nfo'       => $request->hasFile('nfo') ? TorrentTools::getNfo($request->file('nfo')) : '',
            'user_id'   => $user->id,
        ] + $request->safe()->except(['torrent']));

        // Update the status on this torrent moderation message table.
        // The status on the torrent itself will be updated with the TorrentHelper().
        // Both places are kept in order to have the torrent status quickly accesible for the announce.
        TorrentModerationMessage::create([
            'moderated_by' => User::SYSTEM_USER_ID,
            'torrent_id'   => $torrent->id,
        ]);

        // Populate the status/seeders/leechers/times_completed fields for the external tracker
        $torrent->refresh();

        $category = Category::findOrFail($request->integer('category_id'));

        // Backup the files contained in the torrent
        $files = TorrentTools::getTorrentFiles($decodedTorrent);

        foreach ($files as &$file) {
            $file['torrent_id'] = $torrent->id;
        }

        // Can't insert them all at once since some torrents have more files than mysql supports placeholders.
        // Divide by 3 since we're inserting 3 fields: name, size and torrent_id
        foreach (collect($files)->chunk(intdiv(65_000, 3)) as $files) {
            TorrentFile::insert($files->toArray());
        }

        // Cover Image for No-Meta Torrents
        if ($request->hasFile('torrent-cover')) {
            $image_cover = $request->file('torrent-cover');

            abort_if(\is_array($image_cover), 400);

            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(400, 600)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner')) {
            $image_cover = $request->file('torrent-banner');

            abort_if(\is_array($image_cover), 400);

            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // Tracker updates come after initial database updates in case tracker's offline

        Unit3dAnnounce::addTorrent($torrent);

        if ($torrent->getAttribute('featured')) {
            Unit3dAnnounce::addFeaturedTorrent($torrent->id);
        }

        // TMDB updates come after tracker updates in case TMDB's offline

        // TMDB Meta
        if ($torrent->tmdb != 0) {
            switch (true) {
                case $category->tv_meta:
                    (new TMDBScraper())->tv($torrent->tmdb);

                    break;
                case $category->movie_meta:
                    (new TMDBScraper())->movie($torrent->tmdb);

                    break;
            }
        }

        // Torrent Keywords System
        $keywords = [];

        foreach (TorrentTools::parseKeywords($request->string('keywords')) as $keyword) {
            $keywords[] = ['torrent_id' => $torrent->id, 'name' => $keyword];
        }

        foreach (collect($keywords)->chunk(intdiv(65_000, 2)) as $keywords) {
            Keyword::upsert($keywords->toArray(), ['torrent_id', 'name']);
        }

        // check for trusted user and update torrent
        if ($user->group->is_trusted && !$request->boolean('mod_queue_opt_in')) {
            $appurl = config('app.url');
            $user = $torrent->user;
            $username = $user->username;
            $anon = $torrent->anon;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chatRepository->systemMessage(
                    \sprintf('User [url=%s/users/', $appurl).$username.']'.$username.\sprintf('[/url] has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now!'
                );
            } else {
                $this->chatRepository->systemMessage(
                    \sprintf('An anonymous user has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now!'
                );
            }

            if ($torrent->free >= 1) {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] has been granted '.$torrent->free.'% FreeLeech! Grab It While You Can!'
                );
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return to_route('download_check', ['id' => $torrent->id])
            ->with('success', 'Your torrent file is ready to be downloaded and seeded!');
    }
}
