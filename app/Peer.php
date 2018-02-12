<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App;

use App\History;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
 * Peers for customers torrents
 *
 */
class Peer extends Model
{
    use Sortable;

    public $sortable = ['id', 'agent', 'uploaded', 'downloaded', 'left', 'seeder', 'created_at'];

    /**
     * Belongs to User
     *
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Belongs to torrent
     *
     *
     */
    public function torrent()
    {
        return $this->belongsTo(\App\Torrent::class);
    }
}
