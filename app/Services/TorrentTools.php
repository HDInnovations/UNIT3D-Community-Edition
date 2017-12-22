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

namespace App\Services;

/**
 * Various tools for torrents
 *
 */
class TorrentTools
{

    /**
     * Name of the file to be saved
     *
     */
    public static $fileName = '';

    /**
     * Representative table of the decoded torrent
     *
     */
    public static $decodedTorrent = [];

    /**
     * Moves and decodes the torrent
     *
     * @access private
     */
    public static function moveAndDecode($torrentFile)
    {
        self::$fileName = uniqid() . '.torrent'; // Generate a unique name
        $torrentFile->move(getcwd() . '/files/torrents/', self::$fileName); // Move
        self::$decodedTorrent = Bencode::bdecode_file(getcwd() . '/files/torrents/' . self::$fileName);
    }

    /**
     * Calculate the number of files in the torrent
     *
     */
    public static function getFileCount($decodedTorrent)
    {
        // Multiple file torrent ?
        if (array_key_exists("files", $decodedTorrent['info']) && count($decodedTorrent['info']['files'])) {
            return count($decodedTorrent['info']['files']);
        }
        return 1;
    }

    /**
     * Returns the size of the torrent files
     *
     */
    public static function getTorrentSize($decodedTorrent)
    {
        $size = 0;
        if (array_key_exists("files", $decodedTorrent['info']) && count($decodedTorrent['info']['files'])) {
            foreach ($decodedTorrent['info']['files'] as $k => $file) {
                $dir = '';
                $size += $file['length'];
                $count = count($file["path"]);
            }
        } else {
            $size = $decodedTorrent['info']['length'];
            //$files[0] = $decodedTorrent['info']['name.utf-8'];
        }
        return $size;
    }

    /**
     * Returns the torrent file list
     *
     *
     */
    public static function getTorrentFiles($decodedTorrent)
    {
        if (array_key_exists("files", $decodedTorrent['info']) && count($decodedTorrent['info']['files'])) {
            foreach ($decodedTorrent['info']['files'] as $k => $file) {
                $dir = '';
                $count = count($file["path"]);
                for ($i = 0; $i < $count; $i++) {
                    if (($i + 1) == $count) {
                        $fname = $dir . $file["path"][$i];
                        $files[$k]['name'] = $fname;
                    } else {
                        $dir .= $file["path"][$i] . "/";
                        $files[$k]['name'] = $dir;
                    }
                    $files[$k]['size'] = $file['length'];
                }
            }
        } else {
            $files[0]['name'] = $decodedTorrent['info']['name'];
            $files[0]['size'] = $decodedTorrent['info']['length'];
        }
        return $files;
    }

    /**
     * Returns the sha1 (hash) of the torrent
     *
     */
    public static function getTorrentHash($decodedTorrent)
    {
        return sha1(Bencode::bencode($decodedTorrent['info']));
    }

    /**
     * Returns the number of the torrent file
     *
     */
    public static function getTorrentFileCount($decodedTorrent)
    {
        if (array_key_exists("files", $decodedTorrent['info'])) {
            return count($decodedTorrent['info']['files']);
        }
        return 1;
    }

    /**
     * Returns the NFO
     *
     */
    public static function getNfo($inputFile)
    {
        $fileName = uniqid() . '.nfo';
        $inputFile->move(getcwd() . '/files/tmp/', $fileName);
        if (file_exists(getcwd() . '/files/tmp/' . $fileName)) {
            $fileContent = file_get_contents(getcwd() . '/files/tmp/' . $fileName);
            unlink(getcwd() . '/files/tmp/' . $fileName);
        } else {
            $fileContent = null;
        }
        return $fileContent;
    }
}
