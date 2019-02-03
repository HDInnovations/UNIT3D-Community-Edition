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

namespace App;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Peer extends Model
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
        'uploaded',
        'downloaded',
        'left',
        'seeder',
        'created_at',
    ];

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
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A Seed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seed()
    {
        return $this->belongsTo(Torrent::class, 'torrents.id', 'torrent_id');
    }
}
