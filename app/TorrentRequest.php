<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Bbcode;

/**
 * Torrent Requests
 *
 */
class TorrentRequest extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = ['name', 'description', 'category_id', 'user_id', 'imdb', 'votes', 'tvdb', 'type', 'bounty', 'tmdb', 'mal'];

    /**
     * Rules For Validation
     *
     */
    public $rules = [
        'name' => 'required',
        'description' => 'required',
        'category_id' => 'required',
        'user_id' => 'required',
        'imdb' => 'required|numeric',
        'tvdb' => 'required|numeric',
        'tmdb' => 'required|numeric',
        'mal' => 'required|numeric',
        'type' => 'required',
        'bounty' => 'required|numeric',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'filled_when',
        'approved_when'
    ];

    /**
     * Belongs to This User who created the request
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Belongs to the user who approves the request
     *
     */
    public function approveUser()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }

    /**
     * Belongs to the user who fills the request
     *
     */
    public function FillUser()
    {
        return $this->belongsTo(\App\User::class, 'filled_by');
    }

    /**
     * Belongs to This Category
     *
     */
    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }

    /**
     * Belongs to This Type
     *
     */
    public function type()
    {
        return $this->belongsTo(\App\Type::class);
    }

    /**
     * Belongs to this torrent
     *
     */
    public function torrent()
    {
        return $this->belongsTo(\App\Torrent::class, 'filled_hash', 'info_hash');
    }

    /**
     * Has many Comment
     *
     */
    public function comments()
    {
        return $this->hasMany(\App\Comment::class, "requests_id", "id");
    }

    /**
     * Has many request bounties
     *
     */
    public function requestBounty()
    {
        return $this->hasMany(\App\TorrentRequestBounty::class, "requests_id", "id");
    }

    /**
     * Format The Description
     *
     */
    public function getDescriptionHtml()
    {
        return Bbcode::parse($this->description);
    }
}
