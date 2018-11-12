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
use App\PersonalFreeleech;

class HomeController extends Controller
{
    /**
     * Home Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        // Authorized User
        $user = auth()->user();

        // Latest Articles/News Block
        $articles = Article::latest()->take(1)->get();

        // Latest Torrents Block
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();

        $newest = Torrent::with(['user', 'category'])
            ->withCount(['thanks', 'comments'])
            ->latest()
            ->take(5)
            ->get();

        $seeded = Torrent::with(['user', 'category'])
            ->withCount(['thanks', 'comments'])
            ->latest('seeders')
            ->take(5)
            ->get();

        $leeched = Torrent::with(['user', 'category'])
            ->withCount(['thanks', 'comments'])
            ->latest('leechers')
            ->take(5)
            ->get();

        $dying = Torrent::with(['user', 'category'])
            ->withCount(['thanks', 'comments'])
            ->where('seeders', '=', 1)
            ->where('times_completed', '>=', 1)
            ->latest('leechers')
            ->take(5)
            ->get();

        $dead = Torrent::with(['user', 'category'])
            ->withCount(['thanks', 'comments'])
            ->where('seeders', '=', 0)
            ->latest('leechers')
            ->take(5)
            ->get();

        // Latest Topics Block
        $topics = Topic::latest()->take(5)->get();

        // Latest Posts Block
        $posts = Post::latest()->take(5)->get();

        // Online Block
        $users = User::with(['group' => function ($query) {
            $query->select(['id', 'name', 'color', 'effect', 'icon', 'position']);
        }])->select(['id', 'username', 'hidden', 'group_id'])->oldest('username')->get();
        $groups = Group::select(['name', 'color', 'effect', 'icon'])->oldest('position')->get();

        // Featured Torrents
        $featured = FeaturedTorrent::with('torrent')->get();

        // Latest Poll
        $poll = Poll::latest()->first();

        return view('home.home', [
            'user' => $user,
            'personal_freeleech' => $personal_freeleech,
            'users' => $users,
            'groups' => $groups,
            'articles' => $articles,
            'newest' => $newest,
            'seeded' => $seeded,
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
