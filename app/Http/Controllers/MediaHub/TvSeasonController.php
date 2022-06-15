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
use App\Models\Season;
use App\Models\Tv;

class TvSeasonController extends Controller
{
    /**
     * Show A TV Season.
     */
    public function show(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $season = Season::with(['episodes', 'torrents'])->findOrFail($id);
        $show = Tv::where('id', '=', $season->tv_id)->first();

        return \view('mediahub.tv.season.show', [
            'season' => $season,
            'show'   => $show,
        ]);
    }
}
