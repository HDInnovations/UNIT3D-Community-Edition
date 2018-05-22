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

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringHelper;

class TorrentFile extends Model
{

    /**
     * The Database Table Used By The Model
     */
    protected $table = 'files';

    /**
     * Disable Dates
     */
    public $timestamps = false;

    /**
     * Belongs To A Torrent
     */
    public function torrent()
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Return Size In Human Format
     */
    public function getSize($bytes = null, $precision = 2)
    {
        $bytes = $this->size;
        return StringHelper::formatBytes($bytes, 2);
    }
}
