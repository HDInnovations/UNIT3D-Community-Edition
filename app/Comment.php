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

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Bbcode;

class Comment extends Model
{

    /**
     * Belongs to Torrent
     *
     */
    public function torrent()
    {
        return $this->belongsTo(\App\Torrent::class);
    }

    /**
     * Belongs to Article
     *
     */
    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }

    /**
     * Belongs to Request
     *
     */
    public function request()
    {
        return $this->belongsTo(\App\TorrentRequest::class);
    }

    /**
     * Belongs to User
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Parse content and return valid HTML
     *
     */
    public function getContentHtml()
    {
        return Bbcode::parse($this->content);
    }
}
