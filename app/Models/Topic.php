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

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Topic.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string|null                     $state
 * @property bool                            $pinned
 * @property bool                            $approved
 * @property bool                            $denied
 * @property bool                            $solved
 * @property bool                            $invalid
 * @property bool                            $bug
 * @property bool                            $suggestion
 * @property bool                            $implemented
 * @property int|null                        $num_post
 * @property int|null                        $first_post_user_id
 * @property int|null                        $last_post_id
 * @property int|null                        $last_post_user_id
 * @property \Illuminate\Support\Carbon|null $last_post_created_at
 * @property int|null                        $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $forum_id
 */
class Topic extends Model
{
    use Auditable;
    use HasFactory;

    protected $casts = [
        'last_post_created_at' => 'datetime',
        'pinned'               => 'boolean',
        'approved'             => 'boolean',
        'denied'               => 'boolean',
        'solved'               => 'boolean',
        'invalid'              => 'boolean',
        'bug'                  => 'boolean',
        'suggestion'           => 'boolean',
        'implemented'          => 'boolean',
    ];

    protected $guarded = [];

    /**
     * Belongs To A Forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Forum, self>
     */
    public function forum(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'first_post_user_id', 'id');
    }

    /**
     * Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Post>
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TopicRead>
     */
    public function reads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TopicRead::class);
    }

    /**
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subscription>
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Has One Permissions through Forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ForumPermission>
     */
    public function forumPermissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ForumPermission::class, 'forum_id', 'forum_id');
    }

    /**
     * Belongs to Many Subscribed Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function subscribedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, Subscription::class);
    }

    /**
     * Latest post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Post>
     */
    public function latestPostSlow(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Post::class)->latestOfMany();
    }

    /**
     * Latest post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Post, self>
     */
    public function latestPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'last_post_id');
    }

    /**
     * Latest poster.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function latestPoster(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'last_post_user_id');
    }

    /**
     * Only include topics a user is authorized to.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeAuthorized(
        \Illuminate\Database\Eloquent\Builder $query,
        ?bool $canReadTopic = null,
        ?bool $canReplyTopic = null,
        ?bool $canStartTopic = null,
    ): \Illuminate\Database\Eloquent\Builder {
        return $query
            ->whereRelation(
                'forumPermissions',
                fn ($query) => $query
                    ->where('group_id', '=', auth()->user()->group_id)
                    ->when($canReadTopic !== null, fn ($query) => $query->where('read_topic', '=', $canReadTopic))
                    ->when($canReplyTopic !== null, fn ($query) => $query->where('reply_topic', '=', $canReplyTopic))
                    ->when($canStartTopic !== null, fn ($query) => $query->where('start_topic', '=', $canStartTopic))
            )
            ->when($canReplyTopic && !auth()->user()->group->is_modo, fn ($query) => $query->where('state', '=', 'open'));
    }

    /**
     * Does User Have Permission To View Topic.
     */
    public function viewable(): bool
    {
        if (auth()->user()->group->is_modo) {
            return true;
        }

        return $this->forum->getPermission()?->read_topic;
    }
}
