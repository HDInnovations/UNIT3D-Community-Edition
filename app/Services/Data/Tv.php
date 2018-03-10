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
 
namespace App\Services\Data;

use Carbon\Carbon;

class Tv extends Movie
{
    /**
     * @var array
     */
    public $tvdb;

    /**
     * @var array
     */
    public $episodes;

    /**
     * @var Carbon
     */
    public $endDate;

    /**
     * @var bool
     */
    public $ended;

    /**
     * @var string
     */
    public $network;

    /**
     * @var array
     */
    public $creators;

    /**
     * @var float
     */
    public $tvdbRating;

    /**
     * @var int
     */
    public $tvdbVotes;

    public function __construct($data = [])
    {
        parent::__construct($data);

        if ($this->endDate instanceof \DateTime) {
            $this->endDate = $this->endDate ? (new Carbon())->instance($this->endDate) : null;
        } else {
            $this->endDate = $this->endDate ? (new Carbon($this->endDate)) : null;
        }
        $this->ended = $this->endDate ? true : $this->ended;
    }
}
