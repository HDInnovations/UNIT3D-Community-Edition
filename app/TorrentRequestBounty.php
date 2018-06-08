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

class TorrentRequestBounty extends Model
{
    /**
     * The Database Table Used By The Model
     *
     * @var string
     */
    protected $table = 'request_bounty';

    /**
     * Belongs To A User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Belongs To A Torrent Request
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function request()
    {
        return $this->belongsTo(TorrentRequest::class);
    }
}
