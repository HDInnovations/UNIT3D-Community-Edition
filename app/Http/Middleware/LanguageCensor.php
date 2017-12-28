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

namespace App\Http\Middleware;

use Closure;
use Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class LanguageCensor
 *
 * A middleware that can replace or redact(blur) words on a page.
 *
 */
class LanguageCensor
{
    /** @var  An associative array of keys as words or patterns and values as the replacements */
    protected $replaceDict;

    /** @var  An array specifying the words and patterns that are to be redacted */
    protected $redactDict;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $ability
     * @param string|null              $boundModelName
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, $ability = null, $boundModelName = null)
    {
        $response = $next($request);

        $this->prepareDictionary();

        $content = $response->getContent();
        $content = $this->censorResponse($content);

        $response->setContent($content);

        return $response;
    }

    /**
     * Sets up the dictionaries, normalizes the content provided for censor
     * for replacing and redacting stuff on page
     */
    private function prepareDictionary()
    {
        $replaceDict = Config::get('censor.replace', []);
        $redactDict  = Config::get('censor.redact', []);

        $replaceDictKeys   = array_keys($replaceDict);
        $replaceDictValues = array_values($replaceDict);

        $replaceDictKeys = $this->normalizeRegex($replaceDictKeys);

        $this->replaceDict = array_combine($replaceDictKeys, $replaceDictValues);
        $this->redactDict  = $this->normalizeRegex($redactDict);
    }

    /**
     * Converts the words containing wildcards to regex patterns
     *
     * @param $dictionary
     *
     * @return array
     */
    private function normalizeRegex($dictionary)
    {
        foreach ($dictionary as &$pattern) {
            $pattern = str_replace('%', '(?:[^<\s]*)', $pattern);
        }

        return $dictionary;
    }

    /**
     * Censor the request response.
     *
     * @param $source
     *
     * @return mixed
     */
    protected function censorResponse($source)
    {
        $replaceables = array_keys($this->replaceDict);
        $replaceables = array_merge($replaceables, $this->redactDict);

        // Word boundary and word matching regex
        $replaceables = '\b' . implode('\b|\b', $replaceables) . '\b';
        $regex        = '/>(?:[^<]*?(' . $replaceables . ')[^<]*?)</i';

        // Make the keys lower case so that it is easy to lookup
        // the replacements
        $toReplace = array_change_key_case($this->replaceDict, CASE_LOWER);
        $toRedact  = $this->redactDict;

        // Find all the matches and keep redacting/replacing
        $source = preg_replace_callback($regex, function ($match) use ($toReplace, $toRedact) {

            $temp = strtolower($match[1]);

            // If we have to replace it
            if (isset($toReplace[$temp])) {
                return str_replace($match[1], $toReplace[$temp], $match[0]);
            } elseif ($regexKey = $this->getReplaceRegexKey($temp)) { // Get the key i.e. pattern of the replace dictionary
                return str_replace($match[1], $toReplace[$regexKey], $match[0]);
            } elseif ($this->_inArray($temp, $toRedact) || $this->getRedactRegexKey($temp)) {  // If it matches a word or pattern to redact
                $wrapWith = "<span class='censor'>{$temp}</span>";

                return str_replace($match[1], $wrapWith, $match[0]);
            } else {
                return $match[0];
            }

        }, $source);

        return $source;
    }

    /**
     * Gets a matched item, checks the replace dictionary for any satisfying pattern
     * and returns the matching pattern key item if any
     *
     * @param $matched
     *
     * @return bool|string
     */
    public function getReplaceRegexKey($matched)
    {
        foreach ($this->replaceDict as $pattern => $replaceWith) {
            if (preg_match('/' . $pattern . '/', $matched)) {
                return $pattern;
            }
        }

        return false;
    }

    /**
     * Case in-sensitive in_array
     *
     * @param $needle
     * @param $haystack
     *
     * @return bool
     */
    private function _inArray($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    /**
     * Gets the matched item and check if it matches any of the patterns
     * available in redact dictionary
     *
     * @param $matched
     *
     * @return bool
     */
    public function getRedactRegexKey($matched)
    {
        foreach ($this->redactDict as $pattern) {
            if (preg_match('/' . $pattern . '/', $matched)) {
                return $pattern;
            }
        }

        return false;
    }
}
