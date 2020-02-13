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

namespace App\Services\Clients;

use App\Services\Contracts\MangaInterface;
use SimpleXMLElement;

class AnnClient extends Client implements MangaInterface
{
    protected $apiUrl = 'cdn.animenewsnetwork.com/encyclopedia/api.xml';

    protected $apiSecure = false;

    public function __construct()
    {
        parent::__construct($this->apiUrl);
    }

    public function find($key)
    {
        // TODO: Implement find() method.
    }

    public function manga($id)
    {
        $url = $this->apiUrl.'?manga='.$id;
        $data = $this->request($url);
        $xml = new SimpleXMLElement($data);
        $mangas = $xml->manga;
        $staffs = [];
        foreach ($mangas->staff as $staff) {
            $staffs[] = [
                'task'  => $staff->task,
                'staff' => $staff->person,
            ];
        }

        return $staffs;
    }

    public function authors($id)
    {
        // TODO: Implement authors() method.
    }

    public function characters($id)
    {
        // TODO: Implement characters() method.
    }
}
