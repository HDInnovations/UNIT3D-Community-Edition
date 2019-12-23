<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Helpers;

class Xss
{
    /**
     * @const array
     */
    const TAGS_TO_FILTER = [
        '<script.*script>',
        '<frame.*frame>',
        '<object.*object>',
        '<embed.*embed>',
    ];

    /**
     * @const string
     */
    const LISTENERS_TO_FILTER = 'on.*=\".*\"(?=.*>)';

    /**
     * @return string
     */
    public static function getTagsPattern()
    {
        $allTags = implode('|', self::TAGS_TO_FILTER);

        return sprintf('/(%s)/isU', $allTags);
    }

    /**
     * @return string
     */
    public static function getListenersPattern()
    {
        return sprintf('/%s/isU', self::LISTENERS_TO_FILTER);
    }

    /**
     * @param $data
     * @return string
     * @throws \XSSEvasion\Exceptions\FilterException
     */
    public function filter($data)
    {
        $data = $this->filterTags($data);
        $data = $this->filterListeners($data);

        return $data;
    }

    /**
     * @param string $valueToFilter
     * @return mixed|string
     */
    protected function filterTags(string $valueToFilter): string
    {
        preg_match_all(
            self::getTagsPattern(),
            $valueToFilter,
            $pregResult
        );
        $matches = array_key_first($pregResult);
        if (! $matches) {
            $matches = [];
        }
        foreach ($matches as $tag) {
            $valueToFilter = str_replace($tag, e($tag), $valueToFilter);
        }

        return $valueToFilter;
    }

    /**
     * @param string $valueToFilter
     * @return string
     */
    protected function filterListeners(string $valueToFilter): string
    {
        return preg_replace(
            self::getListenersPattern(),
            '',
            $valueToFilter
        );
    }
}
