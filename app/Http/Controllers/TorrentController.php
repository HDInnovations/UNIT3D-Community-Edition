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

use App\Bots\IRCAnnounceBot;
use App\Helpers\Bbcode;
use App\Helpers\Bencode;
use App\Helpers\MediaInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Models\BonTransactions;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\History;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use App\Models\PlaylistTorrent;
use App\Models\PrivateMessage;
use App\Models\TagTorrent;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\TorrentRequest;
use App\Models\Type;
use App\Models\User;
use App\Models\Warning;
use App\Notifications\NewReseedRequest;
use App\Repositories\ChatRepository;
use App\Repositories\TorrentFacetedRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Character;
use MarcReichel\IGDBLaravel\Models\Game;

class TorrentController extends Controller
{
    /**
     * @var TorrentFacetedRepository
     */
    private $faceted;

    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * @var int
     */
    private const PAGE = 0;

    /**
     * @var string
     */
    private const SORTING = 'created_at';

    /**
     * @var int
     */
    private const DIRECTION = 2;

    /**
     * @var string
     */
    private const ORDER = 'desc';

    /**
     * @var int
     */
    private const QTY = 25;

    /**
     * RequestController Constructor.
     *
     * @param TorrentFacetedRepository $faceted
     * @param ChatRepository           $chat
     */
    public function __construct(TorrentFacetedRepository $faceted, ChatRepository $chat)
    {
        $this->faceted = $faceted;
        $this->chat = $chat;
    }

