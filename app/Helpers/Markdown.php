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

class Markdown
{
    public function text($text)
    {
        // make sure no definitions are set
        $this->DefinitionData = [];

        // standardize line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // remove surrounding line breaks
        $text = trim($text, "\n");

        // split text into lines
        $lines = explode("\n", $text);

        // iterate through lines to identify blocks
        $markup = $this->lines($lines);

        // trim line breaks
        $markup = trim($markup, "\n");

        return $markup;
    }

    //
    // Setters
    //

    public function setBreaksEnabled($breaksEnabled)
    {
        $this->breaksEnabled = $breaksEnabled;

        return $this;
    }

    protected $breaksEnabled;

    public function setMarkupEscaped($markupEscaped)
    {
        $this->markupEscaped = $markupEscaped;

        return $this;
    }

    protected $markupEscaped;

    public function setUrlsLinked($urlsLinked)
    {
        $this->urlsLinked = $urlsLinked;

        return $this;
    }

    protected $urlsLinked = true;

    public function setSafeMode($safeMode)
    {
        $this->safeMode = (bool) $safeMode;

        return $this;
    }

    protected $safeMode;

    protected $safeLinksWhitelist = [
        'http://',
        'https://',
        'ftp://',
        'ftps://',
        'mailto:',
        'data:image/png;base64,',
        'data:image/gif;base64,',
        'data:image/jpeg;base64,',
        'irc:',
        'ircs:',
        'git:',
        'ssh:',
        'news:',
        'steam:',
    ];

    //
    // Lines
    //

    protected $BlockTypes = [
        '#' => ['Header'],
        '*' => ['Rule', 'List'],
        '+' => ['List'],
        '-' => ['SetextHeader', 'Table', 'Rule', 'List'],
        '0' => ['List'],
        '1' => ['List'],
        '2' => ['List'],
        '3' => ['List'],
        '4' => ['List'],
        '5' => ['List'],
        '6' => ['List'],
        '7' => ['List'],
        '8' => ['List'],
        '9' => ['List'],
        ':' => ['Table'],
        '<' => ['Comment', 'Markup'],
        '=' => ['SetextHeader'],
        '>' => ['Quote'],
        '[' => ['Reference'],
        '_' => ['Rule'],
        '`' => ['FencedCode'],
        '|' => ['Table'],
        '~' => ['FencedCode'],
    ];

    // ~

    protected $unmarkedBlockTypes = [
        'Code',
    ];

    //
    // Blocks
    //

