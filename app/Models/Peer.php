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

class Peer extends Model
{
    use HasFactory;
    use Sortable;

    /**
     * The Columns That Are Sortable.
     */
    public array $sortable = [
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
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A Seed.
     */
    public function seed(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class, 'torrents.id', 'torrent_id');
    }

    /**
     * Updates Connectable State If Needed.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     *
     * @var resource
     */
    public function updateConnectableStateIfNeeded(): void
    {
        if (\config('announce.connectable_check') == true) {
            $tmp_ip = $this->ip;

            // IPv6 Check
            if (filter_var($tmp_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $tmp_ip = '['.$tmp_ip.']';
            }

            if (! \cache()->has('peers:connectable:'.$tmp_ip.'-'.$this->port.'-'.$this->agent)) {
                $con = @fsockopen($tmp_ip, $this->port, $_, $_, 1);

                $this->connectable = \is_resource($con);
                \cache()->put('peers:connectable:'.$tmp_ip.'-'.$this->port.'-'.$this->agent, $this->connectable, now()->addSeconds(config('announce.connectable_check_interval')));

                if (\is_resource($con)) {
                    \fclose($con);
                }
            }
        }
    }
}
