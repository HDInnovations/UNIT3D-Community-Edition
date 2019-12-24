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

use App\Models\Page;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\DatabaseManager;

final class PageController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;
    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private DatabaseManager $databaseManager;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    public function __construct(Factory $viewFactory, DatabaseManager $databaseManager, Repository $configRepository)
    {
        $this->viewFactory = $viewFactory;
        $this->databaseManager = $databaseManager;
        $this->configRepository = $configRepository;
    }

    /**
     * Show A Page.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id): Factory
    {
        $page = Page::findOrFail($id);

        return $this->viewFactory->make('page.page', ['page' => $page]);
    }

    /**
     * Show Staff Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function staff(): Factory
    {
        $staff = $this->databaseManager->table('users')->leftJoin('groups', 'users.group_id', '=', 'groups.id')->select(['users.id', 'users.title', 'users.username', 'groups.name', 'groups.color', 'groups.icon'])->where('groups.is_admin', 1)->orWhere('groups.is_modo', 1)->get();

        return $this->viewFactory->make('page.staff', ['staff' => $staff]);
    }

    /**
     * Show Internals Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function internal(): Factory
    {
        $internal = $this->databaseManager->table('users')->leftJoin('groups', 'users.group_id', '=', 'groups.id')->select(['users.id', 'users.title', 'users.username', 'groups.name', 'groups.color', 'groups.icon'])->where('groups.is_internal', 1)->get();

        return $this->viewFactory->make('page.internal', ['internal' => $internal]);
    }

    /**
     * Show Blacklist Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blacklist(): Factory
    {
        $clients = $this->configRepository->get('client-blacklist.clients', []);
        $browsers = $this->configRepository->get('client-blacklist.browsers', []);

        return $this->viewFactory->make('page.blacklist', ['clients' => $clients, 'browsers' => $browsers]);
    }

    /**
     * Show About Us Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about(): Factory
    {
        return $this->viewFactory->make('page.aboutus');
    }

    /**
     * Show Email Whitelist / Blacklist Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function emailList(): Factory
    {
        $whitelist = $this->configRepository->get('email-white-blacklist.allow', []);
        $blacklist = $this->configRepository->get('email-white-blacklist.block', []);

        return $this->viewFactory->make('page.emaillist', ['whitelist' => $whitelist, 'blacklist' => $blacklist]);
    }
}
