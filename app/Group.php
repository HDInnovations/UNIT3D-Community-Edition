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

class Group extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "groups";

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = [
        'name', 'color', 'icon'
    ];

    public $timestamps = false;

    /**
     * Has many users
     *
     */
    public function users()
    {
        return $this->hasMany(\App\User::class);
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
     * Returns the requested row from the permissions table
     *
     */
    public function getPermissionsByForum($forum)
    {
        return Permission::whereRaw('forum_id = ? AND group_id = ?', [$forum->id, $this->id])->first();
    }
}
