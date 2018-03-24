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
use App\Article;
use App\Group;
use App\Topic;
use App\Torrent;
use App\User;
use App\Shoutbox;
use App\Post;
use App\FeaturedTorrent;
use App\Mail\Contact;
use \Toastr;

class HomeController extends Controller
{
    /**
     * Home page
     *
     * @access public
     * @return View home.home
     */
    public function home()
    {
        // Latest Articles Block
        $articles = Article::latest()->take(1)->get();      // Fetch latest articles

        // Latest Torrents Block
        $torrents = Torrent::where('sd', 0)->latest()->take(5)->get();     // Fetch latest torrents
        $best = Torrent::where('sd', 0)->latest('seeders')->take(5)->get();              // Fetch Top Seeded Torrents
        $leeched = Torrent::where('sd', 0)->latest('leechers')->take(5)->get();      // Fetch Top Leeched Torrents
        $dying = Torrent::where('sd', 0)->where('seeders', 1)->where('times_completed', '>=', '1')->latest('leechers')->take(5)->get();     // Fetch Top Dying Torrents
        $dead = Torrent::where('sd', 0)->where('seeders', 0)->latest('leechers')->take(5)->get();     // Fetch Top Dead Torrents

        // Latest Topics Block
        $topics = Topic::latest()->take(5)->get();     // Fetch latest topics

        // Latest Posts Block
        $posts = Post::latest()->take(5)->get();     // Fetch Latest Forum Posts

        //ShoutBox Block
        $shoutboxMessages = ShoutboxController::getMessages()['data'];

        //Online Block
        $user = User::oldest('username')->get();
        $groups = Group::oldest('position')->get();

        //Featured Torrents
        $featured = FeaturedTorrent::with('torrent')->get();

        return view('home.home', ['user' => $user, 'groups' => $groups, 'articles' => $articles, 'torrents' => $torrents,
            'best' => $best, 'dying' => $dying, 'leeched' => $leeched, 'dead' => $dead, 'topics' => $topics, 'posts' => $posts,
            'articles' => $articles, 'shoutboxMessages' => $shoutboxMessages, 'featured' => $featured]);
    }

    /**
     * Contact page, send an email to admins
     *
     * @access public
     * @return View home.contact
     */
    public function contact(Request $request)
    {
        // Fetch owner account
        $user = User::where('id', 3)->first();

        if ($request->isMethod('POST')) {
            $input = $request->all();
            Mail::to($user->email, $user->username)->send(new Contact($input));
            Toastr::success('Your Message Was Succefully Sent!', 'Yay!', ['options']);
        }

        return view('home.contact');
    }
}
