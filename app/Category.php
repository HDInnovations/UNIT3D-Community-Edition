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

class Category extends Model
{
    public $timestamps = false;

    /**
     * Validation rules
     *
     */
    public $rules = [
        'name' => 'required|unique:categories',
        'slug' => 'required|unique:categories',
        'image' => '',
        'description' => '',
    ];

    /**
     * Has many torrents
     *
     *
     */
    public function torrents()
    {
        return $this->hasMany(\App\Torrent::class);
    }

    /**
     * Has many requests
     *
     */
    public function requests()
    {
        return $this->hasMany(\App\Requests::class);
    }

    /**
     * Parse content and return valid HTML
     *
     */
    public function getContentHtml()
    {
        $code = new Decoda($this->content);
        $code->defaults();
        $code->setXhtml(false);
        $code->setStrict(false);
        $code->setLineBreaks(true);
        return $code->parse();
    }
}
