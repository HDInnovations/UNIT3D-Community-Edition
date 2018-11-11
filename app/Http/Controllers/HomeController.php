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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Poll;
use App\Article;
use App\Group;
use App\Topic;
use App\Torrent;
use App\User;
use App\Post;
use App\FeaturedTorrent;
use \Toastr;

class HomeController extends Controller
{
    /**
     * Home Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        // Latest Articles/News Block
        $articles = Article::latest()->take(1)->get();

        // Latest Torrents Block
        $torrents = Torrent::with('category')->latest()->take(5)->get();
        $best = Torrent::with('category')->latest('seeders')->take(5)->get();
        $leeched = Torrent::with('category')->latest('leechers')->take(5)->get();
        $dying = Torrent::with('category')->where('seeders', 1)->where('times_completed', '>=', '1')->latest('leechers')->take(5)->get();
        $dead = Torrent::with('category')->where('seeders', 0)->latest('leechers')->take(5)->get();

        // Latest Topics Block
        $topics = Topic::latest()->take(5)->get();

        // Latest Posts Block
        $posts = Post::latest()->take(5)->get();

        // Online Block
        $users = User::with('group')->oldest('username')->get();
        $groups = Group::select(['name', 'color', 'effect', 'icon'])->oldest('position')->get();

        // Featured Torrents
        $featured = FeaturedTorrent::with('torrent')->get();

        // Latest Poll
        $poll = Poll::latest()->first();

        return view('home.home', [
            'users' => $users,
            'groups' => $groups,
            'articles' => $articles,
            'torrents' => $torrents,
            'best' => $best,
            'dying' => $dying,
            'leeched' => $leeched,
            'dead' => $dead,
            'topics' => $topics,
            'posts' => $posts,
            'featured' => $featured,
            'poll' => $poll
        ]);
    }
}
