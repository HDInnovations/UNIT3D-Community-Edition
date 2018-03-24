<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Http\Controllers;

use App\Category;
use App\Rss;
use App\Torrent;
use App\User;

/**
 * File upload management
 *
 *
 */
class RssController extends Controller
{
    private $userID = 0;

    private function auth($passkey)
    {
        $id = User::select('id')->where('rsskey', $passkey)->first();

        if ($user) {
            $this->userID = $id;
            return true;
        }

        return false;
    }

    private function getUserData()
    {
        $catArray = Rss::select('category')->where('user_id', $this->userID)->first();

        if ($catArray) {
            return explode(',', $catArray);
        }

        return [];
    }

    private function getTorrents()
    {
        $catArray = $this->getUserData();

        $torrents = Torrent::select('id', 'name', 'slug', 'size', 'seeders', 'leechers', 'info_hash', 'created_at')
            ->whereIn('category', $catArray)
            ->with('category')
            ->latest()
            ->take(25)
            ->toArray();

        return $torrents;
    }

    public function getData($passkey)
    {
        if ($this->auth($passkey)) {
            $torrents = $this->getTorrents();

            return view('rss.default', ['passkey' => $passkey, 'torrents' => $torrents])->header('Content-Type', 'text/xml');
        }

        return abort(404);
    }

    public function download($passkey, $id)
    {
        if ($this->auth($passkey)) {
        }

        return abort(404);
    }

    public function setView()
    {
        redirect();
    }

    public function getView()
    {
        $user = auth()->user();

        $rssData = RSS::select('category')->where('user_id', $user->id)->first();

        $category = Category::select('id', 'name')->get();
    }
}
