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
 * @author     singularity43
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
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
        'json_account_groups' => 'array',
        'json_mention_groups' => 'array',
        'json_request_groups' => 'array',
        'json_torrent_groups' => 'array',
        'json_forum_groups' => 'array',
        'json_following_groups' => 'array',
        'json_subscription_groups' => 'array',
        'json_bon_groups' => 'array',
    ];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Get the Expected groups for form validation.
     *
     * @return array
     */
    public function getExpectedGroupsAttribute()
    {
        $expected_groups = ['default_groups' => ['1' => 0]];

        return $expected_groups;
    }

    /**
     * Get the Expected fields for form validation.
     *
     * @return array
     */
    public function getExpectedFieldsAttribute()
    {
        $expected_fields = [];

        return $expected_fields;
    }

    /**
     * Set the base vars on object creation without touching boot.
     *
     * @param  string  $type
     * @return void
     */
    public function setDefaultValues($type = 'default')
    {
        foreach ($this->casts as $k => $v) {
            if ($v == 'array') {
                $this->$k = $this->expected_groups;
            }
        }
    }
}
