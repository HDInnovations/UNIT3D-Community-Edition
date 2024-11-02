<?php

declare(strict_types=1);

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

use App\Helpers\MarkdownExtra;

describe('markdown support', tests: function (): void {
    it(
        'Generates HTML from Markdown',
        function (
            string $result,
            string $markdown,
            bool $minify = true,
            bool $strict = false,
            bool $safeMode = false
        ): void {
            $service = (new MarkdownExtra())
                ->setSafeMode($safeMode)
                ->setStrictMode($strict);

            $html = $service->parse($markdown);
            $html = $minify ? str_replace(["\r\n", "\r", "\n"], '', $html) : $html;

            $this->assertEquals($result, $html);
        }
    )->with(function (): iterable {
        yield from basicMarkdown();

        yield from codeBlocks();

        yield from blockQuote();

        yield from extendedSyntax();
    });

    it('Handles auto linking', function (string $result, string $markdown, bool $autoLink = true): void {
        $service = (new MarkdownExtra())->setUrlsLinked($autoLink);
        $this->assertEquals($result, $service->parse($markdown));
    })->with(function (): iterable {
        yield 'auto link' => ['<p><a href="https://example.com">https://example.com</a></p>', 'https://example.com'];

        yield 'auto link disabled' => ['<p>https://example.com</p>', 'https://example.com', false];
    });
});

function basicMarkdown(): iterable
{
    yield 'heading' => ['<h1>Heading</h1>', '# Heading'];

    yield 'paragraph' => ['<p>Paragraph</p>', 'Paragraph'];

    yield 'table' => ['<table><thead><tr><th>Header</th></tr></thead><tbody><tr><td>Cell</td></tr></tbody></table>', "| Header |\n|--------|\n| Cell   |"];

    yield 'list' => ['<ul><li>Item 1</li><li>Item 2</li></ul>', "- Item 1\n- Item 2"];

    yield 'image' => ['<p><img src="https://example.com/image.jpg" alt="Image" /></p>', '![Image](https://example.com/image.jpg)'];

    yield 'link' => ['<p><a href="https://example.com">Link</a></p>', '[Link](https://example.com)'];

    yield 'inline link' => ['<p><a href="https://example.com">https://example.com</a></p>', '<https://example.com>'];

    yield 'inline URL' => ['<p><a href="https://example.com">https://example.com</a></p>', 'https://example.com'];

    yield 'inline email' => ['<p><a href="mailto:test@example.com">test@example.com</a></p>', '<test@example.com>'];

    yield 'bold' => ['<p><strong>This is bold</strong></p>', '**This is bold**'];

    yield 'italic' => ['<p><em>This is italic</em></p>', '*This is italic*'];

    yield 'escaped italic' => ['<p>I do not want _italic text_ here</p>', 'I do not want \_italic text\_ here'];

    yield 'copy' => ['<p>&copy;</p>', '&copy;'];

    yield 'strikethrough' => ['<p><del>This is strikethrough</del></p>', '~~This is strikethrough~~'];

    yield 'bold italic' => ['<p><strong><em>This is bold italic</em></strong></p>', '***This is bold italic***'];

    yield 'bold italic strikethrough' => ['<p><strong><em><del>This is bold italic strikethrough</del></em></strong></p>', '***~~This is bold italic strikethrough~~***'];

    yield 'code' => ['<p>Do not use <code>dump()</code> in your code please.</p>', 'Do not use `dump()` in your code please.'];

    yield 'heading level 1' => ['<h1>Header 1</h1><h2>Header 2</h2><h3>Header 3</h3><h4>Header 4</h4><h5>Header 5</h5><h6>Header 6</h6>', "# Header 1\n\n## Header 2\n\n### Header 3\n\n#### Header 4\n\n##### Header 5\n\n###### Header 6"];

    yield 'heading with custom id' => ['<h3 id="custom-id">My Great Heading</h3>', '### My Great Heading {#custom-id}'];

    yield 'heading level 1 and 2' => ['<h1>Heading level 1</h1><h2>Heading level 2</h2>', "Heading level 1\n===============\nHeading level 2\n---------------"];

    yield 'horizontal rule' => ['<hr />', '____'];

    yield 'escaped html' => ['<p>&lt;test</p>', '<test'];

    yield 'escaped backslash' => ['<p>\test</p>', '\\test'];

    yield 'comment' => ['', '[comment]: <> (This is a comment, it will not be included)'];

    yield 'comment' => ['', '[//]: <> (This is a comment, it will not be included)'];

    yield 'list with nested list' => [
        <<<'HTML'
<ul>
<li>Item 1<ul>
<li>Item 1.1
Test</li>
<li>Item 1.2<ul>
<li>Item 1.2.1</li>
</ul>
</li>
</ul>
</li>
<li>Item 2</li>
<li>Item 3</li>
</ul>
<p>Test</p>
HTML,
        <<<MD
- Item 1\n  - Item 1.1\n  Test\n  - Item 1.2\n    - Item 1.2.1\n- Item 2\n- Item 3\n\nTest
MD,
        false,
    ];

    yield 'ordered list' => ['<ol><li>Item 1</li><li>Item 2</li></ol>', "1. Item 1\n2. Item 2"];

    yield 'html elements with safe mode' => ['<p>&lt;strong&gt;Strong&lt;/strong&gt;</p>', '<strong>Strong</strong>', false, false, true];
}

