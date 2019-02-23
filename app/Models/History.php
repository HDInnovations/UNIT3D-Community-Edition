<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use Sortable;

    /**
     * The Columns That Are Sortable.
     *
     * @var array
     */
    public $sortable = [
        'id',
        'agent',
        'active',
        'seeder',
        'uploaded',
        'downloaded',
        'seedtime',
        'created_at',
        'updated_at',
        'completed_at',
        'prewarn',
        'hitrun',
        'immune',
    ];

    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'history';

    /**
     * The Attributes That Are Mass Assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'info_hash',
    ];

    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array
     */
    protected $dates = ['completed_at'];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Torrent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function torrent()
    {
        return $this->belongsTo(Torrent::class, 'info_hash', 'info_hash');
    }
}
