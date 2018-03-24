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

use Illuminate\Support\Facades\DB;
use App\Page;
use App\User;

class PageController extends Controller
{

    /**
     * Displays the requested page
     *
     *
     */
    public function page($slug, $id)
    {
        $page = Page::findOrFail($id);

        return view('page.page', ['page' => $page]);
    }

    /**
     * Staff Page
     *
     *
     */
    public function staff()
    {
        $staff = DB::table('users')->leftJoin('groups', 'users.group_id', '=', 'groups.id')->select('users.id', 'users.title', 'users.username', 'groups.name', 'groups.color', 'groups.icon')->where('groups.is_admin', 1)->orWhere('groups.is_modo', 1)->get();

        return view('page.staff', ['staff' => $staff]);
    }

    /**
     * Internal Page
     *
     *
     */
    public function internal()
    {
        $internal = DB::table('users')->leftJoin('groups', 'users.group_id', '=', 'groups.id')->select('users.id', 'users.title', 'users.username', 'groups.name', 'groups.color', 'groups.icon')->where('groups.is_internal', 1)->get();

        return view('page.internal', ['internal' => $internal]);
    }

    /**
     * Blacklist Page
     *
     *
     */
    public function blacklist()
    {
        $clients = config('client-blacklist.clients', []);
        $browsers = config('client-blacklist.browsers', []);

        return view('page.blacklist', ['clients' => $clients, 'browsers' => $browsers]);
    }

    /**
     * About Us Page
     *
     *
     */
    public function about()
    {
        return view('page.aboutus');
    }
}
