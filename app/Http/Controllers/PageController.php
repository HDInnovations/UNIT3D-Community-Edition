<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

final class PageController extends Controller
{
    /**
     * Show A Page.
     *
     * @param $id
     *
     * @return Factory|View
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);

        return view('page.page', ['page' => $page]);
    }

    /**
     * Show Staff Page.
     *
     * @return Factory|View
     */
    public function staff()
    {
        $staff = DB::table('users')->leftJoin('groups', 'users.group_id', '=', 'groups.id')->select(['users.id', 'users.title', 'users.username', 'groups.name', 'groups.color', 'groups.icon'])->where('groups.is_admin', 1)->orWhere('groups.is_modo', 1)->get();

        return view('page.staff', ['staff' => $staff]);
    }

    /**
     * Show Internals Page.
     *
     * @return Factory|View
     */
    public function internal()
    {
        $internal = DB::table('users')->leftJoin('groups', 'users.group_id', '=', 'groups.id')->select(['users.id', 'users.title', 'users.username', 'groups.name', 'groups.color', 'groups.icon'])->where('groups.is_internal', 1)->get();

        return view('page.internal', ['internal' => $internal]);
    }

    /**
     * Show Blacklist Page.
     *
     * @return Factory|View
     */
    public function blacklist()
    {
        $clients = config('client-blacklist.clients', []);
        $browsers = config('client-blacklist.browsers', []);

        return view('page.blacklist', ['clients' => $clients, 'browsers' => $browsers]);
    }

    /**
     * Show About Us Page.
     *
     * @return Factory|View
     */
    public function about()
    {
        return view('page.aboutus');
    }

    /**
     * Show Email Whitelist / Blacklist Page.
     *
     * @return Factory|View
     */
    public function emailList()
    {
        $whitelist = config('email-white-blacklist.allow', []);
        $blacklist = config('email-white-blacklist.block', []);

        return view('page.emaillist', ['whitelist' => $whitelist, 'blacklist' => $blacklist]);
    }
}
