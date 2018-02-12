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

use App\Torrent;
use App\CatalogTorrent;

class CatalogTorrent extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "catalog_torrent";

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = [
        'imdb', 'tvdb', 'catalog_id'
    ];
}
