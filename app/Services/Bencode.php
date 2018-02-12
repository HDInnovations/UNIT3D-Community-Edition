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

use function theodorejb\polycast\safe_int;
use function theodorejb\polycast\safe_float;

/**
 * Bencode library for torrents
 *
 */
class Bencode
{
    public static function parse_integer($s, &$pos)
    {
        $len = strlen($s);
        if ($len == 0 || $s[$pos] != 'i') {
            return null;
        }
        ++$pos;

        $result = "";
        while ($pos < $len && $s[$pos] != 'e') {
            if (is_numeric($s[$pos])) {
                $result .= $s[$pos];
            } else {
                // We have an invalid character in the string.
                return null;
            }
            ++$pos;
        }

        if ($pos >= $len) {
            // No end marker, hence we return null.
            return null;
        }

        ++$pos;

        if (safe_int($result)) {
            return (int)$result;
        } else {
            return null;
        }
    }

    public static function parse_string($s, &$pos)
    {
        $len = strlen($s);
        $length_str = "";

        while ($pos < $len && $s[$pos] != ':') {
            if (is_numeric($s[$pos])) {
                $length_str .= $s[$pos];
            } else {
                // Non-numeric character, we return null in this case.
                return null;
            }
            ++$pos;
        }

        if ($pos >= $len) {
            // We need a colon here, but there's none.
            return null;
        }

        ++$pos;
        if (!safe_int($length_str)) {
            return null;
        }

        $length = (int)$length_str;
        $result = "";
        while ($pos < $len && $length > 0) {
            $result .= $s[$pos];
            --$length;
            ++$pos;
        }

        if ($length > 0) {
            // Input ended, but the string is longer than that.
            return null;
        }

        return $result;
    }

    public static function bdecode($s, &$pos = 0)
    {
        $len = strlen($s);
        if ($pos >= $len) {
            return null;
        }

        $c = $s[$pos];
        if ($c == 'i') {
            return self::parse_integer($s, $pos);
        } elseif (is_numeric($c)) {
            return self::parse_string($s, $pos);
        } elseif ($c == 'd') {
            $dict = [];
            ++$pos;
            while ($pos < $len && $s[$pos] != 'e') {
                $key = self::bdecode($s, $pos);
                $value = self::bdecode($s, $pos);
                if (is_null($key) || is_null($value)) {
                    return null;
                }
                $dict[$key] = $value;
            }

            if ($pos >= $len) {
                // We need a end marker here
                return null;
            }
            ++$pos;

            return $dict;
        } elseif ($c == 'l') {
            $list = [];
            ++$pos;
            while ($pos < $len && $s[$pos] != 'e') {
                $next = self::bdecode($s, $pos);
                if (!is_null($next)) {
                    array_push($list, $next);
                } else {
                    return null;
                }
            }

            if ($pos >= $len) {
                // We need a end marker here
                return null;
            }
            ++$pos;
            return $list;
        } else {
            return null;
        }
    }

    public static function bencode($d)
    {
        if (is_array($d)) {
            $ret = 'l';
            $is_dict = false;
            if (!isset($d['isDct'])) {
                foreach (array_keys($d) as $key) {
                    if (!is_int($key)) {
                        $is_dict = true;
                        break;
                    }
                }
            } else {
                $is_dict = (bool)$d['isDct'];
                unset($d['isDct']);
            }

            if ($is_dict) {
                $ret = 'd';
                // this is required by the specs, and BitTornado actualy chokes on unsorted dictionaries
                ksort($d, SORT_STRING);
            }

            foreach ($d as $key => $value) {
                if ($is_dict) {
                    $ret .= strlen($key) . ':' . $key;
                }

                if (is_int($value) || is_float($value)) {
                    $ret .= sprintf('i%de', $value);
                } elseif (is_string($value)) {
                    $ret .= strlen($value) . ':' . $value;
                } else {
                    $ret .= self::bencode($value);
                }
            }
            return $ret . 'e';
        } elseif (is_string($d)) {
            return strlen($d) . ':' . $d;
        } elseif (is_int($d) || is_float($d)) {
            return sprintf('i%de', $d);
        } else {
            return null;
        }
    }

    public static function bdecode_file($filename)
    {
        $f = file_get_contents($filename, FILE_BINARY);
        return self::bdecode($f);
    }

    public static function bdecode_getinfo($filename, $need_info_hash = false)
    {
        $t = self::bdecode_file($filename);
        $t['info_hash'] = $need_info_hash ? sha1(self::bencode($t['info'])) : null;

        if (isset($t['info']['files']) && is_array($t['info']['files'])) { //multifile
            $t['info']['size'] = 0;
            $t['info']['filecount'] = 0;

            foreach ($t['info']['files'] as $file) {
                $t['info']['filecount']++;
                $t['info']['size'] += $file['length'];
            }
        } else {
            $t['info']['size'] = $t['info']['length'];
            $t['info']['filecount'] = 1;
            $t['info']['files'][0]['path'] = $t['info']['name'];
            $t['info']['files'][0]['length'] = $t['info']['length'];
        }
        return $t;
    }
}
