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

namespace App\Http\Controllers\Staff;

use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class VersionController extends Controller
{
    /**
     * @var VersionController
     */
    private $version;

    public function __construct()
    {
        $this->version = config('unit3d.version');
    }

    /**
     * Check the latest release of UNIT3D and compare them to the local version.
     *
     * @return string
     */
    public function checkVersion()
    {
        $client = new Client();
        $response = json_decode($client->get('//api.github.com/repos/HDInnovations/UNIT3D/releases')->getBody());
        $lastestVersion = $response[0]->tag_name;

        return response([
            'updated'       => version_compare($this->version, $lastestVersion, '<') ? false : true,
            'latestversion' => $lastestVersion,
        ]);
    }
}
