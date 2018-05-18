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

class Article extends Model
{
    /**
     * Belongs to User
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class)->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
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
     * Article Brief
     *
     * @access public
     * @param $length
     * @param ellipses
     * @param strip_html Remove HTML tags from string
     * @return string Formatted and cutted content
     *
     */
    public function getBrief($length = 100, $ellipses = true, $strip_html = false)
    {
        $input = $this->content;
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
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
