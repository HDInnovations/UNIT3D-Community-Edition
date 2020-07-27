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

namespace App\Services\Data;

use Carbon\Carbon;

class Tv extends Movie
{
    /**
     * @var array
     */
    public $tvdb = [];

    /**
     * @var array
     */
    public $episodes = [];

    /**
     * @var Carbon
     */
    public $carbon;

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
    public $creators = [];

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

        if ($this->carbon instanceof \DateTime) {
            $this->carbon = $this->carbon ? (new Carbon())->instance($this->carbon) : null;
        } else {
            $this->carbon = $this->carbon ? (new Carbon($this->carbon)) : null;
        }
        $this->ended = $this->carbon ? true : $this->ended;
    }
}
