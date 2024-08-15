<?php

declare(strict_types=1);

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

/**
 * App\Models\UserNotification.
 *
 * @property int   $id
 * @property int   $user_id
 * @property int   $block_notifications
 * @property int   $show_bon_gift
 * @property int   $show_mention_forum_post
 * @property int   $show_mention_article_comment
 * @property int   $show_mention_request_comment
 * @property int   $show_mention_torrent_comment
 * @property int   $show_subscription_topic
 * @property int   $show_subscription_forum
 * @property int   $show_forum_topic
 * @property int   $show_following_upload
 * @property int   $show_request_bounty
 * @property int   $show_request_comment
 * @property int   $show_request_fill
 * @property int   $show_request_fill_approve
 * @property int   $show_request_fill_reject
 * @property int   $show_request_claim
 * @property int   $show_request_unclaim
 * @property int   $show_torrent_comment
 * @property int   $show_torrent_tip
 * @property int   $show_torrent_thank
 * @property int   $show_account_follow
 * @property int   $show_account_unfollow
 * @property array $json_account_groups
 * @property array $json_bon_groups
 * @property array $json_mention_groups
 * @property array $json_request_groups
 * @property array $json_torrent_groups
 * @property array $json_forum_groups
 * @property array $json_following_groups
 * @property array $json_subscription_groups
 */
class UserNotification extends Model
{
    /** @use HasFactory<\Database\Factories\UserNotificationFactory> */
    use HasFactory;

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{json_account_groups: 'array', json_mention_groups: 'array', json_request_groups: 'array', json_torrent_groups: 'array', json_forum_groups: 'array', json_following_groups: 'array', json_subscription_groups: 'array', json_bon_groups: 'array'}
     */
    protected function casts(): array
    {
        return [
            'json_account_groups'      => 'array',
            'json_mention_groups'      => 'array',
            'json_request_groups'      => 'array',
            'json_torrent_groups'      => 'array',
            'json_forum_groups'        => 'array',
            'json_following_groups'    => 'array',
            'json_subscription_groups' => 'array',
            'json_bon_groups'          => 'array',
        ];
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
