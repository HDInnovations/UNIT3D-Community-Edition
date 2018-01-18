<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Bookmark;
use App\Category;
use App\Client;
use App\History;
use App\Shoutbox;
use App\Torrent;
use App\TorrentFile;
use App\Type;
use App\Peer;
use App\Page;
use App\PrivateMessage;
use App\Warning;
use App\User;
use App\BonTransactions;
use App\FeaturedTorrent;
use App\PersonalFreeleech;
use App\FreeleechToken;

use App\Achievements\UserMadeUpload;
use App\Achievements\UserMade25Uploads;
use App\Achievements\UserMade50Uploads;
use App\Achievements\UserMade100Uploads;
use App\Achievements\UserMade200Uploads;
use App\Achievements\UserMade300Uploads;
use App\Achievements\UserMade400Uploads;
use App\Achievements\UserMade500Uploads;
use App\Achievements\UserMade600Uploads;
use App\Achievements\UserMade700Uploads;
use App\Achievements\UserMade800Uploads;
use App\Achievements\UserMade900Uploads;

use App\Helpers\TorrentViewHelper;

use App\Repositories\FacetedRepository;

use App\Services\Bencode;
use App\Services\TorrentTools;
use App\Services\FanArt;

use Carbon\Carbon;
use Cache;
use Decoda\Decoda;
use \Toastr;

use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

/**
 * Torrent Management
 *
 *
 */
class TorrentController extends Controller
{

    /**
     * @var FacetedRepository
     */
    private $repository;

    public function __construct(FacetedRepository $repository)
    {
        $this->repository = $repository;
        View::share('pages', Page::all());
    }

    /**
     * Poster Torrent Search
     *
     * @access public
     * @return View torrent.poster
     *
     */
    public function posterSearch()
    {
        $user = Auth::user();
        $order = explode(":", Request::get('order'));
        $search = Request::get('search');
        $torrents = Torrent::where([
            ['name', 'like', '%' . Request::get('name') . '%'],
            ['category_id', '=', Request::get('category_id')],
            ['type', '=', Request::get('type')],
        ])->orderBy($order[0], $order[1])->paginate(25);

        $torrents->setPath('?name=' . Request::get('name') . '&category_id=' . Request::get('category_id') . '&type=' . Request::get('type') . '&order=' . $order[0] . '%3A' . $order[1]);

        return view('torrent.poster', ['torrents' => $torrents, 'user' => $user, 'categories' => Category::all(), 'types' => Type::all()]);
    }