    /**
     * Displays Torrent List View.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function torrents(Request $request)
    {
        $user = $request->user();
        $repository = $this->faceted;

        $torrents = Torrent::with(['user', 'category', 'tags'])->withCount(['thanks', 'comments'])->orderBy('sticky', 'desc')->orderBy('created_at', 'desc')->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $bookmarks = Bookmark::where('user_id', $user->id)->get();

        return view('torrent.torrents', [
            'personal_freeleech' => $personal_freeleech,
            'repository'         => $repository,
            'bookmarks'          => $bookmarks,
            'torrents'           => $torrents,
            'user'               => $user,
            'sorting'            => '',
            'direction'          => 1,
            'links'              => null,
        ]);
    }

    /**
     * Torrent Similar Results.
     *
     * @param \Illuminate\Http\Request $request
     * @param $category_id
     * @param $tmdb
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function similar(Request $request, $category_id, $tmdb)
    {
        $user = $request->user();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $torrents = Torrent::with(['user', 'category'])
            ->withCount(['thanks', 'comments'])
            ->where('category_id', '=', $category_id)
            ->where('tmdb', '=', $tmdb)
            ->latest()
            ->get();

        if (!$torrents || $torrents->count() < 1) {
            abort(404, 'No Similar Torrents Found');
        }

        return view('torrent.similar', [
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'torrents'           => $torrents,
            'tmdb'               => $tmdb,
        ]);
    }

    /**
     * Displays Torrent Cards View.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cardLayout(Request $request)
    {
        $user = $request->user();
        $torrents = Torrent::with(['user', 'category'])->latest()->paginate(33);
        $repository = $this->faceted;

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        foreach ($torrents as $torrent) {
            $meta = null;
            if ($torrent->category->tv_meta) {
                if ($torrent->tmdb || $torrent->tmdb != 0) {
                    $meta = $client->scrape('tv', null, $torrent->tmdb);
                } elseif ($torrent->imdb && $torrent->imdb != 0) {
                    $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                }
            }
            if ($torrent->category->movie_meta) {
                if ($torrent->tmdb || $torrent->tmdb != 0) {
                    $meta = $client->scrape('movie', null, $torrent->tmdb);
                } elseif ($torrent->imdb && $torrent->imdb != 0) {
                    $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                }
            }
            if ($torrent->category->game_meta) {
                $meta = Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($torrent->igdb);
            }
            if ($meta) {
                $torrent->meta = $meta;
            }
        }

        return view('torrent.cards', [
            'user'       => $user,
            'torrents'   => $torrents,
            'repository' => $repository,
        ]);
    }

    /**
     * Torrent Filter Remember Setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function filtered(Request $request)
    {
        $user = $request->user();
        if ($user && $request->has('force')) {
            if ($request->input('force') == 1) {
                $user->torrent_filters = 0;
                $user->save();
            } elseif ($request->input('force') == 2) {
                $user->torrent_filters = 1;
                $user->save();
            }
        }
    }

    /**
     * Torrent Grouping.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     * @throws \Throwable
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupingLayout(Request $request)
    {
        $user = $request->user();
        $repository = $this->faceted;
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $logger = null;
        $cache = [];
        $attributes = [];

        $torrent = DB::table('torrents')->selectRaw('distinct(torrents.imdb),max(torrents.created_at) as screated_at,max(torrents.seeders) as sseeders,max(torrents.leechers) as sleechers,max(torrents.times_completed) as stimes_completed,max(torrents.name) as sname,max(torrents.size) as ssize')->leftJoin('torrents as torrentsl', 'torrents.id', '=', 'torrentsl.id')->groupBy('torrents.imdb')->whereRaw('torrents.status = ? AND torrents.imdb != ?', [1, 0]);

        $prelauncher = $torrent->orderBy('s'.self::SORTING, self::ORDER)->pluck('imdb')->toArray();

        if (!is_array($prelauncher)) {
            $prelauncher = [];
        }
        $links = new Paginator($prelauncher, count($prelauncher), self::QTY);

        $hungry = array_chunk($prelauncher, self::QTY);
        $fed = [];
        if (is_array($hungry) && array_key_exists(self::PAGE, $hungry)) {
            $fed = $hungry[self::PAGE];
        }
        $totals = [];
        $counts = [];
        $launcher = Torrent::with(['user', 'category'])->withCount(['thanks', 'comments'])->whereIn('imdb', $fed)->orderBy(self::SORTING, self::ORDER);
        foreach ($launcher->cursor() as $chunk) {
            if ($chunk->imdb) {
                $totals[$chunk->imdb] = !array_key_exists($chunk->imdb, $totals) ? 1 : $totals[$chunk->imdb] + 1;
                if (!array_key_exists('imdb'.$chunk->imdb, $cache)) {
                    $cache['imdb'.$chunk->imdb] = [];
                }
                if (!array_key_exists('imdb'.$chunk->imdb, $counts)) {
                    $counts['imdb'.$chunk->imdb] = 0;
                }
                if (!array_key_exists('imdb'.$chunk->imdb, $attributes)) {
                    $attributes['imdb'.$chunk->imdb]['seeders'] = 0;
                    $attributes['imdb'.$chunk->imdb]['leechers'] = 0;
                    $attributes['imdb'.$chunk->imdb]['times_completed'] = 0;
                    $attributes['imdb'.$chunk->imdb]['types'] = [];
                    $attributes['imdb'.$chunk->imdb]['categories'] = [];
                    $attributes['imdb'.$chunk->imdb]['genres'] = [];
                }
                $attributes['imdb'.$chunk->imdb]['times_completed'] += $chunk->times_completed;
                $attributes['imdb'.$chunk->imdb]['seeders'] += $chunk->seeders;
                $attributes['imdb'.$chunk->imdb]['leechers'] += $chunk->leechers;
                if (!array_key_exists($chunk->type, $attributes['imdb'.$chunk->imdb])) {
                    $attributes['imdb'.$chunk->imdb]['types'][$chunk->type] = $chunk->type;
                }
                if (!array_key_exists($chunk->category_id, $attributes['imdb'.$chunk->imdb])) {
                    $attributes['imdb'.$chunk->imdb]['categories'][$chunk->category_id] = $chunk->category_id;
                }
                $cache['imdb'.$chunk->imdb]['torrent'.$counts['imdb'.$chunk->imdb]] = [
                    'created_at'      => $chunk->created_at,
                    'seeders'         => $chunk->seeders,
                    'leechers'        => $chunk->leechers,
                    'name'            => $chunk->name,
                    'times_completed' => $chunk->times_completed,
                    'size'            => $chunk->size,
                    'chunk'           => $chunk,
                ];
                $counts['imdb'.$chunk->imdb]++;
            }
        }
        $torrents = count($cache) > 0 ? $cache : null;

        if (is_array($torrents)) {
            $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
            foreach ($torrents as $k1 => $c) {
                foreach ($c as $k2 => $d) {
                    $meta = null;
                    if ($d['chunk']->category->tv_meta) {
                        if ($d['chunk']->tmdb || $d['chunk']->tmdb != 0) {
                            $meta = $client->scrape('tv', null, $d['chunk']->tmdb);
                        } elseif ($d['chunk']->imdb && $d['chunk']->imdb != 0) {
                            $meta = $client->scrape('tv', 'tt'.$d['chunk']->imdb);
                        }
                    }
                    if ($d['chunk']->category->movie_meta) {
                        if ($d['chunk']->tmdb || $d['chunk']->tmdb != 0) {
                            $meta = $client->scrape('movie', null, $d['chunk']->tmdb);
                        } elseif ($d['chunk']->imdb && $d['chunk']->imdb != 0) {
                            $meta = $client->scrape('movie', 'tt'.$d['chunk']->imdb);
                        }
                    }
                    if ($d['chunk']->category->game_meta) {
                        $meta = Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($d['chunk']->igdb);
                    }
                    if ($meta) {
                        $d['chunk']->meta = $meta;
                    }
                }
            }
        }

        $bookmarks = Bookmark::where('user_id', $user->id)->get();

        return view('torrent.groupings', [
            'torrents'           => $torrents,
            'user'               => $user,
            'sorting'            => self::SORTING,
            'direction'          => self::DIRECTION,
            'links'              => $links,
            'totals'             => $totals,
            'personal_freeleech' => $personal_freeleech,
            'repository'         => $repository,
            'attributes'         => $attributes,
            'bookmarks'          => $bookmarks,
        ])->render();
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param \Illuminate\Http\Request $request
     * @param Torrent                  $torrent
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     * @throws \Throwable
     *
     * @return array
     */
    public function faceted(Request $request, Torrent $torrent)
    {
        $user = $request->user();
        $repository = $this->faceted;
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $collection = null;
        $history = null;
        $nohistory = null;
        $seedling = null;
        $notdownloaded = null;
        $downloaded = null;
        $leeching = null;
        $idling = null;

        if ($request->has('view') && $request->input('view') == 'group') {
            $collection = 1;
        }
        if ($request->has('notdownloaded') && $request->input('notdownloaded') != null) {
            $notdownloaded = 1;
            $nohistory = 1;
        }
        if ($request->has('seeding') && $request->input('seeding') != null) {
            $seedling = 1;
            $history = 1;
        }
        if ($request->has('downloaded') && $request->input('downloaded') != null) {
            $downloaded = 1;
            $history = 1;
        }
        if ($request->has('leeching') && $request->input('leeching') != null) {
            $leeching = 1;
            $history = 1;
        }
        if ($request->has('idling') && $request->input('idling') != null) {
            $idling = 1;
            $history = 1;
        }

        $search = $request->input('search');
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
        $page = (int) $request->input('page');

        $totals = null;
        $links = null;
        $order = null;
        $sorting = null;

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

        if ($request->has('sorting') && $request->input('sorting') != null) {
            $sorting = $request->input('sorting');
        }
        if ($request->has('direction') && $request->input('direction') != null) {
            $order = $request->input('direction');
        }
        if (!$sorting || $sorting == null || !$order || $order == null) {
            $sorting = 'created_at';
            $order = 'desc';
            // $order = 'asc';
        }

        $direction = $order == 'asc' ? 1 : 2;

        $qty = $request->has('qty') ? $request->input('qty') : 25;

        if ($collection == 1) {
            $torrent = DB::table('torrents')->selectRaw('distinct(torrents.imdb),max(torrents.created_at) as screated_at,max(torrents.seeders) as sseeders,max(torrents.leechers) as sleechers,max(torrents.times_completed) as stimes_completed,max(torrents.name) as sname,max(torrents.size) as ssize')
                ->leftJoin('torrents as torrentsl', 'torrents.id', '=', 'torrentsl.id')
                ->groupBy('torrents.imdb')
                ->whereRaw('torrents.status = ? AND torrents.imdb != ?', [1, 0]);

            if ($request->has('search') && $request->input('search') != null) {
                $torrent->where(function ($query) use ($search) {
                    $query->where('torrentsl.name', 'like', $search);
                });
            }
            if ($request->has('description') && $request->input('description') != null) {
                $torrent->where(function ($query) use ($description) {
                    $query->where('torrentsl.description', 'like', $description)->orwhere('torrentsl.mediainfo', 'like', $description);
                });
            }

            if ($request->has('uploader') && $request->input('uploader') != null) {
                $match = User::whereRaw('(username like ?)', [$uploader])->orderBy('username', 'ASC')->first();
                if (null === $match) {
                    return ['result' => [], 'count' => 0];
                }
                $torrent->where('torrentsl.user_id', '=', $match->id)->where('torrentsl.anon', '=', 0);
            }

            if ($request->has('imdb') && $request->input('imdb') != null) {
                $torrent->where('torrentsl.imdb', '=', str_replace('tt', '', $imdb));
            }

            if ($request->has('tvdb') && $request->input('tvdb') != null) {
                $torrent->where('torrentsl.tvdb', '=', $tvdb);
            }

            if ($request->has('tmdb') && $request->input('tmdb') != null) {
                $torrent->where('torrentsl.tmdb', '=', $tmdb);
            }

            if ($request->has('mal') && $request->input('mal') != null) {
                $torrent->where('torrentsl.mal', '=', $mal);
            }

            if ($request->has('igdb') && $request->input('igdb') != null) {
                $torrent->where('torrentsl.igdb', '=', $igdb);
            }

            if ($request->has('start_year') && $request->has('end_year') && $request->input('start_year') != null && $request->input('end_year') != null) {
                $torrent->whereBetween('torrentsl.release_year', [$start_year, $end_year]);
            }

            if ($request->has('categories') && $request->input('categories') != null) {
                $torrent->whereIn('torrentsl.category_id', $categories);
            }

            if ($request->has('types') && $request->input('types') != null) {
                $torrent->whereIn('torrentsl.type', $types);
            }

            if ($request->has('genres') && $request->input('genres') != null) {
                $genreID = TagTorrent::select(['torrent_id'])->distinct()->whereIn('tag_name', $genres)->get();
                $torrent->whereIn('torrentsl.id', $genreID);
            }

            if ($request->has('freeleech') && $request->input('freeleech') != null) {
                $torrent->where('torrentsl.free', '=', $freeleech);
            }

            if ($request->has('doubleupload') && $request->input('doubleupload') != null) {
                $torrent->where('torrentsl.doubleup', '=', $doubleupload);
            }

            if ($request->has('featured') && $request->input('featured') != null) {
                $torrent->where('torrentsl.featured', '=', $featured);
            }

            if ($request->has('stream') && $request->input('stream') != null) {
                $torrent->where('torrentsl.stream', '=', $stream);
            }

            if ($request->has('highspeed') && $request->input('highspeed') != null) {
                $torrent->where('torrentsl.highspeed', '=', $highspeed);
            }

            if ($request->has('sd') && $request->input('sd') != null) {
                $torrent->where('torrentsl.sd', '=', $sd);
            }

            if ($request->has('internal') && $request->input('internal') != null) {
                $torrent->where('torrentsl.internal', '=', $internal);
            }

            if ($request->has('alive') && $request->input('alive') != null) {
                $torrent->where('torrentsl.seeders', '>=', $alive);
            }

            if ($request->has('dying') && $request->input('dying') != null) {
                $torrent->where('torrentsl.seeders', '=', $dying)->where('torrentsl.times_completed', '>=', 3);
            }

            if ($request->has('dead') && $request->input('dead') != null) {
                $torrent->where('torrentsl.seeders', '=', $dead);
            }
        } elseif ($nohistory == 1) {
            $history = History::select(['torrents.id'])->leftJoin('torrents', 'torrents.info_hash', '=', 'history.info_hash')->where('history.user_id', '=', $user->id)->get()->toArray();
            if (!$history || !is_array($history)) {
                $history = [];
            }
            $torrent = $torrent->with(['user', 'category', 'tags'])->withCount(['thanks', 'comments'])->whereNotIn('torrents.id', $history);
        } elseif ($history == 1) {
            $torrent = History::where('history.user_id', '=', $user->id);
            $torrent->where(function ($query) use ($user, $seedling, $downloaded, $leeching, $idling) {
                if ($seedling == 1) {
                    $query->orWhere(function ($query) use ($user) {
                        $query->whereRaw('history.active = ? AND history.seeder = ?', [1, 1]);
                    });
                }
                if ($downloaded == 1) {
                    $query->orWhere(function ($query) use ($user) {
                        $query->whereRaw('history.completed_at is not null');
                    });
                }
                if ($leeching == 1) {
                    $query->orWhere(function ($query) use ($user) {
                        $query->whereRaw('history.active = ? AND history.seeder = ? AND history.completed_at is null', [1, 0]);
                    });
                }
                if ($idling == 1) {
                    $query->orWhere(function ($query) use ($user) {
                        $query->whereRaw('history.active = ? AND history.seeder = ? AND history.completed_at is null', [0, 0]);
                    });
                }
            });
            $torrent = $torrent->selectRaw('distinct(torrents.id),max(torrents.sticky),max(torrents.created_at),max(torrents.seeders),max(torrents.leechers),max(torrents.name),max(torrents.size),max(torrents.times_completed)')->leftJoin('torrents', function ($join) use ($user) {
                $join->on('history.info_hash', '=', 'torrents.info_hash');
            })->groupBy('torrents.id');
        } else {
            $torrent = $torrent->with(['user', 'category', 'tags'])->withCount(['thanks', 'comments']);
        }
        if ($collection != 1) {
            if ($request->has('search') && $request->input('search') != null) {
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
        }

        $logger = null;
        $cache = [];
        $attributes = [];
        $links = null;
        if ($collection == 1) {
            if ($logger === null) {
                $logger = 'torrent.results_groupings';
            }

            $prelauncher = $torrent->orderBy('s'.$sorting, $order)->pluck('imdb')->toArray();

            if (!is_array($prelauncher)) {
                $prelauncher = [];
            }
            $links = new Paginator($prelauncher, count($prelauncher), $qty);

            $hungry = array_chunk($prelauncher, $qty);
            $fed = [];
            if ($page < 1) {
                $page = 1;
            }
            if (is_array($hungry) && array_key_exists($page - 1, $hungry)) {
                $fed = $hungry[$page - 1];
            }
            $totals = [];
            $counts = [];
            $launcher = Torrent::with(['user', 'category', 'tags'])->withCount(['thanks', 'comments'])->whereIn('imdb', $fed)->orderBy($sorting, $order);
            foreach ($launcher->cursor() as $chunk) {
                if ($chunk->imdb) {
                    $totals[$chunk->imdb] = !array_key_exists($chunk->imdb, $totals) ? 1 : $totals[$chunk->imdb] + 1;
                    if (!array_key_exists('imdb'.$chunk->imdb, $cache)) {
                        $cache['imdb'.$chunk->imdb] = [];
                    }
                    if (!array_key_exists('imdb'.$chunk->imdb, $counts)) {
                        $counts['imdb'.$chunk->imdb] = 0;
                    }
                    if (!array_key_exists('imdb'.$chunk->imdb, $attributes)) {
                        $attributes['imdb'.$chunk->imdb]['seeders'] = 0;
                        $attributes['imdb'.$chunk->imdb]['leechers'] = 0;
                        $attributes['imdb'.$chunk->imdb]['times_completed'] = 0;
                        $attributes['imdb'.$chunk->imdb]['types'] = [];
                        $attributes['imdb'.$chunk->imdb]['categories'] = [];
                        $attributes['imdb'.$chunk->imdb]['genres'] = [];
                    }
                    $attributes['imdb'.$chunk->imdb]['times_completed'] += $chunk->times_completed;
                    $attributes['imdb'.$chunk->imdb]['seeders'] += $chunk->seeders;
                    $attributes['imdb'.$chunk->imdb]['leechers'] += $chunk->leechers;
                    if (!array_key_exists($chunk->type, $attributes['imdb'.$chunk->imdb])) {
                        $attributes['imdb'.$chunk->imdb]['types'][$chunk->type] = $chunk->type;
                    }
                    if (!array_key_exists($chunk->category_id, $attributes['imdb'.$chunk->imdb])) {
                        $attributes['imdb'.$chunk->imdb]['categories'][$chunk->category_id] = $chunk->category_id;
                    }
                    $cache['imdb'.$chunk->imdb]['torrent'.$counts['imdb'.$chunk->imdb]] = [
                        'created_at'      => $chunk->created_at,
                        'seeders'         => $chunk->seeders,
                        'leechers'        => $chunk->leechers,
                        'name'            => $chunk->name,
                        'times_completed' => $chunk->times_completed,
                        'size'            => $chunk->size,
                        'chunk'           => $chunk,
                    ];
                    $counts['imdb'.$chunk->imdb]++;
                }
            }
            $torrents = count($cache) > 0 ? $cache : null;
        } elseif ($history == 1) {
            $prelauncher = $torrent->orderBy('torrents.sticky', 'desc')->orderBy('torrents.'.$sorting, $order)->pluck('id')->toArray();

            if (!is_array($prelauncher)) {
                $prelauncher = [];
            }

            $links = new Paginator($prelauncher, count($prelauncher), $qty);

            $hungry = array_chunk($prelauncher, $qty);
            $fed = [];
            if ($page < 1) {
                $page = 1;
            }
            if (is_array($hungry) && array_key_exists($page - 1, $hungry)) {
                $fed = $hungry[$page - 1];
            }
            $torrents = Torrent::with(['user', 'category', 'tags'])->withCount(['thanks', 'comments'])->whereIn('id', $fed)->orderBy($sorting, $order)->get();
        } else {
            $torrents = $torrent->orderBy('sticky', 'desc')->orderBy($sorting, $order)->paginate($qty);
        }
        if ($collection == 1 && is_array($torrents)) {
            $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
            foreach ($torrents as $k1 => $c) {
                foreach ($c as $k2 => $d) {
                    $meta = null;
                    if ($d['chunk']->category->tv_meta) {
                        if ($d['chunk']->tmdb || $d['chunk']->tmdb != 0) {
                            $meta = $client->scrape('tv', null, $d['chunk']->tmdb);
                        } elseif ($d['chunk']->imdb && $d['chunk']->imdb != 0) {
                            $meta = $client->scrape('tv', 'tt'.$d['chunk']->imdb);
                        }
                    }
                    if ($d['chunk']->category->movie_meta) {
                        if ($d['chunk']->tmdb || $d['chunk']->tmdb != 0) {
                            $meta = $client->scrape('movie', null, $d['chunk']->tmdb);
                        } elseif ($d['chunk']->imdb && $d['chunk']->imdb != 0) {
                            $meta = $client->scrape('movie', 'tt'.$d['chunk']->imdb);
                        }
                    }
                    if ($d['chunk']->category->game_meta) {
                        $meta = Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($d['chunk']->igdb);
                    }
                    if ($meta) {
                        $d['chunk']->meta = $meta;
                    }
                }
            }
        }
        if ($request->has('view') && $request->input('view') == 'card') {
            if ($logger == null) {
                $logger = 'torrent.results_cards';
            }
            $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
            foreach ($torrents as $torrent) {
                $meta = null;
                if ($torrent->category->tv_meta) {
                    if ($torrent->tmdb || $torrent->tmdb != 0) {
                        $meta = $client->scrape('tv', null, $torrent->tmdb);
                    } elseif ($torrent->imdb && $torrent->imdb != 0) {
                        $meta = $client->scrape('tv', 'tt'.$torrent->imdb);
                    }
                }
                if ($torrent->category->movie_meta) {
                    if ($torrent->tmdb || $torrent->tmdb != 0) {
                        $meta = $client->scrape('movie', null, $torrent->tmdb);
                    } elseif ($torrent->imdb && $torrent->imdb != 0) {
                        $meta = $client->scrape('movie', 'tt'.$torrent->imdb);
                    }
                }
                if ($torrent->category->game_meta) {
                    $meta = Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($torrent->igdb);
                }
                if ($meta) {
                    $torrent->meta = $meta;
                }
            }
        }
        if ($logger == null) {
            $logger = 'torrent.results';
        }

        $bookmarks = Bookmark::where('user_id', $user->id)->get();

        return view($logger, [
            'torrents'           => $torrents,
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'sorting'            => $sorting,
            'direction'          => $direction,
            'links'              => $links,
            'totals'             => $totals,
            'repository'         => $repository,
            'attributes'         => $attributes,
            'bookmarks'          => $bookmarks,
        ])->render();
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

    /**
     * Display The Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function torrent(Request $request, $id)
    {
        $torrent = Torrent::withAnyStatus()->with(['comments', 'category', 'subtitles'])->findOrFail($id);
        $uploader = $torrent->user;
        $user = $request->user();
        $freeleech_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $comments = $torrent->comments()->latest()->paginate(5);
        $total_tips = BonTransactions::where('torrent_id', '=', $id)->sum('cost');
        $user_tips = BonTransactions::where('torrent_id', '=', $id)->where('sender', '=', $request->user()->id)->sum('cost');
        $last_seed_activity = History::where('info_hash', '=', $torrent->info_hash)->where('seeder', '=', 1)->latest('updated_at')->first();

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        $meta = null;
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

        if (isset($meta) && $meta->recommendations) {
            $meta->recommendations['results'] = array_map(function ($recomentaion) {
                $recomentaion['exists'] = Torrent::where('tmdb', $recomentaion['id'])->get()->isNotEmpty();

                return $recomentaion;
            }, $meta->recommendations['results']);
        }

        $characters = null;
        if ($torrent->category->game_meta) {
            $meta = Game::with(['cover' => ['url', 'image_id'], 'artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($torrent->igdb);
            $characters = Character::whereIn('games', [$torrent->igdb])->take(6)->get();
        }

        $featured = $torrent->featured == 1 ? FeaturedTorrent::where('torrent_id', '=', $id)->first() : null;

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

        $playlists = $user->playlists;

        return view('torrent.torrent', [
            'torrent'            => $torrent,
            'comments'           => $comments,
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'freeleech_token'    => $freeleech_token,
            'meta'               => $meta,
            'characters'         => $characters,
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
            'playlists'          => $playlists,
        ]);
    }

    /**
     * Torrent Edit Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        abort_unless($user->group->is_modo || $user->id == $torrent->user_id, 403);

        return view('torrent.edit_torrent', [
            'categories' => Category::all()->sortBy('position'),
            'types'      => Type::all()->sortBy('position'),
            'torrent'    => $torrent,
        ]);
    }

    /**
     * Edit A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));

        abort_unless($user->group->is_modo || $user->id == $torrent->user_id, 403);
        $torrent->name = $request->input('name');
        $torrent->slug = Str::slug($torrent->name);
        $torrent->description = $request->input('description');
        $torrent->category_id = $request->input('category_id');
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->type = $request->input('type');
        $torrent->mediainfo = $request->input('mediainfo');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $request->input('internal');

        $v = validator($torrent->toArray(), [
            'name'         => 'required',
            'slug'         => 'required',
            'description'  => 'required',
            'category_id'  => 'required',
            'imdb'         => 'required|numeric',
            'tvdb'         => 'required|numeric',
            'tmdb'         => 'required|numeric',
            'mal'          => 'required|numeric',
            'igdb'         => 'required|numeric',
            'type'         => 'required',
            'anon'         => 'required',
            'stream'       => 'required',
            'sd'           => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors($v->errors());
        }
        $torrent->save();
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
            $old_genres = TagTorrent::where('torrent_id', '=', $torrent->id)->get();
            foreach ($old_genres as $old_genre) {
                $old_genre->delete();
            }

            foreach ($meta->genres as $genre) {
                $tag = new TagTorrent();
                $tag->torrent_id = $torrent->id;
                $tag->tag_name = $genre;
                $tag->save();
            }
        }

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Successfully Edited!');
    }

    /**
     * Delete A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTorrent(Request $request)
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
                    $pmuser->subject = sprintf('Torrent Deleted! - %s', $torrent->name);
                    $pmuser->message = sprintf('[b]Attention:[/b] Torrent %s has been removed from our site. Our system shows that you were either the uploader, a seeder or a leecher on said torrent. We just wanted to let you know you can safely remove it from your client.
                                        [b]Removal Reason:[/b] %s
                                        [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]', $torrent->name, $request->message);
                    $pmuser->save();
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
                PlaylistTorrent::where('torrent_id', '=', $id)->delete();
                if ($torrent->featured == 1) {
                    FeaturedTorrent::where('torrent_id', '=', $id)->delete();
                }
                $torrent->delete();

                return redirect()->route('torrents')
                    ->withSuccess('Torrent Has Been Deleted!');
            }
        } else {
            $errors = '';
            foreach ($v->errors()->all() as $error) {
                $errors .= $error."\n";
            }
            \Log::notice(sprintf('Deletion of torrent failed due to: 

%s', $errors));

            return redirect()->route('home.index')
                ->withErrors('Unable to delete Torrent');
        }
    }

    /**
     * Display Peers Of A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function peers($id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $peers = Peer::with(['user'])->where('torrent_id', '=', $id)->latest('seeder')->paginate(25);

        return view('torrent.peers', ['torrent' => $torrent, 'peers' => $peers]);
    }

    /**
     * Display History Of A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history($id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $history = History::with(['user'])->where('info_hash', '=', $torrent->info_hash)->latest()->paginate(25);

        return view('torrent.history', ['torrent' => $torrent, 'history' => $history]);
    }

    /**
     * Torrent Upload Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $title
     * @param int                      $imdb
     * @param int                      $tmdb
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadForm(Request $request, $title = '', $imdb = 0, $tmdb = 0)
    {
        $user = $request->user();

        return view('torrent.upload', [
            'categories' => Category::all()->sortBy('position'),
            'types'      => Type::all()->sortBy('position'),
            'user'       => $user,
            'title'      => $title,
            'imdb'       => str_replace('tt', '', $imdb),
            'tmdb'       => $tmdb,
        ]);
    }

    /**
     * Upload A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \ErrorException
     * @throws \HttpInvalidParamException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $user = $request->user();

        // Preview The Upload
        $previewContent = null;
        if ($request->get('preview') == true) {
            $bbcode = new Bbcode();
            $previewContent = $bbcode->parse($request->input('description'), true);

            return redirect()->route('upload_form')
                ->withInput()
                ->with(['previewContent' => $previewContent])
                ->withWarning('Torrent Description Preview Loaded!');
        }

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        $requestFile = $request->file('torrent');
        if ($request->hasFile('torrent') === false) {
            return view('torrent.upload', [
                'categories' => Category::all()->sortBy('position'),
                'types'      => Type::all()->sortBy('position'),
                'user'       => $user, ])
                ->withErrors('You Must Provide A Torrent File For Upload!');
        }

        if ($requestFile->getError() !== 0 && $requestFile->getClientOriginalExtension() !== 'torrent') {
            return view('torrent.upload', [
                'categories' => Category::all()->sortBy('position'),
                'types'      => Type::all()->sortBy('position'),
                'user'       => $user, ])
                ->withErrors('A Error Has Occurred!');
        }

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

            return redirect()->route('upload_form')
                ->withErrors($v->errors())->withInput();
        }
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
                    sprintf('User [url=%s/users/', $appurl).$username.']'.$username.sprintf('[/url] has uploaded [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            } else {
                $this->chat->systemMessage(
                    sprintf('An anonymous user has uploaded [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return redirect()->route('download_check', ['id' => $torrent->id])
            ->withSuccess('Your torrent file is ready to be downloaded and seeded!');
    }

    /**
     * Download Check.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadCheck(Request $request, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $user = $request->user();

        return view('torrent.download_check', ['torrent' => $torrent, 'user' => $user]);
    }

    /**
     * Download A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     * @param \App\Models\User         $rsskey
     *
     * @return TorrentFile
     */
    public function download(Request $request, $id, $rsskey = null)
    {
        $user = $request->user();
        if (!$user && $rsskey) {
            $user = User::where('rsskey', '=', $rsskey)->firstOrFail();
        }

        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        // User's ratio is too low
        if ($user->getRatio() < config('other.ratio')) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Your Ratio Is To Low To Download!');
        }

        // User's download rights are revoked
        if ($user->can_download == 0 && $torrent->user_id != $user->id) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Your Download Rights Have Been Revoked!');
        }

        // Torrent Status Is Rejected
        if ($torrent->isRejected()) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('This Torrent Has Been Rejected By Staff');
        }

