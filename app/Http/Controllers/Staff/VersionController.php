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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\VersionControllerTest
 */
class VersionController extends Controller
{
    /**
     * Check the latest release of UNIT3D and compare them to the local version.
     */
    public function checkVersion(): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $latestVersion = Http::get('//api.github.com/repos/HDInnovations/UNIT3D/releases')[0]['tag_name'];

        return response([
            'updated'       => !version_compare(config('unit3d.version'), $latestVersion, '<'),
            'latestversion' => $latestVersion,
        ]);
    }
}
