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

        $content = $response->getContent();
        $content = $this->censorResponse($content);

        $response->setContent($content);

        return $response;
    }

    protected function matchWords($string, $word)
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
     * Censor the request response.
     *
     * @param $source
     *
     * @return mixed
     */
    protected function censorResponse($source)
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