function codeBlocks(): iterable
{
    yield 'code block' => [
        '<pre><code>echo "Hello, World!";</code></pre>',
        "```\necho \"Hello, World!\";\n```",
    ];

    yield 'code block with language' => [
        '<pre><code class="language-php">echo "Hello, World!";</code></pre>',
        "```php\necho \"Hello, World!\";\n```",
    ];

    yield 'code block with language json' => [
        <<<'HTML'
<pre><code class="language-json">
{
    "firstName": "John",
    "lastName": "Smith",
    "age": 25
}</code></pre>
HTML,
        <<<'MD'
```json
{
    "firstName": "John",
    "lastName": "Smith",
    "age": 25
}
MD,
        false,
    ];

    yield 'code block with language text' => [
        <<<'HTML'
<pre><code class="language-text">code();
address@domain.example</code></pre>
HTML,
        <<<'MD'
~~~text
code();
address@domain.example
~~~
MD,
        false,
    ];

    yield 'code block using 4 spaces' => [
        <<<'HTML'
<pre><code>{
  "firstName": "John",
  "lastName": "Smith",
  "age": 25
}</code></pre>
HTML,
        <<<'MD'
    {
      "firstName": "John",
      "lastName": "Smith",
      "age": 25
    }
MD,
        false,
    ];
}

function blockQuote(): iterable
{
    yield 'blockquote' => [
        <<<'HTML'
<blockquote>
<p>This is a quote
And here is the second line
One more line</p>
</blockquote>
<p>And this is not a quote</p>
HTML,
        <<<'MD'
> This is a quote
> And here is the second line
One more line

And this is not a quote
MD,
        false,
    ];
}

function extendedSyntax(): iterable
{
    yield 'footnotes' => [
        <<<'HTML'
<p>Here is a simple footnote<sup id="fnref1:1"><a href="#fn:1" class="footnote-ref">1</a></sup>. With some additional<sup id="fnref1:2"><a href="#fn:2" class="footnote-ref">2</a></sup> text after it.</p>
<div class="footnotes">
<hr />
<ol>
<li id="fn:1">
<p>Another footnote.&#160;<a href="#fnref1:1" rev="footnote" class="footnote-backref">&#8617;</a></p>
</li>
<li id="fn:2">
<p>My reference.
Some more text here.</p>
<p>Continuing here.&#160;<a href="#fnref1:2" rev="footnote" class="footnote-backref">&#8617;</a></p>
</li>
</ol>
</div>
HTML,
        <<<'MD'
Here is a simple footnote[^1]. With some additional[^2] text after it.

[^2]: My reference.
Some more text here.

    Continuing here.
[^1]: Another footnote.
MD,
        false,
    ];

    yield 'definition lists' => [
        <<<'HTML'
<dl>
<dt>First Term</dt>
<dd>This is the definition of the first term.</dd>
<dt>Second Term</dt>
<dd>This is one definition of the second term.</dd>
<dd>
<p>This is another definition of the second term.
Continuing here</p>
<p>One more line</p>
</dd>
</dl>
HTML,
        <<<'MD'
First Term
: This is the definition of the first term.

Second Term
: This is one definition of the second term.
: This is another definition of the second term.
Continuing here

    One more line
MD,
        false,
    ];

    yield 'abbreviations' => [
        <<<'HTML'
<p>The <abbr title="Hyper Text Markup Language">HTML</abbr> specification
is maintained by the <abbr title="World Wide Web Consortium">W3C</abbr>.</p>
HTML,
        <<<'MD'
The HTML specification
is maintained by the W3C.

*[HTML]: Hyper Text Markup Language
*[W3C]:  World Wide Web Consortium
MD,
        false,
    ];

    yield 'html elements' => [
        <<<'HTML'
<img src="https://example.com/image.jpg" alt="Image" />
<p>&lt;div&gt;&amp;star;â¨©™ï¸ð&lt;&amp;sol;div&gt;</p>
<p><strong>Strong ☆ ✨</strong></p>
HTML,
        <<<'MD'
<img src="https://example.com/image.jpg" alt="Image" />
<div>☆✨©™️😎</div>
<strong>Strong ☆ ✨</strong>
MD,
        false,
    ];

    yield 'HTML comments' => [
        <<<'HTML'
<!-- This is a comment -->
<!--
This
is a comment
too
-->
HTML,
        <<<'MD'
<!-- This is a comment -->
<!--
This
is a comment
too
-->
MD,
        false,
    ];
}
