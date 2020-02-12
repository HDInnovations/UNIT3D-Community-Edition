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

namespace App\Http\Middleware;

use Bepsvpt\SecureHeaders\SecureHeaders;
use Closure;

class HtmlEncrypt
{
    private $hex;

    /**
     * HtmlEncrypt constructor.
     */
    public function __construct()
    {
        $this->hex = '';
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var Response
         */
        $response = $next($request);

        if ($request->isMethod('get') && !$request->ajax()) {
            $contentType = $response->headers->get('Content-Type');

            if (strpos($contentType, 'text/html') !== false) {
                $response->setContent($this->encryptHtml($response->getContent()));
            }
        }

        return $response;
    }

    public function encryptHtml($content)
    {
        $nonce = SecureHeaders::nonce();

        $text = str_split(bin2hex($content), 2);

        array_walk($text, function (&$a) {
            $this->addHexValue('%'.$a);
        });

        $script = "<script type='text/javascript' nonce='{$nonce}'>document.writeln(unescape('{$this->hex}'));</script>";

        if (config('html-encrypt.disable_right_click')) {
            $script .= "<script type='text/javascript' nonce='{$nonce}'>let body = document.getElementsByTagName('body')[0];var att = document.createAttribute('oncontextmenu');att.value = 'return false'';body.setAttributeNode(att);</script>";
        }

        if (config('html-encrypt.disable_ctrl_and_F12_key')) {
            $script .= "<script type='text/javascript' nonce='{$nonce}'>document.onkeydown=function(e){if(e.ctrlKey || e.keyCode == 123){return false}}</script>";
        }

        return $script;
    }

    public function addHexValue($hex)
    {
        $this->hex .= $hex;
    }
}
