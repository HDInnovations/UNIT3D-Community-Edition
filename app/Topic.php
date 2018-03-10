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

class Topic extends Model
{

    public $rules = [
        'name' => 'required',
        'slug' => 'required',
        'state' => 'required',
        'num_post' => '',
        'first_post_user_id' => 'required',
        'first_post_user_username' => 'required',
        'last_post_user_id' => '',
        'last_post_user_username' => '',
        'views' => '',
        'pinned' => '',
        'forum_id' => 'required',
    ];

    /**
     * Belongs to Forum
     *
     *
     */
    public function forum()
    {
        return $this->belongsTo(\App\Forum::class);
    }

    public function posts()
    {
        return $this->hasMany(\App\Post::class);
    }

    public function viewable()
    {
        if (Auth::user()->group->is_modo) {
            return true;
        }

        return $this->forum->getPermission()->read_topic;
    }

    public function postNumberFromId($searchId)
    {
        $count = 0;
        foreach ($this->posts as $post) {
            $count += 1;
            if ($searchId == $post->id) {
                break;
            }
        }
        return $count;
    }
}