    /**
     * Bump A Torrent
     *
     * @access public
     * @return View torrent.torrent
     *
     */
    public function bumpTorrent($slug, $id)
    {
        if (Auth::user()->group->is_modo) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            $torrent->created_at = Carbon::now();
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . Auth::user()->username . " has bumped " . $torrent->name . " .");

            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::info('Torrent Has Been Bumped To Top Successfully!', 'Info', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Bookmark a particular torrent
     *
     * @param  Torrent $torrent
     * @return Response
     */
    public function bookmark($id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        if (Auth::user()->hasBookmarked($torrent->id)) {
            return back()->with(Toastr::error('Torrent has already been bookmarked.', 'Error', ['options']));
        } else {
            Auth::user()->bookmarks()->attach($torrent->id);
            return back()->with(Toastr::success('Torrent Has Been Bookmarked Successfully!', 'Yay', ['options']));
        }
    }

    /**
     * Sticky Torrent
     *
     * @access public
     * @param $slug Slug
     * @param $id Id
     *
     */
    public function sticky($slug, $id)
    {
        if (Auth::user()->group->is_modo) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            if ($torrent->sticky == 0) {
                $torrent->sticky = "1";
            } else {
                $torrent->sticky = "0";
            }
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . Auth::user()->username . " has stickied " . $torrent->name . " .");

            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::info('Torrent Sticky Status Has Been Adjusted!', 'Info', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Upload A Torrent
     *
     * @access public
     * @return View torrent.upload
     *
     */
    public function upload()
    {
        // Current user is the logged in user
        $user = Auth::user();
        $parsedContent = null;
        // Preview The Post
        if (Request::getMethod() == 'POST' && Request::get('preview') == true) {
            $code = new Decoda(Request::get('description'));
            $code->defaults();
            $code->removeHook('Censor');
            $code->setXhtml(false);
            $code->setStrict(false);
            $code->setLineBreaks(true);
            $parsedContent = $code->parse();
        }
        // Post and Upload
        if (Request::getMethod() == 'POST' && Request::get('post') == true) {
            // No torrent file uploaded OR an Error has occurred
            if (Request::hasFile('torrent') == false) {
                Toastr::warning('You Must Provide A Torrent File For Upload!', 'Error!', ['options']);
                return view('torrent.upload', ['categories' => Category::all(), 'types' => Type::all()->sortBy('position'), 'user' => $user]);
            } elseif (Request::file('torrent')->getError() != 0 && Request::file('torrent')->getClientOriginalExtension() != 'torrent') {
                Toastr::warning('A Error Has Occured!', 'Error!', ['options']);
                return view('torrent.upload', ['categories' => Category::all(), 'types' => Type::all()->sortBy('position'), 'user' => $user]);
            }
            // Deplace and decode the torrent temporarily
            TorrentTools::moveAndDecode(Request::file('torrent'));
            // Array decoded from torrent
            $decodedTorrent = TorrentTools::$decodedTorrent;
            // Tmp filename
            $fileName = TorrentTools::$fileName;
            // Torrent Info
            $info = Bencode::bdecode_getinfo(getcwd() . '/files/torrents/' . $fileName, true);
            // Find the right category
            $category = Category::findOrFail(Request::get('category_id'));
            // Create the torrent (DB)
            $name = Request::get('name');
            $torrent = new Torrent([
                'name' => $name,
                'slug' => str_slug($name),
                'description' => Request::get('description'),
                'mediainfo' => Request::get('mediainfo'),
                'info_hash' => $info['info_hash'],
                'file_name' => $fileName,
                'num_file' => $info['info']['filecount'],
                'announce' => $decodedTorrent['announce'],
                'size' => $info['info']['size'],
                'nfo' => (Request::hasFile('nfo')) ? TorrentTools::getNfo(Request::file('nfo')) : '',
                'category_id' => $category->id,
                'user_id' => $user->id,
                'imdb' => Request::get('imdb'),
                'tvdb' => Request::get('tvdb'),
                'tmdb' => Request::get('tmdb'),
                'mal' => Request::get('mal'),
                'type' => Request::get('type'),
                'anon' => Request::get('anonymous'),
                'stream' => Request::get('stream'),
                'sd' => Request::get('sd'),
            ]);
            // Validation
            $v = Validator::make($torrent->toArray(), $torrent->rules);
            if ($v->fails()) {
                if (file_exists(getcwd() . '/files/torrents/' . $fileName)) {
                    unlink(getcwd() . '/files/torrents/' . $fileName);
                }
                Toastr::warning('Did You Fill In All The Fields? If so then torrent hash is already on site. Dupe upload attempt was found.', 'Error!', ['options']);
            } else {
                // Save The Torrent
                $torrent->save();
                // Auto Shout and Achievements
                $user->unlock(new UserMadeUpload(), 1);
                $user->addProgress(new UserMade25Uploads(), 1);
                $user->addProgress(new UserMade50Uploads(), 1);
                $user->addProgress(new UserMade100Uploads(), 1);
                $user->addProgress(new UserMade200Uploads(), 1);
                $user->addProgress(new UserMade300Uploads(), 1);
                $user->addProgress(new UserMade400Uploads(), 1);
                $user->addProgress(new UserMade500Uploads(), 1);
                $user->addProgress(new UserMade600Uploads(), 1);
                $user->addProgress(new UserMade700Uploads(), 1);
                $user->addProgress(new UserMade800Uploads(), 1);
                $user->addProgress(new UserMade900Uploads(), 1);
                if ($torrent->sd == 0) {
                    $appurl = env('APP_URL', 'http://unit3d.site');
                    if ($torrent->anon == 0) {
                        Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has uploaded [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] grab it now! :slight_smile:"]);
                        Cache::forget('shoutbox_messages');
                    } else {
                        Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "An anonymous user has uploaded [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] grab it now! :slight_smile:"]);
                        Cache::forget('shoutbox_messages');
                    }
                }
                // check for trusted user and update torrent
                if ($user->group->is_trusted) {
                    Torrent::approve($torrent->id);
                }

                // Count and save the torrent number in this category
                $category->num_torrent = Torrent::where('category_id', '=', $category->id)->count();
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

                // Activity Log
                \LogActivity::addToLog("Member " . $user->username . " has uploaded " . $torrent->name . " .");

                return Redirect::route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::success('Your torrent file is ready to be downloaded and seeded!', 'Yay!', ['options']));
            }
        }
        return view('torrent.upload', ['categories' => Category::all(), 'types' => Type::all()->sortBy('position'), 'user' => $user, 'parsedContent' => $parsedContent]);
    }


    /**
     * Displays the torrent list
     *
     * @access public
     * @return page.torrents
     */
    public function torrents()
    {
        $user = Auth::user();
        $torrents = Torrent::query();
        $alive = Torrent::where('seeders', '>=', 1)->count();
        $dead = Torrent::where('seeders', '=', 0)->count();
        $repository = $this->repository;
        return view('torrent.torrents', compact('repository', 'torrents', 'user', 'alive', 'dead'));
    }

    public function faceted(IlluminateRequest $request, Torrent $torrent)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $username = $request->get('username');
        $uploader = User::where('username', '=', $username)->pluck('id');
        $imdb = $request->get('imdb');
        $tvdb = $request->get('tvdb');
        $tmdb = $request->get('tmdb');
        $mal = $request->get('mal');
        $categories = $request->get('categories');
        $types = $request->get('types');
        $freeleech = $request->get('freeleech');
        $doubleupload = $request->get('doubleupload');
        $featured = $request->get('featured');
        $stream = $request->get('stream');
        $highspeed = $request->get('highspeed');
        $sd = $request->get('sd');
        $alive = $request->get('alive');
        $dying = $request->get('dying');
        $dead = $request->get('dead');

        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%' . $term . '%';
        }

        $torrent = $torrent->newQuery();

        if ($request->has('search')) {
            $torrent->where('name', 'like', $search);
        }

        if ($request->has('username')) {
            $torrent->where('anon', 0)->where('user_id', $uploader);
        }

        if ($request->has('imdb')) {
            $torrent->where('imdb', $imdb);
        }

        if ($request->has('tvdb')) {
            $torrent->where('tvdb', $tvdb);
        }

        if ($request->has('tmdb')) {
            $torrent->where('tmdb', $tmdb);
        }

        if ($request->has('mal')) {
            $torrent->where('mal', $mal);
        }

        if ($request->has('categories')) {
            $torrent->whereIn('category_id', $categories);
        }

        if ($request->has('types')) {
            $torrent->whereIn('type', $types);
        }

        if ($request->has('freeleech')) {
            $torrent->where('free', $freeleech);
        }

        if ($request->has('doubleupload')) {
            $torrent->where('doubleup', $doubleupload);
        }

        if ($request->has('featured')) {
            $torrent->where('featured', $featured);
        }

        if ($request->has('stream')) {
            $torrent->where('stream', $stream);
        }

        if ($request->has('highspeed')) {
            $torrent->where('highspeed', $highspeed);
        }

        if ($request->has('sd')) {
            $torrent->where('sd', $sd);
        }

        if ($request->has('alive')) {
            $torrent->where('seeders', '>=', $alive);
        }

        if ($request->has('dying')) {
            $torrent->where('seeders', $dying)->where('times_completed', '>=', 3);
        }

        if ($request->has('dead')) {
            $torrent->where('seeders', $dead);
        }

        // pagination query starts
        $rows = $torrent->count();

        if ($request->has('page')) {
            $page = $request->get('page');
            $qty = $request->get('qty');
            $torrent->skip(($page - 1) * $qty);
            $active = $page;
        } else {
            $active = 1;
        }

        if ($request->has('qty')) {
            $qty = $request->get('qty');
            $torrent->take($qty);
        } else {
            $qty = 25;
            $torrent->take($qty);
        }
        // pagination query ends

        if ($request->has('sorting')) {
            $sorting = $request->get('sorting');
            $order = $request->get('direction');
            $torrent->orderBy($sorting, $order);
        }

        $listings = $torrent->get();

        $helper = new TorrentViewHelper();
        $result = $helper->view($listings);

        return ['result' => $result, 'rows' => $rows, 'qty' => $qty, 'active' => $active];
    }

    /**
     * Display The Torrent
     *
     * @access public
     * @param $slug
     * @param $id
     *
     */
    public function torrent($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $similar = Torrent::where('imdb', '=', $torrent->imdb)->where('status', '=', 1)->orderBy('seeders', 'DESC')->get();
        $uploader = $torrent->user;
        $user = Auth::user();
        $freeleech_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $comments = $torrent->comments()->orderBy('created_at', 'DESC')->paginate(6);
        $thanks = $torrent->thanks()->count();
        $total_tips = BonTransactions::where('torrent_id', '=', $id)->sum('cost');
        $user_tips = BonTransactions::where('torrent_id', '=', $id)->where('sender', '=', Auth::user()->id)->sum('cost');
        $last_seed_activity = History::where('info_hash', '=', $torrent->info_hash)->where('seeder', '=', 1)->orderBy('updated_at', 'DESC')->first();
        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));

        if ($torrent->category_id == 2) {
            $movie = $client->scrape('tv', 'tt' . $torrent->imdb);
        } else {
            $movie = $client->scrape('movie', 'tt' . $torrent->imdb);
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

        return view('torrent.torrent', ['torrent' => $torrent, 'comments' => $comments, 'thanks' => $thanks, 'user' => $user, 'similar' => $similar, 'personal_freeleech' => $personal_freeleech, 'freeleech_token' => $freeleech_token,
            'movie' => $movie, 'total_tips' => $total_tips, 'user_tips' => $user_tips, 'client' => $client, 'featured' => $featured, 'general' => $general, 'general_crumbs' => $general_crumbs, 'video_crumbs' => $video_crumbs, 'audio_crumbs' => $audio_crumbs, 'text_crumbs' => $text_crumbs,
            'video' => $video, 'audio' => $audio, 'subtitle' => $subtitle, 'settings' => $settings, 'uploader' => $uploader, 'last_seed_activity' => $last_seed_activity]);
    }

    /**
     * Peers
     *
     * @access public
     *
     */
    public function peers($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $peers = Peer::where('torrent_id', '=', $id)->orderBy('seeder', 'DESC')->paginate(25); // list the peers
        return view('torrent.peers', ['torrent' => $torrent, 'peers' => $peers]);
    }

    /**
     * History
     *
     * @access public
     *
     */
    public function history($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $history = History::where('info_hash', '=', $torrent->info_hash)->orderBy('created_at', 'desc')->paginate(25);

        return view('torrent.history', ['torrent' => $torrent, 'history' => $history]);
    }

    /**
     * Grant Torrent FL
     *
     * @access public
     * @param $slug Slug
     * @param $id Id
     *
     */
    public function grantFL($slug, $id)
    {
        if (Auth::user()->group->is_modo) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            $appurl = env('APP_URL', 'http://unit3d.site');
            if ($torrent->free == 0) {
                $torrent->free = "1";
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Ladies and Gents, [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] has been granted 100% FreeLeech! Grab It While You Can! :fire:"]);
                Cache::forget('shoutbox_messages');
            } else {
                $torrent->free = "0";
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Ladies and Gents, [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] has been revoked of its 100% FreeLeech! :poop:"]);
                Cache::forget('shoutbox_messages');
            }
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . Auth::user()->username . " has granted freeleech on " . $torrent->name . " .");

            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::info('Torrent FL Has Been Adjusted!', 'Info', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Grant Torrent Featured
     *
     * @access public
     * @param $slug Slug
     * @param $id Id
     *
     */
    public function grantFeatured($slug, $id)
    {
        if (Auth::user()->group->is_modo) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            if ($torrent->featured == 0) {
                $torrent->free = "1";
                $torrent->doubleup = "1";
                $torrent->featured = "1";
                $featured = new FeaturedTorrent([
                    'user_id' => Auth::user()->id,
                    'torrent_id' => $torrent->id,
                ]);
                $featured->save();
                $appurl = env('APP_URL', 'http://unit3d.site');
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Ladies and Gents, [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url]
            has been added to the Featured Torrents Slider by [url={$appurl}/" . Auth::user()->username . "." . Auth::user()->id . "]" . Auth::user()->username . "[/url]! Grab It While You Can! :fire:"]);
                Cache::forget('shoutbox_messages');
            } else {
                return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('Torrent Is Already Featured!', 'Ummm', ['options']));
            }
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . Auth::user()->username . " has featured " . $torrent->name . " .");

            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::info('Torrent Is Now Featured!', 'Info', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Grant Double Upload
     *
     * @access public
     * @param $slug Slug
     * @param $id Id
     *
     */
    public function grantDoubleUp($slug, $id)
    {
        if (Auth::user()->group->is_modo) {
            $torrent = Torrent::withAnyStatus()->findOrFail($id);
            $appurl = env('APP_URL', 'http://unit3d.site');
            if ($torrent->doubleup == 0) {
                $torrent->doubleup = "1";
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Ladies and Gents, [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] has been granted Double Upload! Grab It While You Can! :fire:"]);
                Cache::forget('shoutbox_messages');
            } else {
                $torrent->doubleup = "0";
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Ladies and Gents, [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] has been revoked of its Double Upload! :poop:"]);
                Cache::forget('shoutbox_messages');
            }
            $torrent->save();

            // Activity Log
            \LogActivity::addToLog("Staff Member " . Auth::user()->username . " has granted double upload on " . $torrent->name . " .");

            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::info('Torrent DoubleUpload Has Been Adjusted!', 'Info', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Download Check
     *
     * @access public
     *
     */
    public function downloadCheck($slug, $id)
    {
        // Find the torrent in the database
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        // Grab Current User
        $user = Auth::user();

        return view('torrent.download_check', ['torrent' => $torrent, 'user' => $user]);
    }

    /**
     * Download torrent
     *
     * @access public
     * @param string $slug
     * @param int $id
     * @return file
     */
    public function download($slug, $id)
    {
        // Find the torrent in the database
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        // Grab Current User
        $user = Auth::user();

        // User's ratio is too low
        if ($user->getRatio() < config('other.ratio')) {
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::warning('Your Ratio Is To Low To Download!!!', 'Error!', ['options']));
        }

        // User's download rights are revoked
        if ($user->can_download == 0) {
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::warning('Your Download Rights Have Been Revoked!!!', 'Error!', ['options']));
        }

        // Torrent Status Is Rejected
        if ($torrent->isRejected()) {
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::warning('This Torrent Has Been Rejected By Staff', 'Error!', ['options']));
        }

        // Define the filename for the download
        $tmpFileName = $torrent->slug . '.torrent';

        // The torrent file exist ?
        if (!file_exists(getcwd() . '/files/torrents/' . $torrent->file_name)) {
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::warning('Torrent File Not Found! Please Report This Torrent!', 'Error!', ['options']));
        } else {
            // Delete the last torrent tmp file
            if (file_exists(getcwd() . '/files/tmp/' . $tmpFileName)) {
                unlink(getcwd() . '/files/tmp/' . $tmpFileName);
            }
        }
        // Get the content of the torrent
        $dict = Bencode::bdecode(file_get_contents(getcwd() . '/files/torrents/' . $torrent->file_name));
        if (Auth::check()) {
            // Set the announce key and add the user passkey
            $dict['announce'] = route('announce', ['passkey' => $user->passkey]);
            // Remove Other announce url
            unset($dict['announce-list']);
        } else {
            return redirect('/login');
        }

        $fileToDownload = Bencode::bencode($dict);
        file_put_contents(getcwd() . '/files/tmp/' . $tmpFileName, $fileToDownload);
        return Response::download(getcwd() . '/files/tmp/' . $tmpFileName);
    }

    /**
     * Reseed Request
     *
     * @access public
     * @param $id Id torrent
     */
    public function reseedTorrent($slug, $id)
    {
        $appurl = env('APP_URL', 'http://unit3d.site');
        $user = Auth::user();
        $torrent = Torrent::findOrFail($id);
        $reseed = History::where('info_hash', '=', $torrent->info_hash)->where('active', '=', 0)->get();
        if ($torrent->seeders <= 2) {
            Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Ladies and Gents, [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has requested a reseed on [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] can you help out :question:"]);
            Cache::forget('shoutbox_messages');
            foreach ($reseed as $pm) {
                $pmuser = new PrivateMessage();
                $pmuser->sender_id = 1;
                $pmuser->reciever_id = $pm->user_id;
                $pmuser->subject = "New Reseed Request!";
                $pmuser->message = "Some time ago, you downloaded: [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url]
                                        Now, it has no seeds, and " . $user->username . " would still like to download it.
                                        If you still have this torrent in storage, please consider reseeding it! Thanks!
                                        [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]";
                $pmuser->save();
            }
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::info('A PM has been sent to all users that downloaded this torrent along with original uploader!', 'Info', ['options']));
        } else {
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('This torrent doesnt meet the requirments for a reseed request.', 'Whoops!', ['options']));
        }
    }

    /**
     * Poster View
     *
     *
     * @access public
     * @return view::make poster.poster
     */
    public function poster()
    {
        $user = Auth::user();
        $torrents = Torrent::orderBy('created_at', 'DESC')->paginate(20);
        return view('torrent.poster', ['user' => $user, 'torrents' => $torrents, 'categories' => Category::all(), 'types' => Type::all()]);
    }

    /**
     * Edite un torrent
     *
     * @access public
     * @param $slug Slug du torrent
     * @param $id Id du torrent
     */
    public function edit($slug, $id)
    {
        $user = Auth::user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);


        if ($user->group->is_modo || $user->id == $torrent->user_id) {
            if (Request::isMethod('post')) {
                $name = Request::get('name');
                $imdb = Request::get('imdb');
                $tvdb = Request::get('tvdb');
                $tmdb = Request::get('tmdb');
                $mal = Request::get('mal');
                $category = Request::get('category_id');
                $type = Request::get('type');
                $anon = Request::get('anonymous');
                $stream = Request::get('stream');
                $sd = Request::get('sd');

                $torrent->name = $name;
                $torrent->imdb = $imdb;
                $torrent->tvdb = $tvdb;
                $torrent->tmdb = $tmdb;
                $torrent->mal = $mal;
                $torrent->category_id = $category;
                $torrent->type = $type;
                $torrent->description = Request::get('description');
                $torrent->mediainfo = Request::get('mediainfo');
                $torrent->anon = $anon;
                $torrent->stream = $stream;
                $torrent->sd = $sd;
                $torrent->save();

                // Activity Log
                \LogActivity::addToLog("Staff Member " . $user->username . " has edited torrent " . $torrent->name . " .");

                return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::success('Succesfully Edited!!!', 'Yay!', ['options']));
            } else {
                return view('torrent.edit_tor', ['categories' => Category::all(), 'types' => Type::all()->sortBy('position'), 'tor' => $torrent]);
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Delete torrent
     *
     * @access public
     * @param $id Id torrent
     */
    public function deleteTorrent($id)
    {
        $user = Auth::user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $message = Request::get('message');
        if ($user->group->is_modo || $user->id == $torrent->user_id) {
            Peer::where('torrent_id', '=', $id)->delete();
            History::where('info_hash', '=', $torrent->info_hash)->delete();
            Warning::where('id', '=', $id)->delete();
            TorrentFile::where('torrent_id', '=', $id)->delete();
            if ($torrent->featured == 1) {
                FeaturedTorrent::where('torrent_id', '=', $id)->delete();
            }
            Torrent::where('id', '=', $id)->delete();
            PrivateMessage::create(['sender_id' => 1, 'reciever_id' => $torrent->user_id, 'subject' => "Your Torrent Has Been Deleted!", 'message' => $torrent->name . " Has Been Deleted From Our Site. $message"]);
            // Activity Log
            \LogActivity::addToLog("Member " . $user->username . " has deleted torrent " . $torrent->name . " .");

            return redirect('/torrents')->with(Toastr::info('Torrent Has Been Deleted!', 'Attention!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Use Freeleech Token
     *
     * @access public
     * @param $id Id torrent
     */
    public function freeleechToken($slug, $id)
    {
        $user = Auth::user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $active_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        if ($user->fl_tokens >= 1 && !$active_token) {
            $token = new FreeleechToken();
            $token->user_id = $user->id;
            $token->torrent_id = $torrent->id;
            $token->save();

            $user->fl_tokens -= "1";
            $user->save();

            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::success('You Have Successfully Activated A Freeleech Token For This Torrent!', 'Yay', ['options']));
        } else {
            return Redirect::route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('You Dont Have Enough Freeleech Tokens Or Already Have One Activated On This Torrent.', 'Whoops!', ['options']));
        }
    }
}
