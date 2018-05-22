<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use \DB;
use App\Category;
use App\History;
use App\Torrent;
use App\TorrentFile;
use App\Type;
use App\Peer;
use App\Page;
use App\PrivateMessage;
use App\TorrentRequest;
use App\Warning;
use App\User;
use App\BonTransactions;
use App\FeaturedTorrent;
use App\PersonalFreeleech;
use App\FreeleechToken;
use App\Helpers\TorrentHelper;
use App\Helpers\MediaInfo;
use App\Repositories\TorrentFacetedRepository;
use App\Services\Bencode;
use App\Services\TorrentTools;
use App\Bots\IRCAnnounceBot;
use Carbon\Carbon;
use Decoda\Decoda;
use \Toastr;

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

    public function __construct(TorrentFacetedRepository $faceted, ChatRepository $chat)
    {
        $this->faceted = $faceted;
        $this->chat = $chat;
        view()->share('pages', Page::all());
    }

    /**
     * Displays Torrent List View
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function torrents()
    {
        $user = auth()->user();
        $torrents = Torrent::query();
        $alive = Torrent::where('seeders', '>=', 1)->count();
        $dead = Torrent::where('seeders', 0)->count();
        $repository = $this->faceted;

        return view('torrent.torrents', ['repository' => $repository, 'torrents' => $torrents, 'user' => $user,
            'alive' => $alive, 'dead' => $dead
        ]);
    }

    /**
     * Displays Torrent Cards View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cardsLayout()
    {
        $user = auth()->user();
        $torrents = Torrent::latest()->paginate(33);

        return view('torrent.cards', ['user' => $user, 'torrents' => $torrents]);
    }

    /**
     * Torrent Grouping Categories
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupingCategories()
    {
        $user = auth()->user();
        $categories = Category::where('meta', 1)->get()->sortBy('position');

        return view('torrent.grouping_categories', ['user' => $user, 'categories' => $categories]);
    }

    /**
     * Torrent Grouping
     *
     * @param $category_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupingLayout($category_id)
    {
        $user = auth()->user();
        $category = Category::where('id', $category_id)->first();

        $torrents = DB::table('torrents')
            ->select('*')
            ->join(DB::raw("(SELECT MAX(id) AS id FROM torrents WHERE category_id = {$category_id} GROUP BY torrents.imdb) AS unique_torrents"),
                function ($join) {
                    $join->on('torrents.id', '=', 'unique_torrents.id');
                })
            ->where('imdb', '!=', 0)
            ->orderBy('torrents.created_at', 'DESC')
            ->paginate(25);

        return view('torrent.grouping', ['user' => $user, 'torrents' => $torrents, 'category' => $category]);
    }

    /**
     * Torrent Grouping Results
     *
     * @param $category_id
     * @param $imdb
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupingResults($category_id, $imdb)
    {
        $user = auth()->user();
        $category = Category::where('id', $category_id)->first();
        $torrents = Torrent::where('category_id', $category_id)->where('imdb', $imdb)->latest()->get();

        return view('torrent.grouping_results', ['user' => $user, 'torrents' => $torrents, 'imdb' => $imdb,
            'category' => $category]);
    }

    /**
     * Uses Input's To Put Together A Search
     *
     * @param $request Request
     * @param $torrent Torrent
     * @return array
     */
    public function faceted(Request $request, Torrent $torrent)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $uploader = $request->input('uploader');
        $imdb = $request->input('imdb');
        $tvdb = $request->input('tvdb');
        $tmdb = $request->input('tmdb');
        $mal = $request->input('mal');
        $categories = $request->input('categories');
        $types = $request->input('types');
        $freeleech = $request->input('freeleech');
        $doubleupload = $request->input('doubleupload');
        $featured = $request->input('featured');
        $stream = $request->input('stream');
        $highspeed = $request->input('highspeed');
        $sd = $request->input('sd');
        $alive = $request->input('alive');
        $dying = $request->input('dying');
        $dead = $request->input('dead');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%' . $term . '%';
        }

        $usernames = explode(' ', $uploader);
        $uploader = '';
        foreach ($usernames as $username) {
            $uploader .= '%' . $username . '%';
        }

        $torrent = $torrent->newQuery();

        if ($request->has('search') && $request->input('search') != null) {
            $torrent->where('name', 'like', $search);
        }

        if ($request->has('uploader') && $request->input('uploader') != null) {
            $match = User::where('username', 'like', $uploader)->first();
            if (null === $match) {
                return ['result' => [], 'count' => 0];
            }
            $torrent->where('user_id', $match->id)->where('anon', 0);
        }

        if ($request->has('imdb') && $request->input('imdb') != null) {
            $torrent->where('imdb', $imdb);
        }

        if ($request->has('tvdb') && $request->input('tvdb') != null) {
            $torrent->where('tvdb', $tvdb);
        }

        if ($request->has('tmdb') && $request->input('tmdb') != null) {
            $torrent->where('tmdb', $tmdb);
        }

        if ($request->has('mal') && $request->input('mal') != null) {
            $torrent->where('mal', $mal);
        }

        if ($request->has('categories') && $request->input('categories') != null) {
            $torrent->whereIn('category_id', $categories);
        }

        if ($request->has('types') && $request->input('types') != null) {
            $torrent->whereIn('type', $types);
        }

        if ($request->has('freeleech') && $request->input('freeleech') != null) {
            $torrent->where('free', $freeleech);
        }

        if ($request->has('doubleupload') && $request->input('doubleupload') != null) {
            $torrent->where('doubleup', $doubleupload);
        }

        if ($request->has('featured') && $request->input('featured') != null) {
            $torrent->where('featured', $featured);
        }

        if ($request->has('stream') && $request->input('stream') != null) {
            $torrent->where('stream', $stream);
        }

        if ($request->has('highspeed') && $request->input('highspeed') != null) {
            $torrent->where('highspeed', $highspeed);
        }

        if ($request->has('sd') && $request->input('sd') != null) {
            $torrent->where('sd', $sd);
        }

        if ($request->has('alive') && $request->input('alive') != null) {
            $torrent->where('seeders', '>=', $alive);
        }

        if ($request->has('dying') && $request->input('dying') != null) {
            $torrent->where('seeders', $dying)->where('times_completed', '>=', 3);
        }

        if ($request->has('dead') && $request->input('dead') != null) {
            $torrent->where('seeders', $dead);
        }

        // pagination query starts
        $rows = $torrent->count();

        if ($request->has('page')) {
            $page = $request->input('page');
            $qty = $request->input('qty');
            $torrent->skip(($page - 1) * $qty);
            $active = $page;
        } else {
            $active = 1;
        }

        if ($request->has('qty')) {
            $qty = $request->input('qty');
            $torrent->take($qty);
        } else {
            $qty = 25;
            $torrent->take($qty);
        }
        // pagination query ends

        if ($request->has('sorting') && $request->input('sorting') != null) {
            $sorting = $request->input('sorting');
            $order = $request->input('direction');
            $torrent->orderBy($sorting, $order);
        }

        $listings = $torrent->get();
        $count = $torrent->count();

        $helper = new TorrentHelper();
        $result = $helper->view($listings);

        return ['result' => $result, 'rows' => $rows, 'qty' => $qty, 'active' => $active, 'count' => $count];
    }

    /**
     * Anonymize A Torrent Media Info
     *
     * @param $mediainfo
     * @return array
     */
    private static function anonymizeMediainfo($mediainfo)
    {
        if ($mediainfo === null) {
            return null;
        }
        $complete_name_i = strpos($mediainfo, "Complete name");
        if ($complete_name_i !== false) {
            $path_i = strpos($mediainfo, ": ", $complete_name_i);
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
     * Display The Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function torrent($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $uploader = $torrent->user;
        $user = auth()->user();
        $freeleech_token = FreeleechToken::where('user_id', $user->id)->where('torrent_id', $torrent->id)->first();
        $personal_freeleech = PersonalFreeleech::where('user_id', $user->id)->first();
        $comments = $torrent->comments()->latest()->paginate(6);
        $thanks = $torrent->thanks()->count();
        $total_tips = BonTransactions::where('torrent_id', $id)->sum('cost');
        $user_tips = BonTransactions::where('torrent_id', $id)->where('sender', auth()->user()->id)->sum('cost');
        $last_seed_activity = History::where('info_hash', $torrent->info_hash)->where('seeder', 1)->latest('updated_at')->first();

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
        if ($torrent->category_id == 2) {
            if ($torrent->tmdb || $torrent->tmdb != 0) {
                $movie = $client->scrape('tv', null, $torrent->tmdb);
            } else {
                $movie = $client->scrape('tv', 'tt' . $torrent->imdb);
            }
        } else {
            if ($torrent->tmdb || $torrent->tmdb != 0) {
                $movie = $client->scrape('movie', null, $torrent->tmdb);
            } else {
                $movie = $client->scrape('movie', 'tt' . $torrent->imdb);
            }
        }

        if ($torrent->featured == 1) {
            $featured = FeaturedTorrent::where('torrent_id', $id)->first();
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
            $parser = new \App\Helpers\MediaInfo;
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

        return view('torrent.torrent', ['torrent' => $torrent, 'comments' => $comments, 'thanks' => $thanks,
            'user' => $user, 'personal_freeleech' => $personal_freeleech, 'freeleech_token' => $freeleech_token,
            'movie' => $movie, 'total_tips' => $total_tips, 'user_tips' => $user_tips, 'client' => $client,
            'featured' => $featured, 'general' => $general, 'general_crumbs' => $general_crumbs,
            'video_crumbs' => $video_crumbs, 'audio_crumbs' => $audio_crumbs, 'text_crumbs' => $text_crumbs,
            'video' => $video, 'audio' => $audio, 'subtitle' => $subtitle, 'settings' => $settings,
            'uploader' => $uploader, 'last_seed_activity' => $last_seed_activity]);
    }

    /**
     * Torrent Edit Form
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($user->group->is_modo || $user->id == $torrent->user_id) {
            return view('torrent.edit_torrent', ['categories' => Category::all()->sortBy('position'),
                'types' => Type::all()->sortBy('position'), 'torrent' => $torrent
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Edit A Torrent
     *
     * @param $request Request
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($user->group->is_modo || $user->id == $torrent->user_id) {
            $torrent->name = $request->input('name');
            $torrent->slug = str_slug($torrent->name);
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

            $v = validator($torrent->toArray(), [
                'name' => 'required',
                'slug' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'imdb' => 'required|numeric',
                'tvdb' => 'required|numeric',
                'tmdb' => 'required|numeric',
                'mal' => 'required|numeric',
                'type' => 'required',
                'anon' => 'required',
                'stream' => 'required',
                'sd' => 'required'
            ]);

            if ($v->fails()) {
                return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                    ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
            } else {
                $torrent->save();

                if ($user->group->is_modo) {
                    // Activity Log
                    \LogActivity::addToLog("Staff Member {$user->username} has edited torrent, ID: {$torrent->id} NAME: {$torrent->name} .");
                } else {
                    // Activity Log
                    \LogActivity::addToLog("Member {$user->username} has edited torrent, ID: {$torrent->id} NAME: {$torrent->name} .");
                }

                return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                    ->with(Toastr::success('Successfully Edited!!!', 'Yay!', ['options']));
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Delete A Torrent
     *
     * @param $request Request
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteTorrent(Request $request)
    {
        $v = validator($request->all(), [
            'id' => "required|exists:torrents",
            'slug' => "required|exists:torrents",
            'message' => "required|alpha_dash|min:1"
        ]);

        if ($v) {
            $user = auth()->user();
            $id = $request->id;
            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            if ($user->group->is_modo || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()))) {
                $users = History::where('info_hash', $torrent->info_hash)->get();
                foreach ($users as $pm) {
                    $pmuser = new PrivateMessage();
                    $pmuser->sender_id = 1;
                    $pmuser->reciever_id = $pm->user_id;
                    $pmuser->subject = "Torrent Deleted!";
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
                $torrentRequest = TorrentRequest::where('filled_hash', $torrent->info_hash)->get();
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
                Peer::where('torrent_id', $id)->delete();
                History::where('info_hash', $torrent->info_hash)->delete();
                Warning::where('id', $id)->delete();
                TorrentFile::where('torrent_id', $id)->delete();
                if ($torrent->featured == 1) {
                    FeaturedTorrent::where('torrent_id', $id)->delete();
                }
                Torrent::withAnyStatus()->where('id', $id)->delete();

                return redirect('/torrents')
                    ->with(Toastr::success('Torrent Has Been Deleted!', 'Yay!', ['options']));
            }
        } else {
            $errors = "";
            foreach ($v->errors()->all() as $error) {
                $errors .= $error . "\n";
            }
            \Log::notice("Deletion of torrent failed due to: \n\n{$errors}");

            return redirect()->back()
                ->with(Toastr::error('Unable to delete Torrent', 'Error', ['options']));
        }
    }

    /**
     * Display Peers Of A Torrent
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function peers($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $peers = Peer::where('torrent_id', $id)->latest('seeder')->paginate(25);

        return view('torrent.peers', ['torrent' => $torrent, 'peers' => $peers]);
    }

    /**
     * Display History Of A Torrent
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $history = History::where('info_hash', $torrent->info_hash)->latest()->paginate(25);

        return view('torrent.history', ['torrent' => $torrent, 'history' => $history]);
    }

    /**
     * Torrent Upload Form
     *
     * @param $title
     * @param $imdb
     * @param $tmdb
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadForm($title = '', $imdb = 0, $tmdb = 0)
    {
        $user = auth()->user();

        return view('torrent.upload', ['categories' => Category::all()->sortBy('position'),
            'types' => Type::all()->sortBy('position'), 'user' => $user, 'title' => $title,
            'imdb' => str_replace('tt', '', $imdb), 'tmdb' => $tmdb
        ]);
    }

    /**
     * Upload A Torrent
     *
     * @param $request Request
     * @return Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $user = auth()->user();
        $requestFile = $request->file('torrent');

        if ($request->hasFile('torrent') == false) {
            return view('torrent.upload', ['categories' => Category::all()->sortBy('position'),
                'types' => Type::all()->sortBy('position'), 'user' => $user])
                ->with(Toastr::error('You Must Provide A Torrent File For Upload!', 'Whoops!', ['options']));
        } elseif ($requestFile->getError() != 0 && $requestFile->getClientOriginalExtension() != 'torrent') {
            return view('torrent.upload', ['categories' => Category::all()->sortBy('position'),
                'types' => Type::all()->sortBy('position'), 'user' => $user])
                ->with(Toastr::error('A Error Has Occurred!', 'Whoops!', ['options']));
        }

        // Deplace and decode the torrent temporarily
        TorrentTools::moveAndDecode($requestFile);
        // Array decoded from torrent
        $decodedTorrent = TorrentTools::$decodedTorrent;
        // Tmp filename
        $fileName = TorrentTools::$fileName;
        // Torrent Info
        $info = Bencode::bdecode_getinfo(getcwd() . '/files/torrents/' . $fileName, true);
        // Find the right category
        $category = Category::findOrFail($request->input('category_id'));

        // Create the torrent (DB)
        $torrent = new Torrent();
        $torrent->name = $request->input('name');
        $torrent->slug = str_slug($torrent->name);
        $torrent->description = $request->input('description');
        $torrent->mediainfo = self::anonymizeMediainfo($request->input('mediainfo'));
        $torrent->info_hash = $info['info_hash'];
        $torrent->file_name = $fileName;
        $torrent->num_file = $info['info']['filecount'];
        $torrent->announce = $decodedTorrent['announce'];
        $torrent->size = $info['info']['size'];
        $torrent->nfo = ($request->hasFile('nfo')) ? TorrentTools::getNfo($request->file('nfo')) : '';
        $torrent->category_id = $category->id;
        $torrent->user_id = $user->id;
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->type = $request->input('type');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');

        // Validation
        $v = validator($torrent->toArray(), [
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'info_hash' => 'required|unique:torrents',
            'file_name' => 'required',
            'num_file' => 'required|numeric',
            'announce' => 'required',
            'size' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
            'imdb' => 'required|numeric',
            'tvdb' => 'required|numeric',
            'tmdb' => 'required|numeric',
            'mal' => 'required|numeric',
            'type' => 'required',
            'anon' => 'required',
            'stream' => 'required',
            'sd' => 'required'
        ]);

        if ($v->fails()) {
            if (file_exists(getcwd() . '/files/torrents/' . $fileName)) {
                unlink(getcwd() . '/files/torrents/' . $fileName);
            }
            return redirect()->route('upload_form')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            // Save The Torrent
            $torrent->save();

            // Count and save the torrent number in this category
            $category->num_torrent = Torrent::where('category_id', $category->id)->count();
            $category->save();

            // Torrent Tags System
            /*foreach(explode(',', Input::get('tags')) as $k => $v)
            {

            }*/

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

            // check for trusted user and update torrent
            if ($user->group->is_trusted) {
                TorrentHelper::approveHelper($torrent->slug, $torrent->id);
                \LogActivity::addToLog("Member {$user->username} has uploaded torrent, ID: {$torrent->id} NAME: {$torrent->name} . \nThis torrent has been auto approved by the System.");
            } else {
                \LogActivity::addToLog("Member {$user->username} has uploaded torrent, ID: {$torrent->id} NAME: {$torrent->name} . \nThis torrent is pending approval from satff.");
            }

            return redirect()->route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('Your torrent file is ready to be downloaded and seeded!', 'Yay!', ['options']));
        }
    }

    /**
     * Download Check
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadCheck($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $user = auth()->user();

        return view('torrent.download_check', ['torrent' => $torrent, 'user' => $user]);
    }

    /**
     * Download A Torrent
     *
     * @param $slug
     * @param $id
     * @return TorrentFile
     */
    public function download($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $user = auth()->user();

        // User's ratio is too low
        if ($user->getRatio() < config('other.ratio')) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('Your Ratio Is To Low To Download!!!', 'Whoops!', ['options']));
        }

        // User's download rights are revoked
        if ($user->can_download == 0) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('Your Download Rights Have Been Revoked!!!', 'Whoops!', ['options']));
        }

        // Torrent Status Is Rejected
        if ($torrent->isRejected()) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('This Torrent Has Been Rejected By Staff', 'Whoops!', ['options']));
        }

        // Define the filename for the download
        $tmpFileName = $torrent->slug . '.torrent';

        // The torrent file exist ?
        if (!file_exists(getcwd() . '/files/torrents/' . $torrent->file_name)) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('Torrent File Not Found! Please Report This Torrent!', 'Error!', ['options']));
        } else {
            // Delete the last torrent tmp file
            if (file_exists(getcwd() . '/files/tmp/' . $tmpFileName)) {
                unlink(getcwd() . '/files/tmp/' . $tmpFileName);
            }
        }
        // Get the content of the torrent
        $dict = Bencode::bdecode(file_get_contents(getcwd() . '/files/torrents/' . $torrent->file_name));
        if (auth()->check()) {
            // Set the announce key and add the user passkey
            $dict['announce'] = route('announce', ['passkey' => $user->passkey]);
            // Remove Other announce url
            unset($dict['announce-list']);
        } else {
            return redirect('/login');
        }

        $fileToDownload = Bencode::bencode($dict);
        file_put_contents(getcwd() . '/files/tmp/' . $tmpFileName, $fileToDownload);
        return response()->download(getcwd() . '/files/tmp/' . $tmpFileName);
    }

    /**
     * Bump A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function bumpTorrent($slug, $id)
    {
        $user = auth()->user();

        if ($user->group->is_modo || $user->group->is_internal) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            $torrent->created_at = Carbon::now();
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . $user->username . " has bumped torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

            // Announce To Chat
            $torrent_url = hrefTorrent($torrent);
            $profile_url = hrefProfile($user);

            $this->chat->systemMessage(
                ":warning: Attention, [url={$torrent_url}]{$torrent->name}[/url] has been bumped to top by [url={$profile_url}]{$user->username}[/url]! It could use more seeds! :warning:"
            );

            // Announce To IRC
            if (config('irc-bot.enabled') == true) {
                $appname = config('app.name');
                $bot = new IRCAnnounceBot();
                $bot->message("#announce", "[" . $appname . "] User " . $user->username . " has bumped " . $torrent->name . " , it could use more seeds!");
                $bot->message("#announce", "[Category: " . $torrent->category->name . "] [Type: " . $torrent->type . "] [Size:" . $torrent->getSize() . "]");
                $bot->message("#announce", "[Link: $torrent_url]");
            }

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('Torrent Has Been Bumped To Top Successfully!', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Bookmark A Torrent
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function bookmark($id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if (auth()->user()->isBookmarked($torrent->id)) {
            return redirect()->back()
                ->with(Toastr::error('Torrent has already been bookmarked.', 'Whoops!', ['options']));
        } else {
            auth()->user()->bookmarks()->attach($torrent->id);
            return redirect()->back()
                ->with(Toastr::success('Torrent Has Been Bookmarked Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Unbookmark A Torrent
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function unBookmark($id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        auth()->user()->bookmarks()->detach($torrent->id);

        return redirect()->back()
            ->with(Toastr::success('Torrent Has Been Unbookmarked Successfully!', 'Yay!', ['options']));
    }

    /**
     * Sticky A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function sticky($slug, $id)
    {
        if (auth()->user()->group->is_modo || auth()->user()->group->is_internal) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            if ($torrent->sticky == 0) {
                $torrent->sticky = "1";
            } else {
                $torrent->sticky = "0";
            }
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . auth()->user()->username . " has stickied torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('Torrent Sticky Status Has Been Adjusted!', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * 100% Freeleech A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function grantFL($slug, $id)
    {
        $user = auth()->user();
        if ($user->group->is_modo || $user->group->is_internal) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            $torrent_url = hrefTorrent($torrent);

            if ($torrent->free == 0) {
                $torrent->free = "1";

                $this->chat->systemMessage(
                    "Ladies and Gents, [url={$torrent_url}]{$torrent->name}[/url] has been granted 100% FreeLeech! Grab It While You Can! :fire:"
                );
            } else {
                $torrent->free = "0";

                $this->chat->systemMessage(
                    "Ladies and Gents, [url={$torrent_url}]{$torrent->name}[/url] has been revoked of its 100% FreeLeech! :poop:"
                );
            }

            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . $user->username . " has granted freeleech on torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('Torrent FL Has Been Adjusted!', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Feature A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function grantFeatured($slug, $id)
    {
        $user = auth()->user();

        if ($user->group->is_modo || $user->group->is_internal) {

            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            if ($torrent->featured == 0) {
                $torrent->free = "1";
                $torrent->doubleup = "1";
                $torrent->featured = "1";
                $torrent->save();

                $featured = new FeaturedTorrent([
                    'user_id' => auth()->user()->id,
                    'torrent_id' => $torrent->id,
                ]);
                $featured->save();

                $torrent_url = hrefTorrent($torrent);
                $profile_url = hrefProfile($user);
                $this->chat->systemMessage(
                    "Ladies and Gents, [url={$torrent_url}]{$torrent->name}[/url] has been added to the Featured Torrents Slider by [url={$profile_url}]{$user->username}[/url]! Grab It While You Can! :fire:"
                );

                // Activity Log
                \LogActivity::addToLog("Staff Member " . auth()->user()->username . " has featured torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

                return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                    ->with(Toastr::success('Torrent Is Now Featured!', 'Yay!', ['options']));
            } else {
                return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                    ->with(Toastr::error('Torrent Is Already Featured!', 'Whoops!', ['options']));
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Double Upload A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function grantDoubleUp($slug, $id)
    {
        $user = auth()->user();

        if ($user->group->is_modo || $user->group->is_internal) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            $torrent_url = hrefTorrent($torrent);

            if ($torrent->doubleup == 0) {
                $torrent->doubleup = "1";

                $this->chat->systemMessage(
                    "Ladies and Gents, [url={$torrent_url}]{$torrent->name}[/url] has been granted Double Upload! Grab It While You Can! :fire:"
                );
            } else {
                $torrent->doubleup = "0";
                $this->chat->systemMessage(
                    "Ladies and Gents, [url={$torrent_url}]{$torrent->name}[/url] has been revoked of its Double Upload! :poop:"
                );
            }
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . $user->username . " has granted double upload on torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('Torrent DoubleUpload Has Been Adjusted!', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Reseed Request A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function reseedTorrent($slug, $id)
    {
        $appurl = config('app.url');
        $user = auth()->user();
        $torrent = Torrent::findOrFail($id);
        $reseed = History::where('info_hash', $torrent->info_hash)->where('active', 0)->get();
        if ($torrent->seeders <= 2) {
            // Send Private Messages
            foreach ($reseed as $pm) {
                $pmuser = new PrivateMessage();
                $pmuser->sender_id = 1;
                $pmuser->reciever_id = $pm->user_id;
                $pmuser->subject = "New Reseed Request!";
                $pmuser->message = "Some time ago, you downloaded: [url={$appurl}/torrents/{$torrent->slug}.{$torrent->id}]{$torrent->name}[/url]
                                    Now, it has no seeds, and {$user->username} would still like to download it.
                                    If you still have this torrent in storage, please consider reseeding it! Thanks!
                                    [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]";
                $pmuser->save();
            }

            $torrent_url = hrefTorrent($torrent);
            $profile_url = hrefProfile($user);

            $this->chat->systemMessage(
                "Ladies and Gents, [url={$profile_url}]{$user->username}[/url] has requested a reseed on [url={$torrent_url}]{$torrent->name}[/url] can you help out :question:"
            );

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has requested a reseed request on torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('A PM has been sent to all users that downloaded this torrent along with original uploader!', 'Yay!', ['options']));
        } else {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('This torrent doesnt meet the requirments for a reseed request.', 'Whoops!', ['options']));
        }
    }

    /**
     * Use Freeleech Token On A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function freeleechToken($slug, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $active_token = FreeleechToken::where('user_id', $user->id)->where('torrent_id', $torrent->id)->first();

        if ($user->fl_tokens >= 1 && !$active_token) {
            $token = new FreeleechToken();
            $token->user_id = $user->id;
            $token->torrent_id = $torrent->id;
            $token->save();

            $user->fl_tokens -= "1";
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member {$user->username} has used a freeleech token on torrent, ID: {$torrent->id} NAME: {$torrent->name} .");

            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::success('You Have Successfully Activated A Freeleech Token For This Torrent!', 'Yay!', ['options']));
        } else {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('You Dont Have Enough Freeleech Tokens Or Already Have One Activated On This Torrent.', 'Whoops!', ['options']));
        }
    }
}
