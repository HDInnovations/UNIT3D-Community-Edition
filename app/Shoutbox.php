<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Decoda\Decoda;

class Shoutbox extends Model
{

    protected $table = 'shoutbox';
    protected $fillable = ['user', 'message', 'mentions', 'messages'];

    /**
     * Get The Poster
     *
     * @access public
     * @return
     */
    public function poster()
    {
        return $this->belongsTo('App\User', 'user');
    }

    /**
     * Parse content and return valid HTML
     *
     */
    public static function getMessageHtml($message)
    {
        $code = new Decoda($message);
        $code->defaults();
        $code->setXhtml(false);
        $code->setStrict(false);
        $code->setLineBreaks(true);
        return $code->parse();
    }
}