        // Define the filename for the download
        $tmpFileName = str_replace([' ', '/', '\\'], ['.', '-', '-'], '['.config('torrent.source').']'.$torrent->name.'.torrent');

        // The torrent file exist ?
        if (!file_exists(getcwd().'/files/torrents/'.$torrent->file_name)) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Torrent File Not Found! Please Report This Torrent!');
        }
        // Delete the last torrent tmp file
        if (file_exists(getcwd().'/files/tmp/'.$tmpFileName)) {
            unlink(getcwd().'/files/tmp/'.$tmpFileName);
        }
        // Get the content of the torrent
        $dict = Bencode::bdecode(file_get_contents(getcwd().'/files/torrents/'.$torrent->file_name));
        if ($request->user() || ($rsskey && $user)) {
            // Set the announce key and add the user passkey
            $dict['announce'] = route('announce', ['passkey' => $user->passkey]);
            // Remove Other announce url
            unset($dict['announce-list']);
        } else {
            return redirect()->route('login');
        }

        $fileToDownload = Bencode::bencode($dict);
        file_put_contents(getcwd().'/files/tmp/'.$tmpFileName, $fileToDownload);

        return response()->download(getcwd().'/files/tmp/'.$tmpFileName)->deleteFileAfterSend(true);
    }

    /**
     * Bump A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bumpTorrent(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent->created_at = Carbon::now();
        $torrent->save();

        // Announce To Chat
        $torrent_url = hrefTorrent($torrent);
        $profile_url = hrefProfile($user);

        $this->chat->systemMessage(
            sprintf('Attention, [url=%s]%s[/url] has been bumped to the top by [url=%s]%s[/url]! It could use more seeds!', $torrent_url, $torrent->name, $profile_url, $user->username)
        );

        // Announce To IRC
        if (config('irc-bot.enabled') == true) {
            $appname = config('app.name');
            $bot = new IRCAnnounceBot();
            $bot->message('#announce', '['.$appname.'] User '.$user->username.' has bumped '.$torrent->name.' , it could use more seeds!');
            $bot->message('#announce', '[Category: '.$torrent->category->name.'] [Type: '.$torrent->type.'] [Size:'.$torrent->getSize().']');
            $bot->message('#announce', sprintf('[Link: %s]', $torrent_url));
        }

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Bumped To The Top Successfully!');
    }

    /**
     * Sticky A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sticky(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent->sticky = $torrent->sticky == 0 ? '1' : '0';
        $torrent->save();

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Sticky Status Has Been Adjusted!');
    }

    /**
     * 100% Freeleech A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantFL(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent_url = hrefTorrent($torrent);

        if ($torrent->free == 0) {
            $torrent->free = '1';

            $this->chat->systemMessage(
                sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted 100%% FreeLeech! Grab It While You Can! :fire:', $torrent_url, $torrent->name)
            );
        } else {
            $torrent->free = '0';

            $this->chat->systemMessage(
                sprintf('Ladies and Gents, [url=%s]%s[/url] has been revoked of its 100%% FreeLeech! :poop:', $torrent_url, $torrent->name)
            );
        }

        $torrent->save();

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent FL Has Been Adjusted!');
    }

    /**
     * Feature A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantFeatured(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($torrent->featured == 0) {
            $torrent->free = '1';
            $torrent->doubleup = '1';
            $torrent->featured = '1';
            $torrent->save();

            $featured = new FeaturedTorrent();
            $featured->user_id = $user->id;
            $featured->torrent_id = $torrent->id;
            $featured->save();

            $torrent_url = hrefTorrent($torrent);
            $profile_url = hrefProfile($user);
            $this->chat->systemMessage(
                sprintf('Ladies and Gents, [url=%s]%s[/url] has been added to the Featured Torrents Slider by [url=%s]%s[/url]! Grab It While You Can! :fire:', $torrent_url, $torrent->name, $profile_url, $user->username)
            );

            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withSuccess('Torrent Is Now Featured!');
        }

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withErrors('Torrent Is Already Featured!');
    }

    /**
     * Double Upload A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantDoubleUp(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent_url = hrefTorrent($torrent);

        if ($torrent->doubleup == 0) {
            $torrent->doubleup = '1';

            $this->chat->systemMessage(
                sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted Double Upload! Grab It While You Can! :fire:', $torrent_url, $torrent->name)
            );
        } else {
            $torrent->doubleup = '0';
            $this->chat->systemMessage(
                sprintf('Ladies and Gents, [url=%s]%s[/url] has been revoked of its Double Upload! :poop:', $torrent_url, $torrent->name)
            );
        }
        $torrent->save();

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent DoubleUpload Has Been Adjusted!');
    }

    /**
     * Reseed Request A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reseedTorrent(Request $request, $id)
    {
        $appurl = config('app.url');
        $user = $request->user();
        $torrent = Torrent::findOrFail($id);
        $reseed = History::where('info_hash', '=', $torrent->info_hash)->where('active', '=', 0)->get();

        if ($torrent->seeders <= 2) {
            // Send Notification
            foreach ($reseed as $r) {
                User::find($r->user_id)->notify(new NewReseedRequest($torrent));
            }

            $torrent_url = hrefTorrent($torrent);
            $profile_url = hrefProfile($user);

            $this->chat->systemMessage(
                sprintf('Ladies and Gents, a reseed request was just placed on [url=%s]%s[/url] can you help out :question:', $torrent_url, $torrent->name)
            );

            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withSuccess('A notification has been sent to all users that downloaded this torrent along with original uploader!');
        }

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withErrors('This torrent doesnt meet the rules for a reseed request.');
    }

    /**
     * Use Freeleech Token On A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function freeleechToken(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $active_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();

        if ($user->fl_tokens >= 1 && !$active_token) {
            $token = new FreeleechToken();
            $token->user_id = $user->id;
            $token->torrent_id = $torrent->id;
            $token->save();

            $user->fl_tokens -= '1';
            $user->save();

            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withSuccess('You Have Successfully Activated A Freeleech Token For This Torrent!');
        }

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withErrors('You Dont Have Enough Freeleech Tokens Or Already Have One Activated On This Torrent.');
    }
}
