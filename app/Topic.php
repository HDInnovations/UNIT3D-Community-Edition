<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
