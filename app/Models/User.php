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
use App\Helpers\StringHelper;
use App\Traits\UsersOnlineTrait;
use Assada\Achievements\Achiever;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use voku\helper\AntiXSS;

class User extends Authenticatable implements MustVerifyEmail
{
    use Achiever;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;
    use UsersOnlineTrait;

    /**
     * The Attributes Excluded From The Model's JSON Form.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
        'password',
        'passkey',
        'rsskey',
        'remember_token',
        'api_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login'   => 'datetime',
        'last_action'  => 'datetime',
        'hidden'       => 'boolean',
        'can_comment'  => 'boolean',
        'can_download' => 'boolean',
        'can_request'  => 'boolean',
        'can_invite'   => 'boolean',
        'can_upload'   => 'boolean',
        'can_chat'     => 'boolean',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * ID of the system user.
     */
    final public const SYSTEM_USER_ID = 1;

    /**
     * Belongs To A Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Group, self>
     */
    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class)->withDefault([
            'color'        => config('user.group.defaults.color'),
            'effect'       => config('user.group.defaults.effect'),
            'icon'         => config('user.group.defaults.icon'),
            'name'         => config('user.group.defaults.name'),
            'slug'         => config('user.group.defaults.slug'),
            'position'     => config('user.group.defaults.position'),
            'is_admin'     => config('user.group.defaults.is_admin'),
            'is_freeleech' => config('user.group.defaults.is_freeleech'),
            'is_immune'    => config('user.group.defaults.is_immune'),
            'is_incognito' => config('user.group.defaults.is_incognito'),
            'is_internal'  => config('user.group.defaults.is_internal'),
            'is_modo'      => config('user.group.defaults.is_modo'),
            'is_trusted'   => config('user.group.defaults.is_trusted'),
            'can_upload'   => config('user.group.defaults.can_upload'),
            'level'        => config('user.group.defaults.level'),
        ]);
    }

    /**
     * Belongs To A Internal Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Internal, self>
     */
    public function internal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Internal::class, 'internal_id', 'id', 'name');
    }

    /**
     * Belongs To A Chatroom.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Chatroom, self>
     */
    public function chatroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chatroom::class);
    }

    /**
     * Belongs To A Chat Status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<ChatStatus, self>
     */
    public function chatStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChatStatus::class, 'chat_status_id', 'id');
    }

    /**
     * Belongs To Many Bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Torrent>
     */
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Torrent::class, 'bookmarks', 'user_id', 'torrent_id')->withTimestamps();
    }

    /**
     * Belongs To Many Seeding Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Torrent>
     */
    public function seedingTorrents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Torrent::class, 'history')
            ->wherePivot('active', '=', 1)
            ->wherePivot('seeder', '=', 1);
    }

    /**
     * Belongs To Many Leeching Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Torrent>
     */
    public function leechingTorrents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Torrent::class, 'history')
            ->wherePivot('active', '=', 1)
            ->wherePivot('seeder', '=', 0);
    }

    /**
     * Belongs to many followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'target_id', 'user_id')
            ->as('follow')
            ->withTimestamps();
    }

    /**
     * Belongs to many connectable seeding torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Torrent>
     */
    public function connectableSeedingTorrents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Torrent::class, 'peers')
            ->wherePivot('seeder', '=', 1)
            ->wherePivot('connectable', '=', true);
    }

    /**
     * Belongs to many followees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function following(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'target_id')
            ->as('follow')
            ->withTimestamps();
    }

    /**
     * Has Many Messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Message>
     */
    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Has One Privacy Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<UserPrivacy>
     */
    public function privacy(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserPrivacy::class);
    }

    /**
     * Has One Notifications Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<UserNotification>
     */
    public function notification(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserNotification::class);
    }

    /**
     * Has One Watchlist Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Watchlist>
     */
    public function watchlist(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Watchlist::class);
    }

    /**
     * Has Many RSS Feeds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Rss>
     */
    public function rss(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rss::class);
    }

    /**
     * Has Many Echo Settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<UserEcho>
     */
    public function echoes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserEcho::class);
    }

    /**
     * Has Many Audible Settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<UserAudible>
     */
    public function audibles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAudible::class);
    }

    /**
     * Has Many Thanks Given.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Thank>
     */
    public function thanksGiven(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Thank::class, 'user_id', 'id');
    }

    /**
     * Has Many Wish's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Wish>
     */
    public function wishes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wish::class);
    }

    /**
     * Has Many Thanks Received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<Thank>
     */
    public function thanksReceived(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Thank::class, Torrent::class);
    }

    /**
     * Has Many Polls.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Poll>
     */
    public function polls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Poll::class);
    }

    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent>
     */
    public function torrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class);
    }

    /**
     * Has Many Playlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Playlist>
     */
    public function playlists(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * Has Many Sent PM's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<PrivateMessage>
     */
    public function sentPrivateMessages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrivateMessage::class, 'sender_id');
    }

    /**
     * Has Many Received PM's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<PrivateMessage>
     */
    public function receivedPrivateMessages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrivateMessage::class, 'receiver_id');
    }

    /**
     * Has Many Peers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer>
     */
    public function peers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Has Many Articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Article>
     */
    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Has Many Topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Topic>
     */
    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class, 'first_post_user_id', 'id');
    }

    /**
     * Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Post>
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Has Many Comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Comment>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Has Many Torrent Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest>
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class);
    }

    /**
     * Has Approved Many Torrent Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest>
     */
    public function ApprovedRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class, 'approved_by');
    }

    /**
     * Has Filled Many Torrent Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest>
     */
    public function FilledRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class, 'filled_by');
    }

    /**
     * Has Many Torrent Request BON Bounties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequestBounty>
     */
    public function requestBounty(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequestBounty::class);
    }

    /**
     * Has Moderated Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent>
     */
    public function moderated(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class, 'moderated_by');
    }

    /**
     * Has Many Notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Note>
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Note::class, 'user_id');
    }

    /**
     * Has Many Reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Report>
     */
    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Has Solved Many Reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Report>
     */
    public function solvedReports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class, 'staff_id');
    }

    /**
     * Has Many Torrent History.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<History>
     */
    public function history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(History::class, 'user_id');
    }

    /**
     * Has Many Bans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Ban>
     */
    public function userban(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ban::class, 'owned_by');
    }

    /**
     * Has Given Many Bans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Ban>
     */
    public function staffban(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ban::class, 'created_by');
    }

    /**
     * Has Given Many Warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Warning>
     */
    public function staffwarning(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class, 'warned_by');
    }

    /**
     * Has Deleted Many Warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Warning>
     */
    public function staffdeletedwarning(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class, 'deleted_by');
    }

    /**
     * Has Many Warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Warning>
     */
    public function userwarning(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class, 'user_id');
    }

    /**
     * Has Given Many Invites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Invite>
     */
    public function sentInvites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invite::class, 'user_id');
    }

    /**
     * Has Received Many Invites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Invite>
     */
    public function receivedInvites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invite::class, 'accepted_by');
    }

    /**
     * Has Many Featured Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FeaturedTorrent>
     */
    public function featuredTorrent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeaturedTorrent::class);
    }

    /**
     * Has Many Post Likes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Like>
     */
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Has Given Many BON Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function bonGiven(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'sender_id');
    }

    /**
     * Has Received Many BON Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function bonReceived(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'receiver_id');
    }

    /**
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subscription>
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Has Many Resurrections.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Resurrection>
     */
    public function resurrections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Resurrection::class);
    }

    /**
     * Has Many Subscribed topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Forum>
     */
    public function subscribedForums(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Forum::class, 'subscriptions');
    }

    /**
     * Has Many Subscribed topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Topic>
     */
    public function subscribedTopics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'subscriptions');
    }

    /**
     * Has many free leech tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FreeleechToken>
     */
    public function freeleechTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FreeleechToken::class);
    }

    /**
     * Has many warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Warning>
     */
    public function warnings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class);
    }

    /**
     * Has Many Tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Ticket>
     */
    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /**
     * Has Many Personal Freeleeches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<PersonalFreeleech>
     */
    public function personalFreeleeches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PersonalFreeleech::class);
    }

    /**
     * Has many failed logins.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FailedLoginAttempt>
     */
    public function failedLogins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FailedLoginAttempt::class);
    }

    /**
     * Has many upload snatches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<History>
     */
    public function uploadSnatches(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(History::class, Torrent::class)->whereNotNull('completed_at');
    }

    /**
     * Has many sent gifts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function sentGifts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'sender_id')->where('name', '=', 'gift');
    }

    /**
     * Has many received gifts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function receivedGifts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'receiver_id')->where('name', '=', 'gift');
    }

    /**
     * Has many sent tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function sentTips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'sender_id')->where('name', '=', 'tip');
    }

    /**
     * Has many received tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BonTransactions>
     */
    public function receivedTips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'receiver_id')->where('name', '=', 'tip');
    }

    /**
     * Has many seedboxes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Seedbox>
     */
    public function seedboxes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Seedbox::class);
    }

    /**
     * Has one application.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough<Application>
     */
    public function application(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(Application::class, Invite::class, 'accepted_by', 'email', 'id', 'email');
    }

    /**
     * Has many passkeys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Passkey>
     */
    public function passkeys(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Passkey::class);
    }

    /**
     * Has many rsskeys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Rsskey>
     */
    public function rsskeys(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rsskey::class);
    }

    /**
     * Has many apikeys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Apikey>
     */
    public function apikeys(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Apikey::class);
    }

    /**
     * Has many email updates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<EmailUpdate>
     */
    public function emailUpdates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmailUpdate::class);
    }

    /**
     * Get the Users accepts notification as bool.
     */
    public function acceptsNotification(self $sender, self $target, string $group = 'follower', bool|string $type = false): bool
    {
        $targetGroup = 'json_'.$group.'_groups';

        if ($sender->id === $target->id) {
            return false;
        }

        if ($sender->group->is_modo || $sender->group->is_admin) {
            return true;
        }

        if ($target->block_notifications == 1) {
            return false;
        }

        if ($target->notification && $type && (!$target->notification->$type)) {
            return false;
        }

        if (\is_array($target->notification?->$targetGroup)) {
            return !\in_array($sender->group->id, $target->notification->$targetGroup, true);
        }

        return true;
    }

    /**
     * Get the Users allowed answer as bool.
     */
    public function isVisible(self $target, string $group = 'profile', bool|string $type = false): bool
    {
        $targetGroup = 'json_'.$group.'_groups';
        $sender = auth()->user();

        if ($sender->id == $target->id) {
            return true;
        }

        if ($sender->group->is_modo || $sender->group->is_admin) {
            return true;
        }

        if ($target->getAttribute('hidden')) {
            return false;
        }

        if ($target->privacy && $type && (!$target->privacy->$type || $target->privacy->$type == 0)) {
            return false;
        }

        if (\is_array($target->privacy?->$targetGroup)) {
            return !\in_array($sender->group->id, $target->privacy->$targetGroup);
        }

        return true;
    }

    /**
     * Get the Users allowed answer as bool.
     */
    public function isAllowed(self $target, string $group = 'profile', bool|string $type = false): bool
    {
        $targetGroup = 'json_'.$group.'_groups';
        $sender = auth()->user();

        if ($sender->id == $target->id) {
            return true;
        }

        if ($sender->group->is_modo || $sender->group->is_admin) {
            return true;
        }

        if ($target->private_profile == 1) {
            return false;
        }

        if ($target->privacy && $type && (!$target->privacy->$type || $target->privacy->$type == 0)) {
            return false;
        }

        if (\is_array($target->privacy?->$targetGroup)) {
            return !\in_array($sender->group->id, $target->privacy->$targetGroup);
        }

        return true;
    }

    /**
     * Return Upload In Human Format.
     */
    public function getFormattedUploadedAttribute(): string
    {
        $bytes = $this->uploaded;

        if ($bytes > 0) {
            return StringHelper::formatBytes((float) $bytes, 2);
        }

        return StringHelper::formatBytes(0, 2);
    }

    /**
     * Return Download In Human Format.
     */
    public function getFormattedDownloadedAttribute(): string
    {
        $bytes = $this->downloaded;

        if ($bytes > 0) {
            return StringHelper::formatBytes((float) $bytes, 2);
        }

        return StringHelper::formatBytes(0, 2);
    }

    /**
     * Return The Ratio.
     */
    public function getRatioAttribute(): float
    {
        if ($this->downloaded === 0) {
            return INF;
        }

        return round($this->uploaded / $this->downloaded, 2);
    }

    public function getFormattedRatioAttribute(): string
    {
        $ratio = $this->ratio;

        if (is_infinite($ratio)) {
            return '∞';
        }

        return (string) $ratio;
    }

    /**
     * Return the size (pretty formatted) which can be safely downloaded
     * without falling under the minimum ratio.
     */
    public function getFormattedBufferAttribute(): string
    {
        if (config('other.ratio') === 0) {
            return '∞';
        }

        $bytes = round(($this->uploaded / config('other.ratio')) - $this->downloaded);

        return StringHelper::formatBytes($bytes);
    }

    /**
     * Set The Users Signature After It's Been Purified.
     */
    public function setSignatureAttribute(?string $value): void
    {
        $this->attributes['signature'] = htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Returns the HTML of the user's signature.
     */
    public function getSignatureHtmlAttribute(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->signature));
    }

    /**
     * Set The Users About Me After It's Been Purified.
     */
    public function setAboutAttribute(?string $value): void
    {
        $this->attributes['about'] = htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse About Me And Return Valid HTML.
     */
    public function getAboutHtmlAttribute(): string
    {
        if (empty($this->about)) {
            return 'N/A';
        }

        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->about));
    }

    /**
     * Formats the seed bonus points of the User.
     */
    public function getFormattedSeedbonusAttribute(): string
    {
        return number_format($this->seedbonus, 0, null, "\u{202F}");
    }

    /**
     * Make sure that password reset emails are sent after the user has sent a
     * password reset request, that way an attacker can't use the timing to
     * determine if an email was sent or not.
     *
     * @param       $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        dispatch(fn () => $this->notify(new ResetPassword($token)))->afterResponse();
    }
}
