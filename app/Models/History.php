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

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class History extends Model
{
    use HasFactory;
    use Sortable;

    /**
     * The Columns That Are Sortable.
     */
    public array $sortable = [
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
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Torrent.
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class, 'info_hash', 'info_hash');
    }
}
