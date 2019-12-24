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

namespace App\Http\Middleware;

use Bepsvpt\SecureHeaders\SecureHeaders;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;

final class HtmlEncrypt
{
    /**
     * @var string
     */
    private string $hex;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    /**
     * HtmlEncrypt constructor.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     */
    public function __construct(Repository $configRepository)
    {
        $this->hex = '';
        $this->configRepository = $configRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var Response
         */
        $response = $next($request);

        if ($request->isMethod('get') && ! $request->ajax()) {
            $contentType = $response->headers->get('Content-Type');

            if (strpos($contentType, 'text/html') !== false) {
                $response->setContent($this->encryptHtml($response->getContent()));
            }
        }

        return $response;
    }

    public function encryptHtml($content): string
    {
        $nonce = SecureHeaders::nonce();

        $text = str_split(bin2hex($content), 2);

        array_walk($text, function (&$a) {
            $this->addHexValue('%'.$a);
        });

        $script = sprintf('<script type=\'text/javascript\' nonce=\'%s\'>document.writeln(unescape(\'%s\'));</script>', $nonce, $this->hex);

        if ($this->configRepository->get('html-encrypt.disable_right_click')) {
            $script .= sprintf('<script type=\'text/javascript\' nonce=\'%s\'>let body = document.getElementsByTagName(\'body\')[0];var att = document.createAttribute(\'oncontextmenu\');att.value = \'return false\'\';body.setAttributeNode(att);</script>', $nonce);
        }

        if ($this->configRepository->get('html-encrypt.disable_ctrl_and_F12_key')) {
            $script .= sprintf('<script type=\'text/javascript\' nonce=\'%s\'>document.onkeydown=function(e){if(e.ctrlKey || e.keyCode == 123){return false}}</script>', $nonce);
        }

        return $script;
    }

    public function addHexValue($hex): void
    {
        $this->hex .= $hex;
    }
}
