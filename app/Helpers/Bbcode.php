<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Helpers;

class Bbcode
{
    private array $parsers = [
        'h1' => [
            'openBbcode'  => '/^\[h1\]/i',
            'closeBbcode' => '[/h1]',
            'openHtml'    => '<h1>',
            'closeHtml'   => '</h1>',
        ],
        'h2' => [
            'openBbcode'  => '/^\[h2\]/i',
            'closeBbcode' => '[/h2]',
            'openHtml'    => '<h2>',
            'closeHtml'   => '</h2>',
        ],
        'h3' => [
            'openBbcode'  => '/^\[h3\]/i',
            'closeBbcode' => '[/h3]',
            'openHtml'    => '<h3>',
            'closeHtml'   => '</h3>',
        ],
        'h4' => [
            'openBbcode'  => '/^\[h4\]/i',
            'closeBbcode' => '[/h4]',
            'openHtml'    => '<h4>',
            'closeHtml'   => '</h4>',
        ],
        'h5' => [
            'openBbcode'  => '/^\[h5\]/i',
            'closeBbcode' => '[/h5]',
            'openHtml'    => '<h5>',
            'closeHtml'   => '</h5>',
        ],
        'h6' => [
            'openBbcode'  => '/^\[h6\]/i',
            'closeBbcode' => '[/h6]',
            'openHtml'    => '<h6>',
            'closeHtml'   => '</h6>',
        ],
        'bold' => [
            'openBbcode'  => '/^\[b\]/i',
            'closeBbcode' => '[/b]',
            'openHtml'    => '<b>',
            'closeHtml'   => '</b>',
        ],
        'italic' => [
            'openBbcode'  => '/^\[i\]/i',
            'closeBbcode' => '[/i]',
            'openHtml'    => '<i>',
            'closeHtml'   => '</i>',
        ],
        'underline' => [
            'openBbcode'  => '/^\[u\]/i',
            'closeBbcode' => '[/u]',
            'openHtml'    => '<u>',
            'closeHtml'   => '</u>',
        ],
        'linethrough' => [
            'openBbcode'  => '/^\[s\]/i',
            'closeBbcode' => '[/s]',
            'openHtml'    => '<s>',
            'closeHtml'   => '</s>',
        ],
        'size' => [
            'openBbcode'  => '/^\[size=(\d+)\]/i',
            'closeBbcode' => '[/size]',
            'openHtml'    => '<span style="font-size: clamp(10px, $1, 100px);">',
            'closeHtml'   => '</span>',
        ],
        'font' => [
            'openBbcode'  => '/^\[font=([a-z0-9 ]+)\]/i',
            'closeBbcode' => '[/font]',
            'openHtml'    => '<span style="font-family: $1;">',
            'closeHtml'   => '</span>',
        ],
        'color' => [
            'openBbcode'  => '/^\[color=(\#[a-f0-9]{3,4}|\#[a-f0-9]{6}|\#[a-f0-9]{8}|[a-z]+)\]/i',
            'closeBbcode' => '[/color]',
            'openHtml'    => '<span style="color: $1;">',
            'closeHtml'   => '</span>',
        ],
        'center' => [
            'openBbcode'  => '/^\[center\]/i',
            'closeBbcode' => '[/center]',
            'openHtml'    => '<div style="text-align: center;">',
            'closeHtml'   => '</div>',
        ],
        'left' => [
            'openBbcode'  => '/^\[left\]/i',
            'closeBbcode' => '[/left]',
            'openHtml'    => '<div style="text-align: left;">',
            'closeHtml'   => '</div>',
        ],
        'right' => [
            'openBbcode'  => '/^\[right\]/i',
            'closeBbcode' => '[/right]',
            'openHtml'    => '<div style="text-align: right;">',
            'closeHtml'   => '</div>',
        ],
        'quote' => [
            'openBbcode'  => '/^\[quote\]/i',
            'closeBbcode' => '[/quote]',
            'openHtml'    => '<ul class="media-list comments-list"><li class="media" style="border-left-width: 5px; border-left-style: solid; border-left-color: #01bc8c;"><div class="media-body"><div class="pt-5">',
            'closeHtml'   => '</div></div></li></ul>',
        ],
        'namedquote' => [
            'openBbcode'  => '/^\[quote=([^<>"]*?)\]/i',
            'closeBbcode' => '[/quote]',
            'openHtml'    => '<ul class="media-list comments-list"><li class="media" style="border-left-width: 5px; border-left-style: solid; border-left-color: #01bc8c;"><div class="media-body"><strong><span><i class="fas fa-quote-left"></i> Quoting $1 :</span></strong><div class="pt-5">',
            'closeHtml'   => '</div></div></li></ul>',
        ],
        'namedlink' => [
            'openBbcode'  => '/^\[url=(.*?)\]/i',
            'closeBbcode' => '[/url]',
            'openHtml'    => '<a href="$1">',
            'closeHtml'   => '</a>',
        ],
        'orderedlistnumerical' => [
            'openBbcode'  => '/^\[list=1\]/i',
            'closeBbcode' => '[/list]',
            'openHtml'    => '<ol>',
            'closeHtml'   => '</ol>',
        ],
        'orderedlistalpha' => [
            'openBbcode'  => '/^\[list=a\]/i',
            'closeBbcode' => '[/list]',
            'openHtml'    => '<ol type="a">',
            'closeHtml'   => '</ol>',
        ],
        'unorderedlist' => [
            'openBbcode'  => '/^\[list\]/i',
            'closeBbcode' => '[/list]',
            'openHtml'    => '<ul>',
            'closeHtml'   => '</ul>',
        ],
        'code' => [
            'openBbcode'  => '/^\[code\]/i',
            'closeBbcode' => '[/code]',
            'openHtml'    => '<pre>',
            'closeHtml'   => '</pre>',
        ],
        'alert' => [
            'openBbcode'  => '/^\[alert\]/i',
            'closeBbcode' => '[/alert]',
            'openHtml'    => '<div class="bbcode-alert">',
            'closeHtml'   => '</div>',
        ],
        'note' => [
            'openBbcode'  => '/^\[note\]/i',
            'closeBbcode' => '[/note]',
            'openHtml'    => '<div class="bbcode-note">',
            'closeHtml'   => '</div>',
        ],
        'sub' => [
            'openBbcode'  => '/^\[sub\]/i',
            'closeBbcode' => '[/sub]',
            'openHtml'    => '<sub>',
            'closeHtml'   => '</sub>',
        ],
        'sup' => [
            'openBbcode'  => '/^\[sup\]/i',
            'closeBbcode' => '[/sup]',
            'openHtml'    => '<sup>',
            'closeHtml'   => '</sup>',
        ],
        'small' => [
            'openBbcode'  => '/^\[small\]/i',
            'closeBbcode' => '[/small]',
            'openHtml'    => '<small>',
            'closeHtml'   => '</small>',
        ],
        'table' => [
            'openBbcode'  => '/^\[table\]/i',
            'closeBbcode' => '[/table]',
            'openHtml'    => '<table>',
            'closeHtml'   => '</table>',
        ],
        'table-row' => [
            'openBbcode'  => '/^\[tr\]/i',
            'closeBbcode' => '[/tr]',
            'openHtml'    => '<tr>',
            'closeHtml'   => '</tr>',
        ],
        'table-data' => [
            'openBbcode'  => '/^\[td\]/i',
            'closeBbcode' => '[/td]',
            'openHtml'    => '<td>',
            'closeHtml'   => '</td>',
        ],
        'spoiler' => [
            'openBbcode'  => '/^\[spoiler\]/i',
            'closeBbcode' => '[/spoiler]',
            'openHtml'    => '<details class="label label-primary"><summary>Spoiler</summary><pre><code><div style="text-align:left;">',
            'closeHtml'   => '</div></code></pre></details>',
        ],
        'named-spoiler' => [
            'openBbcode'  => '/^\[spoiler=(.*?)\]/i',
            'closeBbcode' => '[/spoiler]',
            'openHtml'    => '<details class="label label-primary"><summary>$1</summary><pre><code><div style="text-align:left;">',
            'closeHtml'   => '</div></code></pre></details>',
        ],
    ];

