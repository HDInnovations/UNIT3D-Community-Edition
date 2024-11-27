<?php

declare(strict_types=1);

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

use App\Models\BlacklistClient;
use App\Models\Group;
use App\Models\Internal;
use App\Models\Page;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PageControllerTest
 */
class PageController extends Controller
{
    /**
     * Display All Pages.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('page.index', [
            'pages' => Page::all(),
        ]);
    }

    /**
     * Show A Page.
     */
    public function show(Page $page): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('page.page', [
            'page' => $page,
        ]);
    }

    /**
     * Show Staff Page.
     */
    public function staff(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('page.staff', [
            'staff' => Group::query()
                ->with('users.group')
                ->where('is_torrent_modo', '=', 1)
                ->orderByDesc('position')
                ->get(),
        ]);
    }

    /**
     * Show Internals Page.
     */
    public function internal(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('page.internal', [
            'internals' => Internal::query()
                ->with([
                    'users' => fn ($query) => $query->with('group')->orderByPivot('position', 'asc'),
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Show Client-Blacklist Page.
     */
    public function clientblacklist(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('page.blacklist.client', [
            'clients' => BlacklistClient::all(),
        ]);
    }

    /**
     * Show About Us Page.
     */
    public function about(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('page.aboutus');
    }
}
