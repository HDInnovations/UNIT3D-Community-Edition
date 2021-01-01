<?php
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

namespace App\Http\Controllers\MediaHub;

use App\Http\Controllers\Controller;
use App\Models\Network;

class NetworkController extends Controller
{
    /**
     * Display All Networks.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('mediahub.network.index');
    }

    /**
     * Show A Network.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $network = Network::with('tv')->findOrFail($id);

        return view('mediahub.network.show', [
            'network' => $network,
        ]);
    }
}
