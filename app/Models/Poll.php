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
use Illuminate\Support\Str;

class Poll extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * The Attributes That Are Mass Assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'multiple_choice',
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
     * A Poll Has Many Options.
     */
    public function options(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * A Poll Has Many Voters.
     */
    public function voters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Voter::class);
    }

    /**
     * Set The Poll's Title.
     */
    public function setTitleAttribute($title): string
    {
        return $this->attributes['title'] = $title;
    }

    /**
     * Create A Poll Title Slug.
     */
    public function makeSlugFromTitle($title): string
    {
        $slug = \strlen($title) > 20 ? \substr(Str::slug($title), 0, 20) : Str::slug($title);
        $count = $this->where('slug', 'LIKE', '%'.$slug.'%')->count();

        return $count ? \sprintf('%s-%s', $slug, $count) : $slug;
    }

    /**
     * Get Total Votes On A Poll Option.
     */
    public function totalVotes(): int
    {
        $result = 0;
        foreach ($this->options as $option) {
            $result += $option->votes;
        }

        return $result;
    }

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($poll) {
            if (empty($poll->slug)) {
                $poll->slug = $poll->makeSlugFromTitle($poll->title);
            }

            return true;
        });
    }
}
