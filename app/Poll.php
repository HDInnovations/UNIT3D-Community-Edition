<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'ip_checking',
        'multiple_choice'
    ];

    protected static function boot()
    {
        Poll::creating(function ($poll) {
            if (empty($poll->slug)) {
                $poll->slug = $poll->makeSlugFromTitle($poll->title);
            }
            return true;
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function options()
    {
        return $this->hasMany(\App\Option::class);
    }

    public function voters()
    {
        return $this->hasMany(\App\Voter::class);
    }

    /**
     * Set the poll's title, adds ? if needed.
     *
     * @param  string $value
     * @return string
     */
    public function setTitleAttribute($title)
    {
        if (substr($title, -1) != '?') {
            return $this->attributes['title'] = $title . '?';
        }

        return $this->attributes['title'] = $title;
    }

    /**
     * Create a title slug.
     *
     * @param  string $title
     * @return string
     */

    public function makeSlugFromTitle($title)
    {
        $slug = strlen($title) > 20 ? substr(str_slug($title), 0, 20) : str_slug($title);
        $count = $this->where('slug', 'LIKE', "%$slug%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function totalVotes()
    {
        $result = 0;
        foreach ($this->options as $option) {
            $result += $option->votes;
        }
        return $result;
    }
}
