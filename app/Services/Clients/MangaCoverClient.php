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

namespace App\Services\Clients;

use App\Services\Contracts\MangaInterface;

final class MangaCoverClient extends Client implements MangaInterface
{
    /**
     * @var string
     */
    protected string $apiUrl = 'mcd.iosphe.re/api/v1/';

    /**
     * @var bool
     */
    protected bool $apiSecure = false;

    public function __construct()
    {
        parent::__construct($this->apiUrl);
    }

    public function find($key): void
    {
        // TODO: Implement find() method.
    }

    public function manga($id): void
    {
        // TODO: Implement manga() method.
    }

    public function authors($id): void
    {
        // TODO: Implement authors() method.
    }

    public function characters($id): void
    {
        // TODO: Implement characters() method.
    }
}
