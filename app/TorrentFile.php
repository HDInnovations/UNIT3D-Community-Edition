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

/**
 * Template for torrent files
 *
 *
 */
class TorrentFile extends Model
{

    /**
     * DB Table
     *
     */
    protected $table = 'files';

    /**
     * Disable dates when backing up
     *
     */
    public $timestamps = false;

    /**
     * Belongs to Torrent
     *
     *
     */
    public function torrent()
    {
        return $this->belongsTo(\App\Torrent::class);
    }

    /**
     * Return Size In Human Format
     *
     */
    public function getSize($bytes = null, $precision = 2)
    {
        $bytes = $this->size;
        return StringHelper::formatBytes($bytes, 2);
    }
}
