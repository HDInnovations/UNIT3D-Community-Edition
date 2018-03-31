<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use App\Ban;
use App\Warning;
use App\Peer;
use App\History;
use Cache;
use Gstt\Achievements\Achiever;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function theodorejb\polycast\to_int;
use App\Helpers\StringHelper;
use App\Helpers\Bbcode;

/**
 * User-Related Template
 *
 */
class User extends Authenticatable
{
    use Notifiable;
    use Achiever;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $fillable = ['name', 'email', 'password'];

    protected $dates = ['last_login'];

    /**
     * Thanks Given
     *
     */
    public function thanksGiven()
    {
        return $this->hasMany(Thank::class, 'user_id', 'id');
    }

    /**
     * Thanks Received
     *
     */
    public function thanksReceived()
    {
        return $this->hasManyThrough(Thank::class, Torrent::class);
    }

    /**
     * Is Online?
     *
     */
    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Polls
     *
     */
    public function polls()
    {
        return $this->hasMany(\App\Poll::class);
    }

    /**
     * Belongs to group
     *
     */
    public function group()
    {
        return $this->belongsTo(\App\Group::class);
    }

    /**
     * Has many torrents
     *
     */
    public function torrents()
    {
        return $this->hasMany(\App\Torrent::class);
    }

    /**
     * Has send many pms
     *
     */
    public function pm_sender()
    {
        return $this->hasMany(\App\PrivateMessage::class, "sender_id");
    }

    /**
     * Has received many pms
     *
     */
    public function pm_receiver()
    {
        return $this->hasMany(\App\PrivateMessage::class, "reciever_id");
    }

    /**
     * Has many peers
     *
     */
    public function peers()
    {
        return $this->hasMany(\App\Peer::class);
    }

    /**
     * Has many follow
     *
     */
    public function follows()
    {
        return $this->hasMany(\App\Follow::class);
    }

    /**
     * Has many articles
     *
     */
    public function articles()
    {
        return $this->hasMany(\App\Article::class);
    }

    /**
    * Has Many Topics
    *
    */
    public function topics()
    {
        return $this->hasMany(Topic::class, 'first_post_user_id', 'id');
    }

    /**
     * Has many posts
     *
     */
    public function posts()
    {
        return $this->hasMany(\App\Post::class);
    }

    /**
     * Has many Comment
     *
     */
    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    /**
     * Has many created requests
     *
     */
    public function requests()
    {
        return $this->hasMany(\App\TorrentRequest::class);
    }

    /**
     * Has approved many requests
     *
     */
    public function ApprovedRequests()
    {
        return $this->hasMany(\App\TorrentRequest::class, 'approved_by');
    }

    /**
     * Has filled many requests
     *
     */
    public function FilledRequests()
    {
        return $this->hasMany(\App\TorrentRequest::class, 'filled_by');
    }

    /**
     * Has many request Bounties
     *
     */
    public function requestBounty()
    {
        return $this->hasMany(\App\TorrentRequestBounty::class);
    }

    /**
     * Has moderated many torrents
     *
     */
    public function moderated()
    {
        return $this->hasMany(\App\Torrent::class, 'moderated_by');
    }

    /**
     * Has many Notes
     *
     */
    public function notes()
    {
        return $this->hasMany(\App\Note::class, 'user_id');
    }

    /**
     * Has many Reports
     *
     */
    public function reports()
    {
        return $this->hasMany(\App\Report::class, 'reporter_id');
    }

    /**
     * Has many solvedReports
     *
     */
    public function solvedReports()
    {
        return $this->hasMany(\App\Report::class, 'staff_id');
    }

    /**
     * Get all of bookmarks for the user.
     */
    public function bookmarks()
    {
        return $this->belongsToMany(\App\Torrent::class, 'bookmarks', 'user_id', 'torrent_id')->withTimeStamps();
    }

    public function hasBookmarked($torrent_id)
    {
        return $this->bookmarks()->where('torrent_id', '=', $torrent_id)->first() !== null;
    }

    /**
     * Get all of follows for the user.
     */
    public function isFollowing($target_id)
    {
        return (bool)$this->follows()->where('target_id', $target_id)->first(['id']);
    }

    /*
    * Get all history records for the user.
    */
    public function history()
    {
        return $this->hasMany(\App\History::class, "user_id");
    }

    /*
    * Get all records of user bans.
    */
    public function userban()
    {
        return $this->hasMany(\App\Ban::class, "owned_by");
    }

    /*
    * Get all the bans a staff member has actioned.
    */
    public function staffban()
    {
        return $this->hasMany(\App\Ban::class, "created_by");
    }

