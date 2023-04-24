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

class UserNotification extends Model
{
    use HasFactory;

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The Attributes That Should Be Cast To Native Values.
     *
     * @var array
     */
    protected $casts = [
        'json_account_groups'      => 'array',
        'json_mention_groups'      => 'array',
        'json_request_groups'      => 'array',
        'json_torrent_groups'      => 'array',
        'json_forum_groups'        => 'array',
        'json_following_groups'    => 'array',
        'json_subscription_groups' => 'array',
        'json_bon_groups'          => 'array',
    ];

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Get the Expected groups for form validation.
     */
    public function getExpectedGroupsAttribute(): array
    {
        return [];
    }

    /**
     * Get the Expected fields for form validation.
     */
    public function getExpectedFieldsAttribute(): array
    {
        return [];
    }

    /**
     * Set the base vars on object creation without touching boot.
     */
    public function setDefaultValues(string $type = 'default'): void
    {
        foreach ($this->casts as $k => $v) {
            if ($v == 'array') {
                $this->$k = $this->expected_groups;
            }
        }
    }
}
