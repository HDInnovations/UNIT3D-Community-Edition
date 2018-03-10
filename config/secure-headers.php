<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

return [

    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     *
     * Available Value: 'nosniff'
     */

    'x-content-type-options' => 'nosniff',

    /*
     * X-Download-Options
     *
     * Reference: https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
     *
     * Available Value: 'noopen'
     */

    'x-download-options' => 'noopen',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     *
     * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.adobe.com/devnet/adobe-media-server/articles/cross-domain-xml-for-streaming.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */

    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-XSS-Protection
     *
     * Reference: https://blogs.msdn.microsoft.com/ieinternals/2011/01/31/controlling-the-xss-filter
     *
     * Available Value: '1', '0', '1; mode=block'
     */

    'x-xss-protection' => '1; mode=block',

    /*
     * Referrer-Policy
     *
     * Reference: https://w3c.github.io/webappsec-referrer-policy
     *
     * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */

    'referrer-policy' => 'same-origin',

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Please ensure your website had set up ssl/tls before enable hsts.
     */

    'hsts' => [
        'enable' => true,

        'max-age' => 15552000,

        'include-sub-domains' => true,
    ],

    /*
     * Public Key Pinning
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/Public_Key_Pinning
     *
     * hpkp will be ignored if hashes is empty.
     */

    'hpkp' => [
        'hashes' => [
            // [
            //     'algo' => 'sha256',
            //     'hash' => 'hash-value',
            // ],
        ],

        'include-sub-domains' => false,

        'max-age' => 15552000,

        'report-only' => false,

        'report-uri' => null,
    ],

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     *
     * csp will be ignored if custom-csp is not null. To disable csp, set custom-csp to empty string.
     *
     * Note: custom-csp does not support report-only.
     */
    'custom-csp' => '',

    'csp' => [
        'report-only' => false,

        'report-uri' => null,

        'upgrade-insecure-requests' => false,

        // enable or disable the automatic conversion of sources to https
        'https-transform-on-https-connections' => true,
        'httpsTransformOnHttpsConnections' => true,

        'base-uri' => [
            //
        ],

        'default-src' => [
            //
        ],

        'child-src' => [
            'allow' => [
                'https://www.youtube.com',
            ],
        ],

        'script-src' => [
            'allow' => [
                //
            ],

            'hashes' => [
                // ['sha256' => 'hash-value'],
            ],

            'nonces' => [
                //
            ],

            'self' => true,

            'unsafe-inline' => false,

            'unsafe-eval' => false,
        ],

        'style-src' => [
            'allow' => [
                'https://fonts.googleapis.com',
            ],

            'self' => true,

            'unsafe-inline' => true,
        ],

        'img-src' => [
            'allow' => [
                '*.imgur.com',
                'imgbox.com',
                '*.imgbox.com',
                'assets.fanart.tv',
                '*.imagebam.com',
                'ultraimg.com',
                'https://cdn.jsdelivr.net',
                'https://image.tmdb.org',
                'https://thetvdb.com',
                'https://www.themoviedb.org',
                'https://www.thetvdb.com',
                'http://thumbs2.imagebam.com',
            ],

            'types' => [
            ],

            'self' => true,

            'data' => false,
        ],

        /*
         * The following directives are all use 'allow' and 'self' flag.
         *
         * Note: default value of 'self' flag is false.
         */

        'font-src' => [
            'allow' => [
                'https://fonts.googleapis.com',
                'https://fonts.gstatic.com',
            ],
            'self' => true,
        ],

        'connect-src' => [
            'allow' => [
                'www.omdbapi.com',
                'https://api.themoviedb.org',
            ],
            'self' => true,
        ],

        'form-action' => [
            'self' => true,
        ],

        'frame-ancestors' => [
            'self' => true,
        ],

        'media-src' => [
            'self' => true,
        ],

        'object-src' => [
            'self' => false,
        ],

        /*
         * plugin-types only support 'allow'.
         */

        'plugin-types' => [
            //
        ],
    ],

];
