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

class Linkify
{
    /**
     * @var string
     */
    private const REG_EX_URL = "/^(?!\[url=)(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)/";

    public function linky($text)
    {
        if (\preg_match(self::REG_EX_URL, $text, $url)) {
            return \preg_replace(self::REG_EX_URL, \sprintf("<a href='%s'>%s</a> ", $url[0], $url[0]), $text);
        }

        return $text;
    }
}