    protected function lines(array $lines)
    {
        $CurrentBlock = null;

        foreach ($lines as $line) {
            if (rtrim($line) === '') {
                if (isset($CurrentBlock)) {
                    $CurrentBlock['interrupted'] = true;
                }

                continue;
            }

            if (strpos($line, "\t") !== false) {
                $parts = explode("\t", $line);

                $line = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;

                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;

            while (isset($line[$indent]) && $line[$indent] === ' ') {
                $indent++;
            }

            $text = $indent > 0 ? substr($line, $indent) : $line;

            // ~

            $Line = ['body' => $line, 'indent' => $indent, 'text' => $text];

            // ~

            if (isset($CurrentBlock['continuable'])) {
                $Block = $this->{'block'.$CurrentBlock['type'].'Continue'}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $CurrentBlock = $Block;

                    continue;
                }
                if ($this->isBlockCompletable($CurrentBlock['type'])) {
                    $CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);
                }
            }

            // ~

            $marker = $text[0];

            // ~

            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->BlockTypes[$marker])) {
                foreach ($this->BlockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }

            //
            // ~

            foreach ($blockTypes as $blockType) {
                $Block = $this->{'block'.$blockType}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $Block['type'] = $blockType;

                    if (!isset($Block['identified'])) {
                        $Blocks[] = $CurrentBlock;

                        $Block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($blockType)) {
                        $Block['continuable'] = true;
                    }

                    $CurrentBlock = $Block;

                    continue 2;
                }
            }

            // ~

            if (isset($CurrentBlock) && !isset($CurrentBlock['type']) && !isset($CurrentBlock['interrupted'])) {
                $CurrentBlock['element']['text'] .= "\n".$text;
            } else {
                $Blocks[] = $CurrentBlock;

                $CurrentBlock = $this->paragraph($Line);

                $CurrentBlock['identified'] = true;
            }
        }

        // ~

        if (isset($CurrentBlock['continuable']) && $this->isBlockCompletable($CurrentBlock['type'])) {
            $CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);
        }

        // ~

        $Blocks[] = $CurrentBlock;

        unset($Blocks[0]);

        // ~

        $markup = '';

        foreach ($Blocks as $Block) {
            if (isset($Block['hidden'])) {
                continue;
            }

            $markup .= "\n";
            $markup .= isset($Block['markup']) ? $Block['markup'] : $this->element($Block['element']);
        }

        // ~

        return $markup."\n";
    }

    protected function isBlockContinuable($Type)
    {
        return method_exists($this, 'block'.$Type.'Continue');
    }

    protected function isBlockCompletable($Type)
    {
        return method_exists($this, 'block'.$Type.'Complete');
    }

    //
    // Code

    protected function blockCode($Line, $Block = null)
    {
        if (isset($Block) && !isset($Block['type']) && !isset($Block['interrupted'])) {
            return;
        }

        if ($Line['indent'] >= 4) {
            $text = substr($Line['body'], 4);

            return [
                'element' => [
                    'name'    => 'pre',
                    'handler' => 'element',
                    'text'    => [
                        'name' => 'code',
                        'text' => $text,
                    ],
                ],
            ];
        }
    }

    protected function blockCodeContinue($Line, $Block)
    {
        if ($Line['indent'] >= 4) {
            if (isset($Block['interrupted'])) {
                $Block['element']['text']['text'] .= "\n";

                unset($Block['interrupted']);
            }

            $Block['element']['text']['text'] .= "\n";

            $text = substr($Line['body'], 4);

            $Block['element']['text']['text'] .= $text;

            return $Block;
        }
    }

    protected function blockCodeComplete($Block)
    {
        $text = $Block['element']['text']['text'];

        $Block['element']['text']['text'] = $text;

        return $Block;
    }

    //
    // Comment

    protected function blockComment($Line)
    {
        if ($this->markupEscaped || $this->safeMode) {
            return;
        }

        if (isset($Line['text'][3]) && $Line['text'][3] === '-' && $Line['text'][2] === '-' && $Line['text'][1] === '!') {
            $Block = [
                'markup' => $Line['body'],
            ];

            if (preg_match('/-->$/', $Line['text'])) {
                $Block['closed'] = true;
            }

            return $Block;
        }
    }

    protected function blockCommentContinue($Line, array $Block)
    {
        if (isset($Block['closed'])) {
            return;
        }

        $Block['markup'] .= "\n".$Line['body'];

        if (preg_match('/-->$/', $Line['text'])) {
            $Block['closed'] = true;
        }

        return $Block;
    }

    //
    // Fenced Code

    protected function blockFencedCode($Line)
    {
        if (preg_match('/^['.$Line['text'][0].']{3,}[ ]*([^`]+)?[ ]*$/', $Line['text'], $matches)) {
            $Element = [
                'name' => 'code',
                'text' => '',
            ];

            if (isset($matches[1])) {
                $class = 'language-'.$matches[1];

                $Element['attributes'] = [
                    'class' => $class,
                ];
            }

            return [
                'char'    => $Line['text'][0],
                'element' => [
                    'name'    => 'pre',
                    'handler' => 'element',
                    'text'    => $Element,
                ],
            ];
        }
    }

    protected function blockFencedCodeContinue($Line, $Block)
    {
        if (isset($Block['complete'])) {
            return;
        }

        if (isset($Block['interrupted'])) {
            $Block['element']['text']['text'] .= "\n";

            unset($Block['interrupted']);
        }

        if (preg_match('/^'.$Block['char'].'{3,}[ ]*$/', $Line['text'])) {
            $Block['element']['text']['text'] = substr($Block['element']['text']['text'], 1);

            $Block['complete'] = true;

            return $Block;
        }

        $Block['element']['text']['text'] .= "\n".$Line['body'];

        return $Block;
    }

    protected function blockFencedCodeComplete($Block)
    {
        $text = $Block['element']['text']['text'];

        $Block['element']['text']['text'] = $text;

        return $Block;
    }

    //
    // Header

    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1])) {
            $level = 1;

            while (isset($Line['text'][$level]) && $Line['text'][$level] === '#') {
                $level++;
            }

            if ($level > 6) {
                return;
            }

            $text = trim($Line['text'], '# ');

            return [
                'element' => [
                    'name'    => 'h'.min(6, $level),
                    'text'    => $text,
                    'handler' => 'line',
                ],
            ];
        }
    }

    //
    // List

    protected function blockList($Line)
    {
        [$name, $pattern] = $Line['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]+[.]'];

        if (preg_match('/^('.$pattern.'[ ]+)(.*)/', $Line['text'], $matches)) {
            $Block = [
                'indent'  => $Line['indent'],
                'pattern' => $pattern,
                'element' => [
                    'name'    => $name,
                    'handler' => 'elements',
                ],
            ];

            if ($name === 'ol') {
                $listStart = stristr($matches[0], '.', true);

                if ($listStart !== '1') {
                    $Block['element']['attributes'] = ['start' => $listStart];
                }
            }

            $Block['li'] = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    $matches[2],
                ],
            ];

            $Block['element']['text'][] = &$Block['li'];

            return $Block;
        }
    }

    protected function blockListContinue($Line, array $Block)
    {
        if ($Block['indent'] === $Line['indent'] && preg_match('/^'.$Block['pattern'].'(?:[ ]+(.*)|$)/', $Line['text'], $matches)) {
            if (isset($Block['interrupted'])) {
                $Block['li']['text'][] = '';

                $Block['loose'] = true;

                unset($Block['interrupted']);
            }

            unset($Block['li']);

            $text = isset($matches[1]) ? $matches[1] : '';

            $Block['li'] = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    $text,
                ],
            ];

            $Block['element']['text'][] = &$Block['li'];

            return $Block;
        }

        if ($Line['text'][0] === '[' && $this->blockReference($Line)) {
            return $Block;
        }

        if (!isset($Block['interrupted'])) {
            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

            $Block['li']['text'][] = $text;

            return $Block;
        }

        if ($Line['indent'] > 0) {
            $Block['li']['text'][] = '';

            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

            $Block['li']['text'][] = $text;

            unset($Block['interrupted']);

            return $Block;
        }
    }

    protected function blockListComplete(array $Block)
    {
        if (isset($Block['loose'])) {
            foreach ($Block['element']['text'] as &$li) {
                if (end($li['text']) !== '') {
                    $li['text'][] = '';
                }
            }
        }

        return $Block;
    }

    //
    // Quote

    protected function blockQuote($Line)
    {
        if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            return [
                'element' => [
                    'name'    => 'blockquote',
                    'handler' => 'lines',
                    'text'    => (array) $matches[1],
                ],
            ];
        }
    }

    protected function blockQuoteContinue($Line, array $Block)
    {
        if ($Line['text'][0] === '>' && preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            if (isset($Block['interrupted'])) {
                $Block['element']['text'][] = '';

                unset($Block['interrupted']);
            }

            $Block['element']['text'][] = $matches[1];

            return $Block;
        }

        if (!isset($Block['interrupted'])) {
            $Block['element']['text'][] = $Line['text'];

            return $Block;
        }
    }

    //
    // Rule

    protected function blockRule($Line)
    {
        if (preg_match('/^(['.$Line['text'][0].'])([ ]*\1){2,}[ ]*$/', $Line['text'])) {
            return [
                'element' => [
                    'name' => 'hr',
                ],
            ];
        }
    }

    //
    // Setext

    protected function blockSetextHeader($Line, array $Block = null)
    {
        if (!isset($Block) || isset($Block['type']) || isset($Block['interrupted'])) {
            return;
        }

        if (rtrim($Line['text'], $Line['text'][0]) === '') {
            $Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';

            return $Block;
        }
    }

    //
    // Markup

    protected function blockMarkup($Line)
    {
        if ($this->markupEscaped || $this->safeMode) {
            return;
        }

        if (preg_match('/^<(\w[\w-]*)(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*(\/)?>/', $Line['text'], $matches)) {
            $element = strtolower($matches[1]);

            if (in_array($element, $this->textLevelElements)) {
                return;
            }

            $Block = [
                'name'   => $matches[1],
                'depth'  => 0,
                'markup' => $Line['text'],
            ];

            $length = strlen($matches[0]);

            $remainder = substr($Line['text'], $length);

            if (trim($remainder) === '') {
                if (isset($matches[2]) || in_array($matches[1], $this->voidElements)) {
                    $Block['closed'] = true;

                    $Block['void'] = true;
                }
            } else {
                if (isset($matches[2]) || in_array($matches[1], $this->voidElements)) {
                    return;
                }

                if (preg_match('/<\/'.$matches[1].'>[ ]*$/i', $remainder)) {
                    $Block['closed'] = true;
                }
            }

            return $Block;
        }
    }

    protected function blockMarkupContinue($Line, array $Block)
    {
        if (isset($Block['closed'])) {
            return;
        }

        if (preg_match('/^<'.$Block['name'].'(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*>/i', $Line['text'])) { // open
            $Block['depth']++;
        }

        if (preg_match('/(.*?)<\/'.$Block['name'].'>[ ]*$/i', $Line['text'], $matches)) { // close
            if ($Block['depth'] > 0) {
                $Block['depth']--;
            } else {
                $Block['closed'] = true;
            }
        }

        if (isset($Block['interrupted'])) {
            $Block['markup'] .= "\n";

            unset($Block['interrupted']);
        }

        $Block['markup'] .= "\n".$Line['body'];

        return $Block;
    }

    //
    // Reference

    protected function blockReference($Line)
    {
        if (preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $Line['text'], $matches)) {
            $id = strtolower($matches[1]);

            $Data = [
                'url'   => $matches[2],
                'title' => null,
            ];

            if (isset($matches[3])) {
                $Data['title'] = $matches[3];
            }

            $this->DefinitionData['Reference'][$id] = $Data;

            return [
                'hidden' => true,
            ];
        }
    }

    //
    // Table

    protected function blockTable($Line, array $Block = null)
    {
        if (!isset($Block) || isset($Block['type']) || isset($Block['interrupted'])) {
            return;
        }

        if (strpos($Block['element']['text'], '|') !== false && rtrim($Line['text'], ' -:|') === '') {
            $alignments = [];

            $divider = $Line['text'];

            $divider = trim($divider);
            $divider = trim($divider, '|');

            $dividerCells = explode('|', $divider);

            foreach ($dividerCells as $dividerCell) {
                $dividerCell = trim($dividerCell);

                if ($dividerCell === '') {
                    continue;
                }

                $alignment = null;

                if ($dividerCell[0] === ':') {
                    $alignment = 'left';
                }

                if (substr($dividerCell, -1) === ':') {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }

                $alignments[] = $alignment;
            }

            // ~

            $HeaderElements = [];

            $header = $Block['element']['text'];

            $header = trim($header);
            $header = trim($header, '|');

            $headerCells = explode('|', $header);

            foreach ($headerCells as $index => $headerCell) {
                $headerCell = trim($headerCell);

                $HeaderElement = [
                    'name'    => 'th',
                    'text'    => $headerCell,
                    'handler' => 'line',
                ];

                if (isset($alignments[$index])) {
                    $alignment = $alignments[$index];

                    $HeaderElement['attributes'] = [
                        'style' => 'text-align: '.$alignment.';',
                    ];
                }

                $HeaderElements[] = $HeaderElement;
            }

            // ~

            $Block = [
                'alignments' => $alignments,
                'identified' => true,
                'element'    => [
                    'name'    => 'table',
                    'handler' => 'elements',
                ],
            ];

            $Block['element']['text'][] = [
                'name'    => 'thead',
                'handler' => 'elements',
            ];

            $Block['element']['text'][] = [
                'name'    => 'tbody',
                'handler' => 'elements',
                'text'    => [],
            ];

            $Block['element']['text'][0]['text'][] = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $HeaderElements,
            ];

            return $Block;
        }
    }

    protected function blockTableContinue($Line, array $Block)
    {
        if (isset($Block['interrupted'])) {
            return;
        }

        if ($Line['text'][0] === '|' || strpos($Line['text'], '|')) {
            $Elements = [];

            $row = $Line['text'];

            $row = trim($row);
            $row = trim($row, '|');

            preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);

            foreach ($matches[0] as $index => $cell) {
                $cell = trim($cell);

                $Element = [
                    'name'    => 'td',
                    'handler' => 'line',
                    'text'    => $cell,
                ];

                if (isset($Block['alignments'][$index])) {
                    $Element['attributes'] = [
                        'style' => 'text-align: '.$Block['alignments'][$index].';',
                    ];
                }

                $Elements[] = $Element;
            }

            $Element = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $Elements,
            ];

            $Block['element']['text'][1]['text'][] = $Element;

            return $Block;
        }
    }

    //
    // ~
    //

    protected function paragraph($Line)
    {
        return [
            'element' => [
                'name'    => 'p',
                'text'    => $Line['text'],
                'handler' => 'line',
            ],
        ];
    }

    //
    // Inline Elements
    //

    protected $InlineTypes = [
        '"'  => ['SpecialCharacter'],
        '!'  => ['Image'],
        '&'  => ['SpecialCharacter'],
        '*'  => ['Emphasis'],
        ':'  => ['Url'],
        '<'  => ['UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'],
        '>'  => ['SpecialCharacter'],
        '['  => ['Link'],
        '_'  => ['Emphasis'],
        '`'  => ['Code'],
        '~'  => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
    ];

    // ~

    protected $inlineMarkerList = '!"*_&[:<>`~\\';

    //
    // ~
    //

    public function line($text, $nonNestables = [])
    {
        $markup = '';

        // $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inlineMarkerList)) {
            $marker = $excerpt[0];

            $markerPosition = strpos($text, $marker);

            $Excerpt = ['text' => $excerpt, 'context' => $text];

            foreach ($this->InlineTypes[$marker] as $inlineType) {
                // check to see if the current inline type is nestable in the current context

                if (!empty($nonNestables) && in_array($inlineType, $nonNestables)) {
                    continue;
                }

                $Inline = $this->{'inline'.$inlineType}($Excerpt);

                if (!isset($Inline)) {
                    continue;
                }

                // makes sure that the inline belongs to "our" marker

                if (isset($Inline['position']) && $Inline['position'] > $markerPosition) {
                    continue;
                }

                // sets a default inline position

                if (!isset($Inline['position'])) {
                    $Inline['position'] = $markerPosition;
                }

                // cause the new element to 'inherit' our non nestables

                foreach ($nonNestables as $non_nestable) {
                    $Inline['element']['nonNestables'][] = $non_nestable;
                }

                // the text that comes before the inline
                $unmarkedText = substr($text, 0, $Inline['position']);

                // compile the unmarked text
                $markup .= $this->unmarkedText($unmarkedText);

                // compile the inline
                $markup .= isset($Inline['markup']) ? $Inline['markup'] : $this->element($Inline['element']);

                // remove the examined text
                $text = substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            // the marker does not belong to an inline

            $unmarkedText = substr($text, 0, $markerPosition + 1);

            $markup .= $this->unmarkedText($unmarkedText);

            $text = substr($text, $markerPosition + 1);
        }

        return $markup.$this->unmarkedText($text);
    }

    //
    // ~
    //

    protected function inlineCode($Excerpt)
    {
        $marker = $Excerpt['text'][0];

        if (preg_match('/^('.$marker.'+)[ ]*(.+?)[ ]*(?<!'.$marker.')\1(?!'.$marker.')/s', $Excerpt['text'], $matches)) {
            $text = $matches[2];
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name' => 'code',
                    'text' => $text,
                ],
            ];
        }
    }

    protected function inlineEmailTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false && preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $Excerpt['text'], $matches)) {
            $url = $matches[1];

            if (!isset($matches[2])) {
                $url = 'mailto:'.$url;
            }

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 'a',
                    'text'       => $matches[1],
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }
    }

    protected function inlineEmphasis($Excerpt)
    {
        if (!isset($Excerpt['text'][1])) {
            return;
        }

        $marker = $Excerpt['text'][0];

        if ($Excerpt['text'][1] === $marker && preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches)) {
            $emphasis = 'strong';
        } elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return;
        }

        return [
            'extent'  => strlen($matches[0]),
            'element' => [
                'name'    => $emphasis,
                'handler' => 'line',
                'text'    => $matches[1],
            ],
        ];
    }

    protected function inlineEscapeSequence($Excerpt)
    {
        if (isset($Excerpt['text'][1]) && in_array($Excerpt['text'][1], $this->specialCharacters)) {
            return [
                'markup' => $Excerpt['text'][1],
                'extent' => 2,
            ];
        }
    }

    protected function inlineImage($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) || $Excerpt['text'][1] !== '[') {
            return;
        }

        $Excerpt['text'] = substr($Excerpt['text'], 1);

        $Link = $this->inlineLink($Excerpt);

        if ($Link === null) {
            return;
        }

        $Inline = [
            'extent'  => $Link['extent'] + 1,
            'element' => [
                'name'       => 'img',
                'attributes' => [
                    'src' => $Link['element']['attributes']['href'],
                    'alt' => $Link['element']['text'],
                ],
            ],
        ];

        $Inline['element']['attributes'] += $Link['element']['attributes'];

        unset($Inline['element']['attributes']['href']);

        return $Inline;
    }

    protected function inlineLink($Excerpt)
    {
        $Element = [
            'name'         => 'a',
            'handler'      => 'line',
            'nonNestables' => ['Url', 'Link'],
            'text'         => null,
            'attributes'   => [
                'href'  => null,
                'title' => null,
            ],
        ];

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches)) {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        } else {
            return;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches)) {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2])) {
                $Element['attributes']['title'] = substr($matches[2], 1, -1);
            }

            $extent += strlen($matches[0]);
        } else {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            } else {
                $definition = strtolower($Element['text']);
            }

            if (!isset($this->DefinitionData['Reference'][$definition])) {
                return;
            }

            $Definition = $this->DefinitionData['Reference'][$definition];

            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        return [
            'extent'  => $extent,
            'element' => $Element,
        ];
    }

    protected function inlineMarkup($Excerpt)
    {
        if ($this->markupEscaped || $this->safeMode || strpos($Excerpt['text'], '>') === false) {
            return;
        }

        if ($Excerpt['text'][1] === '/' && preg_match('/^<\/\w[\w-]*[ ]*>/s', $Excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        if ($Excerpt['text'][1] === '!' && preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', $Excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        if ($Excerpt['text'][1] !== ' ' && preg_match('/^<\w[\w-]*(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*\/?>/s', $Excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }
    }

    protected function inlineSpecialCharacter($Excerpt)
    {
        if ($Excerpt['text'][0] === '&' && !preg_match('/^&#?\w+;/', $Excerpt['text'])) {
            return [
                'markup' => '&amp;',
                'extent' => 1,
            ];
        }

        $SpecialCharacter = ['>' => 'gt', '<' => 'lt', '"' => 'quot'];

        if (isset($SpecialCharacter[$Excerpt['text'][0]])) {
            return [
                'markup' => '&'.$SpecialCharacter[$Excerpt['text'][0]].';',
                'extent' => 1,
            ];
        }
    }

    protected function inlineStrikethrough($Excerpt)
    {
        if (!isset($Excerpt['text'][1])) {
            return;
        }

        if ($Excerpt['text'][1] === '~' && preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excerpt['text'], $matches)) {
            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'    => 'del',
                    'text'    => $matches[1],
                    'handler' => 'line',
                ],
            ];
        }
    }

    protected function inlineUrl($Excerpt)
    {
        if ($this->urlsLinked !== true || !isset($Excerpt['text'][2]) || $Excerpt['text'][2] !== '/') {
            return;
        }

        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $url = $matches[0][0];

            return [
                'extent'   => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element'  => [
                    'name'       => 'a',
                    'text'       => $url,
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }
    }

    protected function inlineUrlTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false && preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $Excerpt['text'], $matches)) {
            $url = $matches[1];

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 'a',
                    'text'       => $url,
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }
    }

    // ~

    protected function unmarkedText($text)
    {
        if ($this->breaksEnabled) {
            return preg_replace('/[ ]*\n/', "<br />\n", $text);
        }
        $text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);

        return str_replace(" \n", "\n", $text);
    }

    //
    // Handlers
    //

    protected function element(array $Element)
    {
        if ($this->safeMode) {
            $Element = $this->sanitiseElement($Element);
        }

        $markup = '<'.$Element['name'];

        if (isset($Element['attributes'])) {
            foreach ($Element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' '.$name.'="'.self::escape($value).'"';
            }
        }

        if (isset($Element['text'])) {
            $markup .= '>';

            if (!isset($Element['nonNestables'])) {
                $Element['nonNestables'] = [];
            }

            if (isset($Element['handler'])) {
                $markup .= $this->{$Element['handler']}($Element['text'], $Element['nonNestables']);
            } else {
                $markup .= self::escape($Element['text'], true);
            }

            $markup .= '</'.$Element['name'].'>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    protected function elements(array $Elements)
    {
        $markup = '';

        foreach ($Elements as $Element) {
            $markup .= "\n".$this->element($Element);
        }

        return $markup."\n";
    }

    // ~

    protected function li($lines)
    {
        $markup = $this->lines($lines);

        $trimmedMarkup = trim($markup);

        if (!in_array('', $lines) && substr($trimmedMarkup, 0, 3) === '<p>') {
            $markup = $trimmedMarkup;
            $markup = substr($markup, 3);

            $position = strpos($markup, '</p>');

            $markup = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    //
    // Deprecated Methods
    //

    public function parse($text)
    {
        return $this->text($text);
    }

    protected function sanitiseElement(array $Element)
    {
        static $goodAttribute = '/^[a-zA-Z0-9][a-zA-Z0-9-_]*+$/';
        static $safeUrlNameToAtt = [
            'a'   => 'href',
            'img' => 'src',
        ];

        if (isset($safeUrlNameToAtt[$Element['name']])) {
            $Element = $this->filterUnsafeUrlInAttribute($Element, $safeUrlNameToAtt[$Element['name']]);
        }

        if (!empty($Element['attributes'])) {
            foreach ($Element['attributes'] as $att => $val) {
                // filter out badly parsed attribute
                if (!preg_match($goodAttribute, $att)) {
                    unset($Element['attributes'][$att]);
                }
                // dump onevent attribute
                elseif (self::striAtStart($att, 'on')) {
                    unset($Element['attributes'][$att]);
                }
            }
        }

        return $Element;
    }

    protected function filterUnsafeUrlInAttribute(array $Element, $attribute)
    {
        foreach ($this->safeLinksWhitelist as $scheme) {
            if (self::striAtStart($Element['attributes'][$attribute], $scheme)) {
                return $Element;
            }
        }

        $Element['attributes'][$attribute] = str_replace(':', '%3A', $Element['attributes'][$attribute]);

        return $Element;
    }

    //
    // Static Methods
    //

    protected static function escape($text, $allowQuotes = false)
    {
        return htmlspecialchars($text, $allowQuotes ? ENT_NOQUOTES : ENT_QUOTES, 'UTF-8');
    }

    protected static function striAtStart($string, $needle)
    {
        $len = strlen($needle);

        if ($len > strlen($string)) {
            return false;
        }

        return strtolower(substr($string, 0, $len)) === strtolower($needle);
    }

    public static function instance($name = 'default')
    {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        $instance = new static();

        self::$instances[$name] = $instance;

        return $instance;
    }

    private static $instances = [];

    //
    // Fields
    //

    protected $DefinitionData;

    //
    // Read-Only

    protected $specialCharacters = [
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    ];

    protected $StrongRegex = [
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',
    ];

    protected $EmRegex = [
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    ];

    protected $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';

    protected $voidElements = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    ];

    protected $textLevelElements = [
        'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
        'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing',
        'i', 'rp', 'del', 'code',          'strike', 'marquee',
        'q', 'rt', 'ins', 'font',          'strong',
        's', 'tt', 'kbd', 'mark',
        'u', 'xm', 'sub', 'nobr',
        'sup', 'ruby',
        'var', 'span',
        'wbr', 'time',
    ];
}
