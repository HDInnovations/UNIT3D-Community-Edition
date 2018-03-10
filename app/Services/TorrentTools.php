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
        self::$decodedTorrent = Bencode::bdecode_file($torrentFile);
        // The PID will be set if an user downloads the torrent, but for
        // security purposes it's better to overwrite the user-provided
        // announce URL.
        $announce = config('app.url');
        $announce .= "/announce/PID";
        self::$decodedTorrent['announce'] = $announce;
        self::$decodedTorrent['info']['source'] = config('torrent.source');
        self::$decodedTorrent['info']['private'] = 1;
        $created_by = config('torrent.created_by', null);
        $created_by_append = config('torrent.created_by_append', false);
        if ($created_by !== null) {
            if ($created_by_append && array_key_exists("created by", self::$decodedTorrent)) {
                $c = self::$decodedTorrent['created by'];
                $c = trim($c, ". ");
                $c .= ". " . $created_by;
                $created_by = $c;
            }
            self::$decodedTorrent['created by'] = $created_by;
        }
        $comment = config('torrent.comment', null);
        if ($comment !== null) {
            self::$decodedTorrent['comment'] = $comment;
        }
        $encoded = Bencode::bencode(self::$decodedTorrent);
        self::$fileName = uniqid() . '.torrent'; // Generate a unique name
        file_put_contents(getcwd() . '/files/torrents/' . self::$fileName, $encoded); // Create torrent
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
