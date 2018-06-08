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

class TorrentRequest extends Model
{
    /**
     * The Attributes That Should Be Mutated To Dates
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
     * The Database Table Used By The Model
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * Belongs To A User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Belongs To A User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approveUser()
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Belongs To A User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function FillUser()
    {
        return $this->belongsTo(User::class, 'filled_by')->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Belongs To A Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Torrent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function torrent()
    {
        return $this->belongsTo(Torrent::class, 'filled_hash', 'info_hash');
    }

    /**
     * Has Many Comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, "requests_id", "id");
    }

    /**
     * Has Many BON Bounties
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestBounty()
    {
        return $this->hasMany(TorrentRequestBounty::class, "requests_id", "id");
    }

    /**
     * Parse Description And Return Valid HTML
     *
     * @return string Parsed BBCODE To HTML
     */
    public function getDescriptionHtml()
    {
        return Bbcode::parse($this->description);
    }
}
