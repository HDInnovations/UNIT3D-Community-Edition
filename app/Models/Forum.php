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
 * App\Models\Forum.
 *
 * @property int                             $id
 * @property int|null                        $position
 * @property int|null                        $num_topic
 * @property int|null                        $num_post
 * @property int|null                        $last_topic_id
 * @property int|null                        $last_post_id
 * @property int|null                        $last_post_user_id
 * @property \Illuminate\Support\Carbon|null $last_post_created_at
 * @property string|null                     $name
 * @property string|null                     $slug
 * @property string|null                     $description
 * @property int                             $forum_category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Forum extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at'];

    /**
     * Has Many Topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Topic>
     */
    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Returns The Category In Which The Forum Is Located.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<ForumCategory, self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'forum_category_id');
    }

    /**
     * All posts inside the forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<Post>
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Post::class, Topic::class);
    }

    /**
     * Latest topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Topic>
     */
    public function lastRepliedTopicSlow(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Topic::class)->ofMany('last_post_created_at', 'max');
    }

    /**
     * Latest topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Topic, self>
     */
    public function lastRepliedTopic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Topic::class, 'last_topic_id');
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
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subscription>
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class, 'forum_id', 'id');
    }

    /**
     * Has Many Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ForumPermission>
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ForumPermission::class);
    }

    /**
     * Belongs To Many Subscribed Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function subscribedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, Subscription::class);
    }

    /**
     * Only include forums a user is authorized to.
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
                'permissions',
                fn ($query) => $query
                    ->where('group_id', '=', auth()->user()->group_id)
                    ->when($canReadTopic !== null, fn ($query) => $query->where('read_topic', '=', $canReadTopic))
                    ->when($canReplyTopic !== null, fn ($query) => $query->where('reply_topic', '=', $canReplyTopic))
                    ->when($canStartTopic !== null, fn ($query) => $query->where('start_topic', '=', $canStartTopic))
            );
    }

    /**
     * Returns The Permission Field.
     */
    public function getPermission(): ?ForumPermission
    {
        return ForumPermission::query()
            ->where('group_id', '=', auth()->user()->group_id)
            ->where('forum_id', '=', $this->id)
            ->first();
    }
}
