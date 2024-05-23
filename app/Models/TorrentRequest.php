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
use App\Traits\Auditable;
use App\Traits\TorrentFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

/**
 * App\Models\TorrentRequest.
 *
 * @property int                             $id
 * @property string                          $name
 * @property int                             $category_id
 * @property int|null                        $imdb
 * @property int|null                        $tvdb
 * @property int|null                        $tmdb
 * @property int|null                        $mal
 * @property int                             $igdb
 * @property string                          $description
 * @property int                             $user_id
 * @property string                          $bounty
 * @property int                             $votes
 * @property int|null                        $claimed
 * @property int                             $anon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null                        $filled_by
 * @property int|null                        $torrent_id
 * @property \Illuminate\Support\Carbon|null $filled_when
 * @property int                             $filled_anon
 * @property int|null                        $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_when
 * @property int                             $type_id
 * @property int|null                        $resolution_id
 * @property-read User|null $approver
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TorrentRequestBounty> $bounties
 * @property-read Category|null $category
 * @property-read TorrentRequestClaim|null $claim
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read User|null $filler
 * @property-read Resolution|null $resolution
 * @property-read Torrent|null $torrent
 * @property-read Type|null $type
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest alive()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest bookmarkedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest dead()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest doubleup()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest downloadedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest dying()
 * @method static \Database\Factories\TorrentRequestFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest featured()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest graveyard()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest highSpeed()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest internal()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest leechedby(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest notDownloadedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofAdult(?bool $isAdult = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofCategory(array $categories)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofCollection(int $collectionId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofCompany(int $companyId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofDescription(string $description, bool $isRegex = false)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofDistributor(array $distributors)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofEpisode(int $episodeNumber)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofFilename(string $filename)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofFreeleech(array|int $free)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofGenre(array $genres)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofImdb(int $tvdbId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofKeyword(array $keywords)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofMal(int $malId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofMediainfo(string $mediainfo, bool $isRegex = false)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofName(string $name, bool $isRegex = false)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofNetwork(int $networkId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofPlaylist(int $playlistId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofPrimaryLanguage(array $languages)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofRefundable(int $refundable)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofRegion(array $regions)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofResolution(array $resolutions)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofSeason(int $seasonNumber)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofSizeGreaterOrEqualTo(int $size)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofSizeLesserOrEqualTo(int $size)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofTmdb(int $tvdbId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofTvdb(int $tvdbId)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofType(array $types)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest ofUploader(string $username, ?\App\Models\User $authenticatedUser = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest personalRelease()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest refundable()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest releasedAfterOrIn(int $year)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest releasedBeforeOrIn(int $year)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest sd()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest seededBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest streamOptimized()
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest uncompletedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|TorrentRequest wishedBy(\App\Models\User $user)
 * @mixin \Eloquent
 */
class TorrentRequest extends Model
{
    use Auditable;
    use HasFactory;
    use TorrentFilter;

    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'filled_when'   => 'datetime',
            'approved_when' => 'datetime',
            'igdb'          => 'integer',
        ];
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function filler(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'filled_by')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Type, self>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Resolution.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Resolution, self>
     */
    public function resolution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }

    /**
     * Belongs To A Torrent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Torrent, self>
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Comment>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Has Many BON Bounties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequestBounty>
     */
    public function bounties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequestBounty::class, 'requests_id', 'id');
    }

    /**
     * Has One Torrent Request Claim.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<TorrentRequestClaim>
     */
    public function claim(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TorrentRequestClaim::class, 'request_id');
    }

    /**
     * Set The Requests Description After Its Been Purified.
     */
    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['description'] = htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Description And Return Valid HTML.
     */
    public function getDescriptionHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->description));
    }
}
