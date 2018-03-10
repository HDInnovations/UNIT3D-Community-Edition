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

use Illuminate\Database\Eloquent\Model;

class BonTransactions extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bon_transactions';

    /**
     * Tells Laravel to not maintain the timestamp columns
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = ['itemID', 'name', 'cost', 'sender', 'receiver', 'comment', 'torrent_id'];
}
