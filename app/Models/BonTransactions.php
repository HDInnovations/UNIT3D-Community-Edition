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

class BonTransactions extends Model
{
    use HasFactory;

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The Storage Format Of The Model's Date Columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Belongs To A Sender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Receiver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function receiver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To BonExchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BonExchange, self>
     */
    public function exchange(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BonExchange::class)->withDefault([
            'value' => 0,
            'cost'  => 0,
        ]);
    }

    /**
     * Belongs to Torrent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Torrent, self>
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs to Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Post, self>
     */
    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