    /**
     * Parses the BBCode string.
     */
    public function parse($source): string
    {
        // Replace all void elements since they don't have closing tags
        $source = \str_replace('[*]', '<li>', $source);
        $source = \preg_replace_callback(
            '/\[url\](.*?)\[\/url\]/i',
            fn ($matches) => '<a href="'.\htmlspecialchars($matches[1]).'">'.\htmlspecialchars($matches[1]).'</a>',
            $source
        );
        $source = \preg_replace_callback(
            '/\[img\](.*?)\[\/img\]/i',
            fn ($matches) => '<img src="'.\htmlspecialchars($matches[1]).'" loading="lazy" class="img-responsive" style="display: inline !important;">',
            $source
        );
        $source = \preg_replace_callback(
            '/\[img width=(\d+)\](.*?)\[\/img\]/i',
            fn ($matches) => '<img src="'.\htmlspecialchars($matches[2]).'" loading="lazy" width="'.$matches[1].'px">',
            $source
        );
        $source = \preg_replace_callback(
            '/\[img=(\d+)(?:x\d+)?\](.*?)\[\/img\]/i',
            fn ($matches) => '<img src="'.\htmlspecialchars($matches[2]).'" loading="lazy" width="'.$matches[1].'px">',
            $source
        );

        // Youtube elements need to be replaced like this because the content inside the two tags
        // has to be moved into an html attribute
        $source = \preg_replace_callback(
            '/\[youtube\](.*?)\[\/youtube\]/i',
            fn ($matches) => '<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.\htmlspecialchars($matches[1]).'?rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
            $source
        );
        $source = \preg_replace_callback(
            '/\[video\](.*?)\[\/video\]/i',
            fn ($matches) => '<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.\htmlspecialchars($matches[1]).'?rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
            $source
        );
        $source = \preg_replace_callback(
            '/\[video="youtube"\](.*?)\[\/video\]/i',
            fn ($matches) => '<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.\htmlspecialchars($matches[1]).'?rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
            $source
        );

        // Common comparison syntax used in other torrent management systems is quite specific
        // so it must be done here instead
        $source = \preg_replace_callback(
            '/\[comparison=(.*?)\]\s*(.*?)\s*\[\/comparison\]/is',
            function ($matches) {
                $comparates = preg_split('/\s*,\s*/', $matches[1]);
                $urls = \preg_split('/\s*(?:,|\s)\s*/', $matches[2]);
                $validatedUrls = \collect($urls)->filter(fn ($url) => filter_var($url, FILTER_VALIDATE_URL));
                $chunkedUrls = $validatedUrls->chunk(\count($comparates));
                $html = \view('partials.comparison', ['comparates' => $comparates, 'urls' => $chunkedUrls])->render();
                $html = \preg_replace('/\s+/', ' ', $html);

                return $html;
            },
            $source
        );

        // Stack of unclosed elements
        $openedElements = [];

        // Character index
        $index = 0;

        // Don't loop more than the length of the source
        while ($index < \strlen($source)) {
            // Get the next occurrence of `[`
            $index = \strpos($source, '[', $index);

            // Break if there are no more occurrences of `[`
            if ($index === false) {
                break;
            }

            // Break if `[` is the last character of the source
            if ($index + 1 >= \strlen($source)) {
                break;
            }

            // Is the potential tag opening or closing?
            if ($source[$index + 1] === '/' && ! empty($openedElements)) {
                $name = \array_pop($openedElements);
                $el = $this->parsers[$name];
                $tag = \substr($source, $index, \strlen($el['closeBbcode']));

                // Replace bbcode tag with html tag if found tag matches expected tag,
                // otherwise return the expected element's to the stack
                if (\strcasecmp($tag, $el['closeBbcode']) === 0) {
                    $source = \substr_replace($source, $el['closeHtml'], $index, \strlen($el['closeBbcode']));
                } else {
                    $openedElements[] = $name;
                }
            } else {
                $remainingText = \substr($source, $index);

                // Find match between found bbcode tag and valid elements
                foreach ($this->parsers as $name => $el) {
                    // The opening bbcode tag uses the regex `^` character to make
                    // sure only the beginning of $remainingText is matched
                    if (\preg_match($el['openBbcode'], $remainingText, $matches) === 1) {
                        $replacement = \preg_replace($el['openBbcode'], $el['openHtml'], $matches[0]);
                        $source = \substr_replace($source, $replacement, $index, \strlen($matches[0]));
                        $openedElements[] = $name;

                        break;
                    }
                }
            }

            $index++;
        }

        while (! empty($openedElements)) {
            $source .= $this->parsers[\array_pop($openedElements)]['closeHtml'];
        }

        // Replace line breaks
        $source = \str_replace(["\r\n", "\n"], '<br>', $source);

        return $source;
    }
}