    public function staffwarning()
    {
        return $this->hasMany(\App\Warning::class, 'warned_by');
    }

    public function userwarning()
    {
        return $this->hasMany(\App\Warning::class, 'user_id');
    }

    /**
     * Has many invites
     *
     */
    public function sentInvite()
    {
        return $this->hasMany(\App\Invite::class, 'user_id');
    }

    /**
     * Has many invites
     *
     */
    public function recievedInvite()
    {
        return $this->hasMany(\App\Invite::class, 'accepted_by');
    }

    /**
     * Has many featured
     *
     */
    public function featuredTorrent()
    {
        return $this->hasMany(\App\FeaturedTorrent::class);
    }

    /**
     * Has many likes
     *
     */
    public function likes()
    {
        return $this->hasMany(\App\Like::class);
    }

    /**
     * Return Upload In Human Format
     */
    public function getUploaded($bytes = null, $precision = 2)
    {
        $bytes = $this->uploaded;
        return StringHelper::formatBytes($bytes, 2);
    }

    /**
     * Return Download In Human Format
     *
     */
    public function getDownloaded($bytes = null, $precision = 2)
    {
        $bytes = $this->downloaded;
        return StringHelper::formatBytes($bytes, 2);
    }

    /**
     * Return The Ratio
     *
     */
    public function getRatio()
    {
        if ($this->downloaded == 0) {
            return INF;
        }
        return (float)round($this->uploaded / $this->downloaded, 2);
    }

    // Return the ratio pretty formated as a string.
    public function getRatioString()
    {
        $ratio = $this->getRatio();
        if (is_infinite($ratio)) {
            return "∞";
        } else {
            return (string)$ratio;
        }
    }

    // Return the ratio after $size bytes would be downloaded.
    public function ratioAfterSize($size)
    {
        if ($this->downloaded + $size == 0) {
            return INF;
        }
        return (float)round($this->uploaded / ($this->downloaded + $size), 2);
    }

    // Return the ratio after $size bytes would be downloaded, pretty formatted
    // as a string.
    public function ratioAfterSizeString($size, $freeleech = false)
    {
        if ($freeleech) {
            return $this->getRatioString() . " (Freeleech)";
        }

        $ratio = $this->ratioAfterSize($size);
        if (is_infinite($ratio)) {
            return "∞";
        } else {
            return (string)$ratio;
        }
    }

    // Return the size (pretty formated) which can be safely downloaded
    // without falling under the minimum ratio.
    public function untilRatio($ratio)
    {
        if ($ratio == 0.0) {
            return "∞";
        }

        $bytes = to_int(round($this->uploaded / $ratio));
        return StringHelper::formatBytes($bytes);
    }

    /**
     * Returns the HTML of the user's signature
     *
     * @access public
     * @return string html
     */
    public function getSignature()
    {
        return Bbcode::parse($this->signature);
    }

    /**
     * Parse content and return valid HTML
     *
     */
    public function getAboutHtml()
    {
        return Bbcode::parse($this->about);
    }

    /**
     * @method getSeedbonus
     *
     * Formats the seebonus of the User
     *
     * @access public
     * @return decimal
     */
    public function getSeedbonus()
    {
        return number_format($this->seedbonus, 2, '.', ' ');
    }

    /**
     * @method getSeeding
     *
     * Gets the amount of torrents a user seeds
     *
     * @access public
     * @return integer
     */
    public function getSeeding()
    {
        return Peer::where('user_id', '=', $this->id)
            ->where('seeder', '=', '1')
            ->distinct('hash')
            ->count();
    }

    /**
     * @method getSeeding
     *
     * Gets the amount of torrents a user seeds
     *
     * @access public
     * @return integer
     */
    public function getUploads()
    {
        return Torrent::withAnyStatus()
            ->where('user_id', '=', $this->id)
            ->count();
    }

    /**
     * @method getLeeching
     *
     * Gets the amount of torrents a user seeds
     *
     * @access public
     * @return integer
     */
    public function getLeeching()
    {
        return Peer::where('user_id', '=', $this->id)
            ->where('left', '>', '0')
            ->distinct('hash')
            ->count();
    }

    /**
     * @method getWarning
     *
     * Gets count on users active warnings
     *
     * @access public
     * @return integer
     */
    public function getWarning()
    {
        return Warning::where('user_id', '=', $this->id)
            ->whereNotNull('torrent')
            ->where('active', '=', '1')
            ->count();
    }

    /**
     * @method getTotalSeedTime
     *
     * Gets the users total seedtime
     *
     * @access public
     * @return integer
     */
    public function getTotalSeedTime()
    {
        return History::where('user_id', '=', $this->id)
            ->sum('seedtime');
    }
}
