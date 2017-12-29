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

use Config;

/**
 * Class LanguageCensor
 *
 * A class that can redact/replace words.
 *
 */
class LanguageCensor
{
    static protected function matchWords($string, $word)
    {
        $result = [];
        $length = strlen($word);
        $pos = stripos($string, $word, 0);
        while ($pos !== false) {
            $match = substr($string, $pos, $length);
            array_push($result, $match);
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
    static public function censor($source)
    {
        $redactArray = Config::get('censor.redact', []);
        foreach ($redactArray as $word) {
            foreach (self::matchWords($source, $word) as $match) {
                $replacement = "<span class='censor'>{$match}</span>";
                $source = str_replace($match, $replacement, $source);
            }
        }

        $replaceDict = Config::get('censor.replace', []);
        foreach ($replaceDict as $word => $replacement) {
            $source = str_ireplace($word, $replacement, $source);
        }
        return $source;
    }
}
