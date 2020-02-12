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

class Episode
{
    public $season;

    public $episode;

    public $title;

    public $releaseDate;

    public $plot;

    public $photo;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (is_array($value) && !count($value)) {
                    $value = null;
                }
                $this->$key = !empty($value) ? $value : null;
            }
        }

        if ($this->releaseDate instanceof \DateTime) {
            $release_date = $this->releaseDate ? (new Carbon())->instance($this->releaseDate) : null;
        } else {
            $release_date = $this->releaseDate ? new Carbon($this->releaseDate) : null;
        }
        $this->releaseDate = $release_date;
    }
}
