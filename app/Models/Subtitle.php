<?php

declare(strict_types=1);

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

use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\StringHelper;
use App\Models\Scopes\ApprovedScope;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Subtitle.
 *
 * @property int                             $id
 * @property string                          $title
 * @property string                          $file_name
 * @property int                             $file_size
 * @property int                             $language_id
 * @property string                          $extension
 * @property string|null                     $note
 * @property int|null                        $downloads
 * @property int                             $verified
 * @property int                             $user_id
 * @property int                             $torrent_id
 * @property bool                            $anon
 * @property int                             $status
 * @property \Illuminate\Support\Carbon|null $moderated_at
 * @property int|null                        $moderated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[ScopedBy([ApprovedScope::class])]
class Subtitle extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\SubtitleFactory> */
    use HasFactory;

    final public const APPROVED = 1;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{anon: 'bool', moderated_at: 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'anon'         => 'bool',
            'moderated_at' => 'datetime',
        ];
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => User::SYSTEM_USER_ID,
        ]);
    }

    /**
     * Belongs To A Torrent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Torrent, $this>
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A Media Language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<MediaLanguage, $this>
     */
    public function language(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MediaLanguage::class);
    }

    /**
     * Returns The Size In Human Format.
     */
    public function getSize(): string
    {
        $bytes = $this->file_size;

        return StringHelper::formatBytes($bytes, 2);
    }
}
