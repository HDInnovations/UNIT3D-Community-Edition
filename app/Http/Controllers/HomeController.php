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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use App\Article;
use App\Comment;
use App\Group;
use App\History;
use App\Mail\Contact;
use App\Topic;
use App\Torrent;
use App\Category;
use App\Type;
use App\User;
use App\Peer;
use App\Shoutbox;
use App\Post;
use App\FeaturedTorrent;
use App\UserFreeleech;

use Cache;
use \Toastr;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('private');
    }

    /**
     * Home page
     *
     * @access public
     * @return View home.home
     */
    public function home()
    {
        // Latest Articles Block
        $articles = Article::orderBy('created_at', 'desc')->take(1)->get();      // Fetch latest articles

        // Latest Torrents Block
        $torrents = Torrent::where('sd', '=', 0)->orderBy('created_at', 'desc')->take(5)->get();     // Fetch latest torrents
        $best = Torrent::where('sd', '=', 0)->orderBy('seeders', 'desc')->take(5)->get();              // Fetch Top Seeded Torrents
        $leeched = Torrent::where('sd', '=', 0)->orderBy('leechers', 'desc')->take(5)->get();      // Fetch Top Leeched Torrents
        $dying = Torrent::where('sd', '=', 0)->where('seeders', '=', '1')->where('times_completed', '>=', '1')->orderBy('leechers', 'desc')->take(5)->get();     // Fetch Top Dying Torrents
        $dead = Torrent::where('sd', '=', 0)->where('seeders', '=', '0')->orderBy('leechers', 'desc')->take(5)->get();     // Fetch Top Dead Torrents

        // Latest Topics Block
        $topics = Topic::orderBy('created_at', 'desc')->take(5)->get();     // Fetch latest topics

        // Latest Posts Block
        $posts = Post::orderBy('created_at', 'desc')->take(5)->get();     // Fetch Latest Forum Posts

        //ShoutBox Block
        $shoutboxItems = Cache::remember('shoutbox_messages', 60, function () {
            return Shoutbox::orderBy('created_at', 'desc')->take(50)->get();
        });
        $shoutboxItems = $shoutboxItems->reverse();

        //Online Block
        $user = User::orderBy('username', 'asc')->get();
        $groups = Group::orderBy('position', 'asc')->get();

        //Featured Torrents
        $featured = FeaturedTorrent::with('torrent')->get();

        return view('home.home', ['user' => $user, 'groups' => $groups, 'articles' => $articles, 'torrents' => $torrents,
            'best' => $best, 'dying' => $dying, 'leeched' => $leeched, 'dead' => $dead, 'topics' => $topics, 'posts' => $posts,
            'articles' => $articles, 'shoutboxItems' => $shoutboxItems, 'featured' => $featured]);
    }

    /**
     * Search for torrents
     *
     * @access public
     * @return View page.torrents
     *
     */
    public function search()
    {
        $user = Auth::user();
        $search = Request::get('name');
        $terms = explode(' ', $search);
        $search = '';
        foreach ($terms as $term) {
            $search .= '%' . $term . '%';
        }
        $torrents = Torrent::where('name', 'like', $search)->orderBy('created_at', 'desc')->paginate(50);
        $personal_freeleech = UserFreeleech::where('user_id', '=', $user->id)->first();

        $torrents->setPath('?name=' . Request::get('name'));

        return view('home.search', ['torrents' => $torrents, 'user' => $user, 'personal_freeleech' => $personal_freeleech, 'categories' => Category::all(), 'types' => Type::all()]);
    }


    /**
     * Contact page, send an email to admins
     *
     * @access public
     * @return View home.contact
     */
    public function contact()
    {
        // Fetch admin group
        $group = Group::where('slug', '=', 'administrators')->first();
        // grab the admins
        $admins = User::where('group_id', '=', $group->id)->get();

        if (Request::getMethod() == 'POST') {
            $input = Request::all();
            // Send The Mail
            foreach ($admins as $user) {
                Mail::to($user->email, $user->username)->send(new Contact($input));
            }
            Toastr::success('Your Message Was Succefully Sent!', 'Success', ['options']);
        }

        return view('home.contact');
    }

    public function landing()
    {
        return View::make('landing.christmas');
    }

}
