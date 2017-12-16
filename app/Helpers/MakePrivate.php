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

namespace App\Helpers;

class MakePrivate
{

    public $announce = null;
    public $comment = null;
    public $created_by = null;

    function __construct()
    {
        // We can choose to load default announce URL, comment and created_by from a configuration file.
    }

    /**
     * Data Setter
     * @param array $data [array of public variables]
     */
    public function set($data = array())
    {
        if (is_array($data)) {
            if (isset($data['announce'])) {
                $this->announce = $data['announce'];
            }
            if (isset($data['comment'])) {
                $this->comment = $data['comment'];
            }
            if (isset($data['created_by'])) {
                $this->created_by = $data['created_by'];
            }
        }
    }

    /**
     * Decode a torrent file into Bencoded data
     * @param  string $s [link to torrent file]
     * @param  integer $pos [file position pointer]
     * @return array/null    [Array of Bencoded data]
     */
    public function bdecode($s, &$pos = 0)
    {
        if ($pos >= strlen($s)) {
            return null;
        }
        switch ($s[$pos]) {
            case 'd':
                $pos++;
                $retval = array();
                while ($s[$pos] != 'e') {
                    $key = $this->bdecode($s, $pos);
                    $val = $this->bdecode($s, $pos);
                    if ($key === null || $val === null)
                        break;
                    $retval[$key] = $val;
                }
                $retval["isDct"] = true;
                $pos++;
                return $retval;

            case 'l':
                $pos++;
                $retval = array();
                while ($s[$pos] != 'e') {
                    $val = $this->bdecode($s, $pos);
                    if ($val === null)
                        break;
                    $retval[] = $val;
                }
                $pos++;
                return $retval;

            case 'i':
                $pos++;
                $digits = strpos($s, 'e', $pos) - $pos;
                $val = round((float)substr($s, $pos, $digits));
                $pos += $digits + 1;
                return $val;

            // case "0": case "1": case "2": case "3": case "4":
            // case "5": case "6": case "7": case "8": case "9":
            default:
                $digits = strpos($s, ':', $pos) - $pos;
                if ($digits < 0 || $digits > 20)
                    return null;
                $len = (int)substr($s, $pos, $digits);
                $pos += $digits + 1;
                $str = substr($s, $pos, $len);
                $pos += $len;
                //echo "pos: $pos str: [$str] len: $len digits: $digits\n";
                return (string)$str;
        }
        return null;
    }

    /**
     * Created Torrent file from Bencoded data
     * @param  array $d [array data of a decoded torrent file]
     * @return string    [data can be downloaded as torrent]
     */
    public function bencode(&$d)
    {
        if (is_array($d)) {
            $ret = "l";
            $isDict = false;
            if (isset($d["isDct"]) && $d["isDct"] === true) {
                $isDict = 1;
                $ret = "d";
                // this is required by the specs, and BitTornado actualy chokes on unsorted dictionaries
                ksort($d, SORT_STRING);
            }
            foreach ($d as $key => $value) {
                if ($isDict) {
                    // skip the isDct element, only if it's set by us
                    if ($key == "isDct" and is_bool($value)) continue;
                    $ret .= strlen($key) . ":" . $key;
                }
                if (is_int($value) || is_float($value)) {
                    $ret .= "i${value}e";
                } else if (is_string($value)) {
                    $ret .= strlen($value) . ":" . $value;
                } else {
                    $ret .= $this->bencode($value);
                }
            }
            return $ret . "e";
        } elseif (is_string($d)) // fallback if we're given a single bencoded string or int
            return strlen($d) . ":" . $d;
        elseif (is_int($d) || is_float($d))
            return "i${d}e";
        else
            return null;
    }

    /**
     * Decode a torrent file into Bencoded data
     * @param  string $filename [File Path]
     * @return array/null            [Array of Bencoded data]
     */
    public function bdecode_file($filename)
    {
        if (is_file($filename)) {
            $f = file_get_contents($filename, FILE_BINARY);
            return $this->bdecode($f);
        }
        return null;
    }

    /**
     * Generate list of files in a torrent
     * @param  array $data [array data of a decoded torrent file]
     * @return array        [list of files in an array]
     */
    public function filelist($data)
    {
        $FileCount = 0;
        $FileList = array();
        if (!isset($data['info']['files'])) // Single file mode
        {
            $FileCount = 1;
            $TotalSize = $data['info']['length'];
            $FileList[] = array($data['info']['length'], $data['info']['name']);
        } else { // Multiple file mode
            $FileNames = array();
            $TotalSize = 0;
            $Files = $data['info']['files'];
            foreach ($Files as $File) {
                $FileCount++;
                $TotalSize += $File['length'];
                $FileSize = $File['length'];

                $FileName = ltrim(implode('/', $File['path']), '/');

                $FileList[] = array('size' => $FileSize, 'name' => $FileName);
                $FileNames[] = $FileName;
            }
            array_multisort($FileNames, $FileList);
        }
        return array('file_count' => $FileCount, 'total_size' => $TotalSize, 'files' => $FileList);
    }

    /**
     * Replace array data on Decoded torrent data so that it can be bencoded into a private torrent file.
     * Provide the custom data using $this->set();
     * @param  array $data [array data of a decoded torrent file]
     * @return array        [array data for torrent file]
     */
    public function make_private($data)
    {
        // Remove announce
        // announce-list is an unofficial extension to the protocol that allows for multiple trackers per torrent
        unset($data['announce']);
        unset($data['announce-list']);

        // Bitcomet & Azureus cache peers in here
        unset($data['nodes']);

        // Azureus stores the dht_backup_enable flag here
        unset($data['azureus_properties']);

        // Remove web-seeds
        unset($data['url-list']);

        // Remove libtorrent resume info
        unset($data['libtorrent_resume']);

        // Remove profiles / Media Infos
        unset($data['info']['profiles']);
        unset($data['info']['file-duration']);
        unset($data['info']['file-media']);

        // Add Announce URL
        if (is_array($this->announce)) {
            $data['announce'] = reset($this->announce);
            $data["announce-list"] = array();
            $announce_list = array();
            foreach ($this->announce as $announceUri) {
                $announce_list[] = $announceUri;
            }
            $data["announce-list"] = $announce_list;
        } else {
            $data['announce'] = $this->announce;
        }

        // Add Comment
        $data['comment'] = $this->comment;

        // Created by and Created on
        $data['created by'] = $this->created_by;
        $data['creation date'] = time();

        // Make Private
        $data['info']['private'] = 1;

        // Sort by key to respect spec
        ksort($data['info']);
        ksort($data);

        return $data;
    }
}
