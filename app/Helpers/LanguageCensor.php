<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Helpers;

/**
 * Class LanguageCensor.
 *
 * A class that can redact/replace words.
 */
class LanguageCensor
{
    protected static function isSpecial($c)
    {
        $specialChars = "<>\n [].;,";

        return strpos($specialChars, $c) !== false;
    }

    protected static function matchWordIndexes($string, $word)
    {
        $result = [];
        $length = strlen($word);
        $string_length = strlen($string);
        $pos = stripos($string, $word, 0);
        while ($pos !== false) {
            $prev = ($pos === 0) ? ' ' : $string[$pos - 1];
            $last = ($pos + $length) < $string_length ? $string[$pos + $length] : ' ';
            if (self::isSpecial($prev) && self::isSpecial($last)) {
                $result[] = $pos;
            }
            $pos = stripos($string, $word, $pos + $length);
        }

        return $result;
    }

    /**
     * Censor a text.
     *
     * @param $source
     *
     * @return mixed
     */
    public static function censor($source)
    {
        $redactArray = config('censor.redact', []);
        foreach ($redactArray as $word) {
            $result = '';
            $length = strlen($source);
            $word_length = strlen($word);
            assert($word_length > 0);
            $indexes = self::matchWordIndexes($source, $word);
            $ignore = 0;
            for ($i = 0; $i < $length; $i++) {
                if ((is_countable($indexes) ? count($indexes) : 0) > 0 && $indexes[0] == $i) {
                    $match = substr($source, $indexes[0], $word_length);
                    $result .= "<span class='censor'>{$match}</span>";
                    $ignore = $word_length - 1;
                } elseif ($ignore > 0) {
                    $ignore--;
                } else {
                    $result .= $source[$i];
                }
            }
            $source = $result;
        }

        $replaceDict = config('censor.replace', []);
        foreach ($replaceDict as $word => $replacement) {
            $source = str_ireplace($word, $replacement, $source);
        }

        return $source;
    }
}
