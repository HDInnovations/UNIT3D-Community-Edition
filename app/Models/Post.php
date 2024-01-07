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
     * A Post Has Many Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class);
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
