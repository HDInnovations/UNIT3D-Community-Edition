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

class Forum extends Model
{
    /**
     * Has Many Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Has Many Permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Returns A Table With The Forums In The Category
     *
     * @return string
     */
    public function getForumsInCategory()
    {
        return Forum::where('parent_id', '=', $this->id)->get();
    }

    /**
     * Returns The Category Nn Which The Forum Is Located
     *
     * @return string
     */
    public function getCategory()
    {
        return Forum::find($this->parent_id);
    }

    /**
     * Count The Number Of Posts In The Forum
     *
     * @return string
     */
    public function getPostCount($forumId)
    {
        $forum = Forum::find($forumId);
        $topics = $forum->topics;
        $count = 0;
        foreach ($topics as $t) {
            $count += $t->posts()->count();
        }
        return $count;
    }

    /**
     * Count The Number Of Topics In The Forum
     *
     * @return string
     */
    public function getTopicCount($forumId)
    {
        $forum = Forum::find($forumId);
        return Topic::where('forum_id', '=', $forum->id)->count();
    }

    /**
     * Returns The Permission Field
     *
     * @return string
     */
    public function getPermission()
    {
        if (auth()->check()) {
            $group = auth()->user()->group;
        } else {
            $group = Group::find(2);
        }
        return Permission::whereRaw('forum_id = ? AND group_id = ?', [$this->id, $group->id])->first();
    }
}
