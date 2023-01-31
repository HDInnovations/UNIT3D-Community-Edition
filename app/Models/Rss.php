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
use Illuminate\Database\Eloquent\SoftDeletes;

class Rss extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'rss';

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The Attributes That Should Be Cast To Native Types.
     *
     * @var array
     */
    protected $casts = [
        'name'            => 'string',
        'json_torrent'    => 'array',
        'expected_fields' => 'array',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

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
     * Belongs To A Staff Member.
     */
    public function staff(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // Not needed yet. Just added for future extendability.
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the RSS feeds JSON Torrent as object.
     */
    public function getObjectTorrentAttribute(): \stdClass|bool
    {
        // Went with attribute to avoid () calls in views. Uniform ->object_torrent vs ->json_torrent.
        if ($this->json_torrent) {
            $expected = $this->expected_fields;

            return (object) \array_merge($expected, $this->json_torrent);
        }

        return false;
    }

    /**
     * Get the RSS feeds expected fields for form validation.
     */
    public function getExpectedFieldsAttribute(): array
    {
        // Just Torrents for now... extendable to check on feed type in future.
        return [
            'search'          => null,
            'description'     => null,
            'uploader'        => null,
            'imdb'            => null,
            'mal'             => null,
            'categories'      => null,
            'types'           => null,
            'resolutions'     => null,
            'genres'          => null,
            'freeleech'       => null,
            'doubleupload'    => null,
            'featured'        => null,
            'stream'          => null,
            'highspeed'       => null,
            'sd'              => null,
            'internal'        => null,
            'personalrelease' => null,
            'bookmark'        => null,
            'alive'           => null,
            'dying'           => null,
            'dead'            => null,
        ];
    }
}
