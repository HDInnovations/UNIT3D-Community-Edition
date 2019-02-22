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
 * @author     HDVinnie
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * The Attributes That Are Mass Assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'ip_checking',
        'multiple_choice',
    ];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * A Poll Has Many Options.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * A Poll Has Many Voters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }

    /**
     * Set The Poll's Title, Adds A Question Mark (?) If Needed.
     *
     * @param $title
     *
     * @return string
     */
    public function setTitleAttribute($title)
    {
        if (substr($title, -1) != '?') {
            return $this->attributes['title'] = $title.'?';
        }

        return $this->attributes['title'] = $title;
    }

    /**
     * Create A Poll Title Slug.
     *
     * @param $title
     *
     * @return string
     */
    public function makeSlugFromTitle($title)
    {
        $slug = strlen($title) > 20 ? substr(str_slug($title), 0, 20) : str_slug($title);
        $count = $this->where('slug', 'LIKE', "%$slug%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get Total Votes On A Poll Option.
     *
     * @return string
     */
    public function totalVotes()
    {
        $result = 0;
        foreach ($this->options as $option) {
            $result += $option->votes;
        }

        return $result;
    }

    protected static function boot()
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
