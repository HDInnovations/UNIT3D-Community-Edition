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
use Illuminate\Support\Facades\Auth;

class Forum extends Model
{

    /**
     * Has many topics
     *
     */
    public function topics()
    {
        return $this->hasMany(\App\Topic::class);
    }

    /**
     * Has many permissions
     *
     */
    public function permissions()
    {
        return $this->hasMany(\App\Permission::class);
    }

    /**
     * Returns a table with the forums in the category
     *
     */
    public function getForumsInCategory()
    {
        return Forum::where('parent_id', '=', $this->id)->get();
    }

    /**
     * Returns the category in which the forum is located
     *
     */
    public function getCategory()
    {
        return Forum::find($this->parent_id);
    }

    /**
     * Count the number of posts in the forum
     *
     *
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
     * Count the number of topics in the forum
     *
     */
    public function getTopicCount($forumId)
    {
        $forum = Forum::find($forumId);
        return Topic::where('forum_id', '=', $forum->id)->count();
    }

    /**
     * Returns the permission field
     *
     */
    public function getPermission()
    {
        if (Auth::check()) {
            $group = Auth::user()->group;
        } else {
            $group = Group::find(2);
        }
        return Permission::whereRaw('forum_id = ? AND group_id = ?', [$this->id, $group->id])->first();
    }
}
