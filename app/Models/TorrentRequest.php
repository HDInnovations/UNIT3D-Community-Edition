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

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use App\Notifications\NewComment;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

class TorrentRequest extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array
     */
    protected $casts = [
        'filled_when'   => 'datetime',
        'approved_when' => 'datetime',
    ];

    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'requests';

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
     * Belongs To A User.
     */
    public function approveUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A User.
     */
    public function FillUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'filled_by')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Category.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Resolution.
     */
    public function resolution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }

    /**
     * Belongs To A Torrent.
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Has Many BON Bounties.
     */
    public function requestBounty(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequestBounty::class, 'requests_id', 'id');
    }

    /**
     * Set The Requests Description After Its Been Purified.
     */
    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['description'] = \htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Description And Return Valid HTML.
     */
    public function getDescriptionHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->description, true));
    }

    /**
     * Notify Requester When A New Action Is Taken.
     */
    public function notifyRequester($type, $payload): bool
    {
        $user = User::with('notification')->findOrFail($this->user_id);
        if ($user->acceptsNotification(\auth()->user(), $user, 'request', 'show_request_comment')) {
            $user->notify(new NewComment('request', $payload));

            return true;
        }

        return true;
    }
}
