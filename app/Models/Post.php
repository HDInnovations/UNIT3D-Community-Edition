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

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

/**
 * App\Models\Post.
 *
 * @property int                             $id
 * @property string                          $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $user_id
 * @property int                             $topic_id
 */
class Post extends Model
{
    use Auditable;
    use HasFactory;

    protected $fillable = [
        'content',
        'topic_id',
        'user_id',
    ];

    /**
     * Belongs To A Topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Topic, self>
     */
    public function topic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * A Post Has Many Likes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Like>
     */
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class)->where('like', '=', 1);
    }

    /**
     * A Post Has Many Dislikes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Like>
     */
    public function dislikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class)->where('dislike', '=', 1);
    }

    /**
     * Has Many Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<PostTip>
     */
    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostTip::class);
    }

    /**
     * A Post Author Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Post>
     */
    public function authorPosts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    /**
     * A Post Author Has Many Topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Topic>
     */
    public function authorTopics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class, 'first_post_user_id', 'user_id');
    }

    /**
     * Only include posts a user is authorized to.
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
        return $query->whereNotIn(
            'topic_id',
            Topic::query()
                ->whereRelation(
                    'forumPermissions',
                    fn ($query) => $query
                        ->where('group_id', '=', auth()->user()->group_id)
                        ->where(
                            fn ($query) => $query
                                ->whereRaw('1 = 0')
                                ->when($canReadTopic !== null, fn ($query) => $query->orWhere('read_topic', '!=', $canReadTopic))
                                ->when($canReplyTopic !== null, fn ($query) => $query->orWhere('reply_topic', '!=', $canReplyTopic))
                                ->when($canStartTopic !== null, fn ($query) => $query->orWhere('start_topic', '!=', $canStartTopic))
                        )
                )
                ->when($canReplyTopic && !auth()->user()->group->is_modo, fn ($query) => $query->where('state', '=', 'open'))
                ->select('id')
        );
    }

    /**
     * Set The Posts Content After Its Been Purified.
     */
    public function setContentAttribute(?string $value): void
    {
        $this->attributes['content'] = htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Content And Return Valid HTML.
     */
    public function getContentHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->content));
    }
}
