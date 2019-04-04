<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 *
 * @link        http://milesj.me/code/php/decoda
 */

namespace App\Hook;

use Decoda\Hook\AbstractHook;

/**
 * Converts URLs and emails (not wrapped in tags) into clickable links.
 */
class ClickableHook extends AbstractHook
{
    /**
     * Matches a link or an email, and converts it to an anchor tag.
     *
     * @param string $content
     *
     * @return string
     */
    public function beforeParse($content)
    {
        $parser = $this->getParser();

        // To make sure we won't parse links inside [url] or [img] tags, we'll first replace all urls/imgs with uniqids
        // and keep them in this array, and restore them at the end, after parsing
        $ignoredStrings = [];

        // The tags we won't touch
        // For example, neither [url="http://www.example.com"] nor [img]http://www.example.com[/img] will be replaced.
        $ignoredTags = ['url', 'link', 'img', 'image'];

        $i = 0;
        foreach ($ignoredTags as $tag) {
            if (preg_match_all(sprintf('/\[%s[\s=\]].*?\[\/%s\]/is', $tag, $tag), $content, $matches, PREG_SET_ORDER)) {
                $matches = array_unique(array_map(function ($x) {
                    return $x[0];
                }, $matches));

                foreach ($matches as $val) {
                    $uniqid = uniqid($i++);

                    $ignoredStrings[$uniqid] = $val;
                    $content = str_replace($val, $uniqid, $content);
                }
            }
        }

        if ($parser->hasFilter('Url')) {
            $protocols = $parser->getFilter('Url')->getConfig('protocols');
            $chars = preg_quote('-_=+|\;:&?/[]%,.!@#$*(){}"\'', '/');
            $split_char = "<>[] \n";
            $split_chars = [];
            $result = [];

            $length = strlen($content);
            $split = [];
            $current = '';
            for ($i = 0; $i < $length; $i++) {
                if (strpos($split_char, $content[$i]) !== false) {
                    array_push($split_chars, $content[$i]);
                    array_push($split, $current);
                    $current = '';
                } else {
                    $current .= $content[$i];
                }
            }

            if (strlen($current) != 0) {
                array_push($split, $current);
            }

            $length = count($split);
            for ($i = 0; $i < $length; $i++) {
                if (filter_var($split[$i], FILTER_VALIDATE_URL)) {
                    $split[$i] = self::_urlCallback($split[$i]);
                } elseif (preg_match("/www\.[A-z,-]+\.[A-z,-]+/", $split[$i])) {
                    $split[$i] = self::_urlCallback($split[$i]);
                }
            }

            $result = '';
            $split_length = count($split_chars);
            for ($i = 0; $i < $length; $i++) {
                $result .= $split[$i];
                if ($i < $split_length) {
                    $result .= $split_chars[$i];
                }
            }

            $content = $result;
        }

        // Based on W3C HTML5 spec: https://www.w3.org/TR/html5/forms.html#valid-e-mail-address
        if ($parser->hasFilter('Email')) {
            $pattern = '(:\/\/[\w\.\+]+:)?([a-z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*)';

            $content = preg_replace_callback('/'.$pattern.'/i', [$this, '_emailCallback'], $content);
        }

        // We restore the tags we ommited
        foreach ($ignoredStrings as $key => $val) {
            $content = str_replace($key, $val, $content);
        }

        return $content;
    }

    /**
     * Callback for email processing.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function _emailCallback($matches)
    {
        // is like http://user:pass@domain.com ? Then we do not touch it.
        if ($matches[1]) {
            return $matches[0];
        }

        return $this->getParser()->getFilter('Email')->parse([
            'tag'        => 'email',
            'attributes' => [],
        ], trim($matches[2]));
    }

    /**
     * Callback for URL processing.
     *
     * @param $match
     * @return string
     */
    protected function _urlCallback($match)
    {
        return $this->getParser()->getFilter('Url')->parse([
            'tag'        => 'url',
            'attributes' => [],
        ], trim($match));
    }
}
