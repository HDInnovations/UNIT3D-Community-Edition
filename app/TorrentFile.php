<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     BluCrew
 */
 
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Template for torrent files
 *
 *
 */
class TorrentFile extends Model
{

    /**
     * DB Table
     *
     */
    protected $table = 'files';

    /**
     * Disable dates when backing up
     *
     */
    public $timestamps = false;

    /**
     * Belongs to Torrent
     *
     *
     */
    public function torrent()
    {
        return $this->belongsTo(\App\Torrent::class);
    }

    /**
    * Return Size In Human Format
    *
    */
    public function getSize($bytes = null, $precision = 2)
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
