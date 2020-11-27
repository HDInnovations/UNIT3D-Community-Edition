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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use voku\helper\AntiXSS;
class User extends \Illuminate\Foundation\Auth\User
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Notifications\Notifiable;
    use \Assada\Achievements\Achiever;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \App\Traits\UsersOnlineTrait;
    /**
     * The Attributes Excluded From The Model's JSON Form.
     *
     * @var array
     */
    protected $hidden = ['email', 'password', 'passkey', 'rsskey', 'remember_token', 'api_token'];
    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array
     */
    protected $dates = ['last_login', 'last_action'];
    /**
     * Belongs To A Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class)->withDefault(['color' => \config('user.group.defaults.color'), 'effect' => \config('user.group.defaults.effect'), 'icon' => \config('user.group.defaults.icon'), 'name' => \config('user.group.defaults.name'), 'slug' => \config('user.group.defaults.slug'), 'position' => \config('user.group.defaults.position'), 'is_admin' => \config('user.group.defaults.is_admin'), 'is_freeleech' => \config('user.group.defaults.is_freeleech'), 'is_immune' => \config('user.group.defaults.is_immune'), 'is_incognito' => \config('user.group.defaults.is_incognito'), 'is_internal' => \config('user.group.defaults.is_internal'), 'is_modo' => \config('user.group.defaults.is_modo'), 'is_trusted' => \config('user.group.defaults.is_trusted'), 'can_upload' => \config('user.group.defaults.can_upload'), 'level' => \config('user.group.defaults.level')]);
    }
    /**
     * Belongs To A Chatroom.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatroom()
    {
        return $this->belongsTo(\App\Models\Chatroom::class);
    }
    /**
     * Belongs To A Chat Status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatStatus()
    {
        return $this->belongsTo(\App\Models\ChatStatus::class, 'chat_status_id', 'id');
    }
    /**
     * Belongs To Many Bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarks()
    {
        return $this->belongsToMany(\App\Models\Torrent::class, 'bookmarks', 'user_id', 'torrent_id')->withTimeStamps();
    }
    /**
     * @param $torrent_id
     *
     * @return bool
     */
    public function isBookmarked($torrent_id)
    {
        return $this->bookmarks()->where('torrent_id', '=', $torrent_id)->first() !== null;
    }
    /**
     * Has Many Messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }
    /**
     * Has One Privacy Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function privacy()
    {
        return $this->hasOne(\App\Models\UserPrivacy::class);
    }
    /**
     * Has One Chat Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chat()
    {
        return $this->hasOne(\App\Models\UserChat::class);
    }
    /**
     * Has One Notifications Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function notification()
    {
        return $this->hasOne(\App\Models\UserNotification::class);
    }
    /**
     * Has Many RSS Feeds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rss()
    {
        return $this->hasMany(\App\Models\Rss::class);
    }
    /**
     * Has Many Echo Settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function echoes()
    {
        return $this->hasMany(\App\Models\UserEcho::class);
    }
    /**
     * Has Many Audible Settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function audibles()
    {
        return $this->hasMany(\App\Models\UserAudible::class);
    }
    /**
     * Has Many Thanks Given.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thanksGiven()
    {
        return $this->hasMany(\App\Models\Thank::class, 'user_id', 'id');
    }
    /**
     * Has Many Wish's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishes()
    {
        return $this->hasMany(\App\Models\Wish::class);
    }
    /**
     * Has Many Thanks Received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function thanksReceived()
    {
        return $this->hasManyThrough(\App\Models\Thank::class, \App\Models\Torrent::class);
    }
    /**
     * Has Many Polls.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function polls()
    {
        return $this->hasMany(\App\Models\Poll::class);
    }
    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function torrents()
    {
        return $this->hasMany(\App\Models\Torrent::class);
    }
    /**
     * Has Many Playlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playlists()
    {
        return $this->hasMany(\App\Models\Playlist::class);
    }
    /**
     * Has Many Sent PM's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pm_sender()
    {
        return $this->hasMany(\App\Models\PrivateMessage::class, 'sender_id');
    }
    /**
     * Has Many Received PM's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pm_receiver()
    {
        return $this->hasMany(\App\Models\PrivateMessage::class, 'receiver_id');
    }
    /**
     * Has Many Peers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function peers()
    {
        return $this->hasMany(\App\Models\Peer::class);
    }
    /**
     * Has Many Followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function follows()
    {
        return $this->hasMany(\App\Models\Follow::class);
    }
    /**
     * Has Many Articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(\App\Models\Article::class);
    }
    /**
     * Has Many Topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(\App\Models\Topic::class, 'first_post_user_id', 'id');
    }
    /**
     * Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }
    /**
     * Has Many Comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }
    /**
     * Has Many Torrent Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(\App\Models\TorrentRequest::class);
    }
    /**
     * Has Approved Many Torrent Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ApprovedRequests()
    {
        return $this->hasMany(\App\Models\TorrentRequest::class, 'approved_by');
    }
    /**
     * Has Filled Many Torrent Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function FilledRequests()
    {
        return $this->hasMany(\App\Models\TorrentRequest::class, 'filled_by');
    }
    /**
     * Has Many Torrent Request BON Bounties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestBounty()
    {
        return $this->hasMany(\App\Models\TorrentRequestBounty::class);
    }
    /**
     * Has Moderated Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moderated()
    {
        return $this->hasMany(\App\Models\Torrent::class, 'moderated_by');
    }
    /**
     * Has Many Notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(\App\Models\Note::class, 'user_id');
    }
    /**
     * Has Many Reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(\App\Models\Report::class, 'reporter_id');
    }
    /**
     * Has Solved Many Reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function solvedReports()
    {
        return $this->hasMany(\App\Models\Report::class, 'staff_id');
    }
    /**
     * Has Many Torrent History.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(\App\Models\History::class, 'user_id');
    }
    /**
     * Has Many Bans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userban()
    {
        return $this->hasMany(\App\Models\Ban::class, 'owned_by');
    }
    /**
     * Has Given Many Bans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffban()
    {
        return $this->hasMany(\App\Models\Ban::class, 'created_by');
    }
    /**
     * Has Given Many Warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffwarning()
    {
        return $this->hasMany(\App\Models\Warning::class, 'warned_by');
    }
    /**
     * Has Deleted Many Warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffdeletedwarning()
    {
        return $this->hasMany(\App\Models\Warning::class, 'deleted_by');
    }
    /**
     * Has Many Warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userwarning()
    {
        return $this->hasMany(\App\Models\Warning::class, 'user_id');
    }
    /**
     * Has Given Many Invites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentInvite()
    {
        return $this->hasMany(\App\Models\Invite::class, 'user_id');
    }
    /**
     * Has Received Many Invites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedInvite()
    {
        return $this->hasMany(\App\Models\Invite::class, 'accepted_by');
    }
    /**
     * Has Many Featured Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function featuredTorrent()
    {
        return $this->hasMany(\App\Models\FeaturedTorrent::class);
    }
    /**
     * Has Many Post Likes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }
    /**
     * Has Given Many BON Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonGiven()
    {
        return $this->hasMany(\App\Models\BonTransactions::class, 'sender');
    }
    /**
     * Has Received Many BON Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonReceived()
    {
        return $this->hasMany(\App\Models\BonTransactions::class, 'receiver');
    }
    /**
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }
    /**
     * Has many free leech tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function freeleechTokens()
    {
        return $this->hasMany(\App\Models\FreeleechToken::class);
    }
    /**
     * Has many warnings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warnings()
    {
        return $this->hasMany(\App\Models\Warning::class);
    }
    /**
     * Get the Users username as slug.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->username);
    }
    /**
     * Get the Users accepts notification as bool.
     *
     * @param self   $sender
     * @param self   $target
     * @param string $group
     * @param bool   $type
     *
     * @return int
     */
    public function acceptsNotification(self $sender, self $target, $group = 'follower', $type = false)
    {
        $target_group = 'json_' . $group . '_groups';
        if ($sender->id === $target->id) {
            return false;
        }
        if ($sender->group->is_modo || $sender->group->is_admin) {
            return true;
        }
        if ($target->block_notifications && $target->block_notifications == 1) {
            return false;
        }
        if ($target->notification && $type && !$target->notification->{$type}) {
            return false;
        }
        if ($target->notification && $target->notification->{$target_group} && \is_array($target->notification->{$target_group}['default_groups'])) {
            if (\array_key_exists($sender->group->id, $target->notification->{$target_group}['default_groups'])) {
                return $target->notification->{$target_group}['default_groups'][$sender->group->id] == 1;
            }
            return true;
        }
        return true;
    }
    /**
     * Get the Users allowed answer as bool.
     *
     * @param self   $target
     * @param string $group
     * @param bool   $type
     *
     * @return int
     */
    public function isVisible(self $target, $group = 'profile', $type = false)
    {
        $target_group = 'json_' . $group . '_groups';
        $sender = \auth()->user();
        if ($sender->id == $target->id) {
            return true;
        }
        if ($sender->group->is_modo || $sender->group->is_admin) {
            return true;
        }
        if ($target->hidden && $target->hidden == 1) {
            return false;
        }
        if ($target->privacy && $type && (!$target->privacy->{$type} || $target->privacy->{$type} == 0)) {
            return false;
        }
        if ($target->privacy && $target->privacy->{$target_group} && \is_array($target->privacy->{$target_group}['default_groups'])) {
            if (\array_key_exists($sender->group->id, $target->privacy->{$target_group}['default_groups'])) {
                return $target->privacy->{$target_group}['default_groups'][$sender->group->id] == 1;
            }
            return true;
        }
        return true;
    }
    /**
     * Get the Users allowed answer as bool.
     *
     * @param self   $target
     * @param string $group
     * @param bool   $type
     *
     * @return int
     */
    public function isAllowed(self $target, $group = 'profile', $type = false)
    {
        $target_group = 'json_' . $group . '_groups';
        $sender = \auth()->user();
        if ($sender->id == $target->id) {
            return true;
        }
        if ($sender->group->is_modo || $sender->group->is_admin) {
            return true;
        }
        if ($target->private_profile && $target->private_profile == 1) {
            return false;
        }
        if ($target->privacy && $type && (!$target->privacy->{$type} || $target->privacy->{$type} == 0)) {
            return false;
        }
        if ($target->privacy && $target->privacy->{$target_group} && \is_array($target->privacy->{$target_group}['default_groups'])) {
            if (\array_key_exists($sender->group->id, $target->privacy->{$target_group}['default_groups'])) {
                return $target->privacy->{$target_group}['default_groups'][$sender->group->id] == 1;
            }
            return true;
        }
        return true;
    }
    /**
     * Does Subscription Exist.
     *
     * @param $type
     * @param $topic_id
     *
     * @return string
     */
    public function isSubscribed(string $type, $topic_id)
    {
        if ($type === 'topic') {
            return (bool) $this->subscriptions()->where('topic_id', '=', $topic_id)->first(['id']);
        }
        return (bool) $this->subscriptions()->where('forum_id', '=', $topic_id)->first(['id']);
    }
    /**
     * Get All Followers Of A User.
     *
     * @param $target_id
     *
     * @return string
     */
    public function isFollowing($target_id)
    {
        return (bool) $this->follows()->where('target_id', '=', $target_id)->first(['id']);
    }
    /**
     * Return Upload In Human Format.
     *
     * @param null $bytes
     * @param int  $precision
     *
     * @return string
     */
    public function getUploaded($bytes = null, $precision = 2)
    {
        $bytes = $this->uploaded;
        if ($bytes > 0) {
            return \App\Helpers\StringHelper::formatBytes((float) $bytes, 2);
        }
        return \App\Helpers\StringHelper::formatBytes(0, 2);
    }
    /**
     * Return Download In Human Format.
     *
     * @param null $bytes
     * @param int  $precision
     *
     * @return string
     */
    public function getDownloaded($bytes = null, $precision = 2)
    {
        $bytes = $this->downloaded;
        if ($bytes > 0) {
            return \App\Helpers\StringHelper::formatBytes((float) $bytes, 2);
        }
        return \App\Helpers\StringHelper::formatBytes(0, 2);
    }
    /**
     * Return The Ratio.
     */
    public function getRatio()
    {
        if ($this->downloaded === 0) {
            return INF;
        }
        return \round($this->uploaded / $this->downloaded, 2);
    }
    // Return the ratio pretty formated as a string.
    /**
     * @return string
     */
    public function getRatioString()
    {
        $ratio = $this->getRatio();
        if (\is_infinite($ratio)) {
            return '∞';
        }
        return (string) $ratio;
    }
    // Return the ratio after $size bytes would be downloaded.
    /**
     * @param $size
     *
     * @return float
     */
    public function ratioAfterSize($size)
    {
        if ($this->downloaded + $size == 0) {
            return INF;
        }
        return \round($this->uploaded / ($this->downloaded + $size), 2);
    }
    // Return the ratio after $size bytes would be downloaded, pretty formatted
    // as a string.
    /**
     * @param      $size
     * @param bool $freeleech
     *
     * @return string
     */
    public function ratioAfterSizeString($size, $freeleech = false)
    {
        if ($freeleech) {
            return $this->getRatioString() . ' (' . \trans('torrent.freeleech') . ')';
        }
        $ratio = $this->ratioAfterSize($size);
        if (\is_infinite($ratio)) {
            return '∞';
        }
        return (string) $ratio;
    }
    // Return the size (pretty formated) which can be safely downloaded
    // without falling under the minimum ratio.
    /**
     * @param $ratio
     *
     * @return string
     */
    public function untilRatio($ratio)
    {
        if ($ratio == 0.0) {
            return '∞';
        }
        $bytes = \round($this->uploaded / $ratio - $this->downloaded);
        return \App\Helpers\StringHelper::formatBytes($bytes);
    }
    /**
     * Set The Users Signature After Its Been Purified.
     *
     * @param string $value
     *
     * @return void
     */
    public function setSignatureAttribute($value)
    {
        $antiXss = new \voku\helper\AntiXSS();
        $this->attributes['signature'] = $antiXss->xss_clean($value);
    }
    /**
     * Returns the HTML of the user's signature.
     *
     * @return string html
     */
    public function getSignature()
    {
        $bbcode = new \App\Helpers\Bbcode();
        $linkify = new \App\Helpers\Linkify();
        return $bbcode->parse($linkify->linky($this->signature), true);
    }
    /**
     * Set The Users About Me After Its Been Purified.
     *
     * @param string $value
     *
     * @return void
     */
    public function setAboutAttribute($value)
    {
        $antiXss = new \voku\helper\AntiXSS();
        $this->attributes['about'] = $antiXss->xss_clean($value);
    }
    /**
     * Parse About Me And Return Valid HTML.
     *
     * @return string Parsed BBCODE To HTML
     */
    public function getAboutHtml()
    {
        if (empty($this->about)) {
            return 'N/A';
        }
        $bbcode = new \App\Helpers\Bbcode();
        $linkify = new \App\Helpers\Linkify();
        return $bbcode->parse($linkify->linky($this->about), true);
    }
    /**
     * @method getSeedbonus
     *
     * Formats the seebonus of the User
     *
     * @return decimal
     */
    public function getSeedbonus()
    {
        return \number_format($this->seedbonus, 2, '.', ' ');
    }
    /**
     * @method getSeeding
     *
     * Gets the amount of torrents a user seeds
     *
     * @return int
     */
    public function getSeeding()
    {
        return \App\Models\Peer::where('user_id', '=', $this->id)->where('seeder', '=', '1')->distinct('info_hash')->count();
    }
    /**
     * @method getLast30Uploads
     *
     * Gets the amount of torrents a user seeds
     *
     * @return int
     */
    public function getLast30Uploads()
    {
        $current = \Carbon\Carbon::now();
        return \App\Models\Torrent::withAnyStatus()->where('user_id', '=', $this->id)->where('created_at', '>', $current->copy()->subDays(30)->toDateTimeString())->count();
    }
    /**
     * @method getUploads
     *
     * Gets the amount of torrents a user seeds
     *
     * @return int
     */
    public function getUploads()
    {
        return \App\Models\Torrent::withAnyStatus()->where('user_id', '=', $this->id)->count();
    }
    /**
     * @method getLeeching
     *
     * Gets the amount of torrents a user seeds
     *
     * @return int
     */
    public function getLeeching()
    {
        return \App\Models\Peer::where('user_id', '=', $this->id)->where('left', '>', '0')->distinct('info_hash')->count();
    }
    /**
     * @method getWarning
     *
     * Gets count on users active warnings
     *
     * @return int
     */
    public function getWarning()
    {
        return \App\Models\Warning::where('user_id', '=', $this->id)->whereNotNull('torrent')->where('active', '=', '1')->count();
    }
    /**
     * @method getTotalSeedTime
     *
     * Gets the users total seedtime
     *
     * @return int
     */
    public function getTotalSeedTime()
    {
        return \App\Models\History::where('user_id', '=', $this->id)->sum('seedtime');
    }
    /**
     * @method getTotalSeedSize
     *
     * Gets the users total seedsoze
     *
     * @return int
     */
    public function getTotalSeedSize()
    {
        $peers = \App\Models\Peer::where('user_id', '=', $this->id)->where('seeder', '=', 1)->pluck('torrent_id');
        return \App\Models\Torrent::whereIn('id', $peers)->sum('size');
    }
}
