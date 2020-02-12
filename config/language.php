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

return [

    /*
    |--------------------------------------------------------------------------
    | Enable All Language Routes
    |--------------------------------------------------------------------------
    |
    | This option enable language route.
    |
    */
    'route'         => true,

    /*
    |--------------------------------------------------------------------------
    | Enable Language Home Route
    |--------------------------------------------------------------------------
    |
    | This option enable language route to set language and return
    | to url('/')
    |
    */
    'home'          => true,

    /*
    |--------------------------------------------------------------------------
    | Add Language Code
    |--------------------------------------------------------------------------
    |
    | This option will add the language code to the redirected url
    |
    */
    'url'          => false,

    /*
    |--------------------------------------------------------------------------
    | Carbon Language
    |--------------------------------------------------------------------------
    |
    | This option the language of carbon library.
    |
    */
    'carbon'        => true,

    /*
    |--------------------------------------------------------------------------
    | Date Language
    |--------------------------------------------------------------------------
    |
    | This option the language of jenssegers/date library.
    |
    */
    'date'          => false,

    /*
    |--------------------------------------------------------------------------
    | Routes Prefix
    |--------------------------------------------------------------------------
    |
    | This option indicates the prefix for language routes.
    |
    */
    'prefix'        => 'languages',

    /*
    |--------------------------------------------------------------------------
    | Flags
    |--------------------------------------------------------------------------
    |
    | This option indicates the flags features.
    |
    */

    'flags'         => ['width' => '20px', 'ul_class' => '', 'li_class' => '', 'img_class' => ''],

    /*
    |--------------------------------------------------------------------------
    | Language code mode
    |--------------------------------------------------------------------------
    |
    | This option indicates the language code and name to be used, short/long
    | and english/native.
    | Short: language code (en)
    | Long: languagecode-COUNTRYCODE (en-GB)
    |
    */

    'mode'          => ['code' => 'short', 'name' => 'native'],

    /*
    |--------------------------------------------------------------------------
    | Allowed languages
    |--------------------------------------------------------------------------
    |
    | This options indicates the language allowed languages.
    |
    */

    'allowed'       => [
        'ar',
        'az',
        'be',
        'bg',
        'bn',
        'bs',
        'ca',
        'cs',
        'cy',
        'da',
        'de',
        'da',
        'el',
        'en',
        'es',
        'et',
        'eu',
        'fa',
        'fi',
        'fr',
        'gl',
        'hi',
        'hr',
        'hu',
        'id',
        'is',
        'it',
        'ja',
        'ka',
        'kk',
        'km',
        'kn',
        'ko',
        'lt',
        'lv',
        'mk',
        'mn',
        'ms',
        'ne',
        'nl',
        'no',
        'pl',
        'pt',
        'ro',
        'ru',
        'si',
        'sk',
        'sl',
        'sq',
        'sr',
        'sv',
        'sw',
        'tg',
        'th',
        'tl',
        'tr',
        'uk',
        'ur',
        'uz',
        'vi',
        'zh-CN',
        'zh-TW',
    ],

    /*
    |--------------------------------------------------------------------------
    | All Languages
    |--------------------------------------------------------------------------
    |
    | This option indicates the language codes and names.
    |
    */

    'all' => [
        ['short' => 'ar',       'long' => 'ar-SA',      'english' => 'Arabic',              'native' => 'العربية'],
        ['short' => 'az',       'long' => 'az-AZ',      'english' => 'Azerbaijani',         'native' => 'Azərbaycan'],
        ['short' => 'be',       'long' => 'be-BE',      'english' => 'Belarusian',          'native' => 'беларускі'],
        ['short' => 'bg',       'long' => 'bg-BG',      'english' => 'Bulgarian',           'native' => 'български'],
        ['short' => 'bn',       'long' => 'bn-BD',      'english' => 'Bengali',             'native' => 'বাংলা'],
        ['short' => 'bs',       'long' => 'sr-BA',      'english' => 'Bosnian',             'native' => 'Bosanski'],
        ['short' => 'ca',       'long' => 'ca-CA',      'english' => 'Catalan',             'native' => 'Català'],
        ['short' => 'cs',       'long' => 'cs-CZ',      'english' => 'Czech',               'native' => 'Čeština'],
        ['short' => 'cy',       'long' => 'cy-CY',      'english' => 'Welsh',               'native' => 'Cymraeg'],
        ['short' => 'da',       'long' => 'da-DK',      'english' => 'Danish',              'native' => 'Dansk'],
        ['short' => 'de',       'long' => 'de-DE',      'english' => 'German',              'native' => 'Deutsch'],
        ['short' => 'el',       'long' => 'el-EL',      'english' => 'Greek',               'native' => 'Ελληνικά'],
        ['short' => 'en',       'long' => 'en-US',      'english' => 'English (US)',        'native' => 'English (US)'],
        ['short' => 'es',       'long' => 'es-ES',      'english' => 'Spanish',             'native' => 'Español'],
        ['short' => 'et',       'long' => 'et-EE',      'english' => 'Estonian',            'native' => 'Eesti'],
        ['short' => 'eu',       'long' => 'eu-EU',      'english' => 'Basque',              'native' => 'Euskal'],
        ['short' => 'fa',       'long' => 'fa-FA',      'english' => 'Persian',             'native' => 'فارسی'],
        ['short' => 'fi',       'long' => 'fi-FI',      'english' => 'Finnish',             'native' => 'Suomi'],
        ['short' => 'fr',       'long' => 'fr-FR',      'english' => 'French',              'native' => 'Français'],
        ['short' => 'gl',       'long' => 'gl-GL',      'english' => 'Galician',            'native' => 'Galego'],
        ['short' => 'hi',       'long' => 'hi-IN',      'english' => 'Hindi',               'native' => 'हिन्दी'],
        ['short' => 'hr',       'long' => 'hr-HR',      'english' => 'Croatian',            'native' => 'Hrvatski'],
        ['short' => 'hu',       'long' => 'hu-HU',      'english' => 'Hungarian',           'native' => 'Magyar'],
        ['short' => 'id',       'long' => 'id-ID',      'english' => 'Indonesian',          'native' => 'Bahasa Indonesia'],
        ['short' => 'is',       'long' => 'is-IS',      'english' => 'Icelandic',           'native' => 'Íslensku'],
        ['short' => 'it',       'long' => 'it-IT',      'english' => 'Italian',             'native' => 'Italiano'],
        ['short' => 'ja',       'long' => 'ja-JP',      'english' => 'Japanese',            'native' => '日本語'],
        ['short' => 'ka',       'long' => 'ka-KA',      'english' => 'Georgian',            'native' => 'ქართული'],
        ['short' => 'kk',       'long' => 'kk-KK',      'english' => 'Kazakh',              'native' => 'Қазақша'],
        ['short' => 'km',       'long' => 'km-KM',      'english' => 'Khmer',               'native' => 'ភាសាខ្មែរ'],
        ['short' => 'kn',       'long' => 'kn-KN',      'english' => 'Kannada',             'native' => 'ಕನ್ನಡ'],
        ['short' => 'ko',       'long' => 'ko-KR',      'english' => 'Korean',              'native' => '한국어'],
        ['short' => 'lt',       'long' => 'lt-LT',      'english' => 'Lithuanian',          'native' => 'Lietuvių'],
        ['short' => 'lv',       'long' => 'lv-LV',      'english' => 'Latvian',             'native' => 'Latviešu valoda'],
        ['short' => 'mk',       'long' => 'mk-MK',      'english' => 'Macedonian',          'native' => 'Македонски'],
        ['short' => 'mn',       'long' => 'mn-MN',      'english' => 'Mongolian',           'native' => 'Монгол хэл дээр'],
        ['short' => 'ms',       'long' => 'ms-MY',      'english' => 'Malay',               'native' => 'Bahasa Melayu'],
        ['short' => 'ne',       'long' => 'ne-NE',      'english' => 'Nepali',              'native' => 'नेपाली'],
        ['short' => 'nl',       'long' => 'nl-NL',      'english' => 'Dutch',               'native' => 'Nederlands'],
        ['short' => 'no',       'long' => 'no-NO',      'english' => 'Norwegian',           'native' => 'Norsk Bokmål'],
        ['short' => 'pl',       'long' => 'pl-PL',      'english' => 'Polish',              'native' => 'Polski'],
        ['short' => 'pt',       'long' => 'pt-PT',      'english' => 'Portuguese',          'native' => 'Português'],
        ['short' => 'ro',       'long' => 'ro-RO',      'english' => 'Romanian',            'native' => 'Română'],
        ['short' => 'ru',       'long' => 'ru-RU',      'english' => 'Russian',             'native' => 'Русский'],
        ['short' => 'si',       'long' => 'si-SI',      'english' => 'Sinhala',             'native' => 'සිංහල'],
        ['short' => 'sk',       'long' => 'sk-SK',      'english' => 'Slovak',              'native' => 'Slovenský'],
        ['short' => 'sl',       'long' => 'sl-SL',      'english' => 'Slovenian',           'native' => 'Slovenščina'],
        ['short' => 'sq',       'long' => 'sq-AL',      'english' => 'Albanian',            'native' => 'Shqip'],
        ['short' => 'sr',       'long' => 'sr-SR',      'english' => 'Serbian',             'native' => 'Српски'],
        ['short' => 'sv',       'long' => 'sv-SE',      'english' => 'Swedish',             'native' => 'Svenska'],
        ['short' => 'sw',       'long' => 'sw-SW',      'english' => 'Swahili',             'native' => 'Kiswahili'],
        ['short' => 'tg',       'long' => 'tg-TG',      'english' => 'Tajik',               'native' => 'Тоҷикӣ'],
        ['short' => 'th',       'long' => 'th-TH',      'english' => 'Thai',                'native' => 'ไทย'],
        ['short' => 'tl',       'long' => 'tl-TL',      'english' => 'Filipino',            'native' => 'Filipino'],
        ['short' => 'tr',       'long' => 'tr-TR',      'english' => 'Turkish',             'native' => 'Türkçe'],
        ['short' => 'uk',       'long' => 'uk-UA',      'english' => 'Ukrainian',           'native' => 'Українська'],
        ['short' => 'ur',       'long' => 'ur-UR',      'english' => 'Urdu',                'native' => 'اردو'],
        ['short' => 'uz',       'long' => 'uz-UZ',      'english' => 'Uzbek',               'native' => 'Ozbek'],
        ['short' => 'vi',       'long' => 'vi-VN',      'english' => 'Vietnamese',          'native' => 'Tiếng Việt'],
        ['short' => 'zh-CN',    'long' => 'zh-CN',      'english' => 'Chinese (S)',         'native' => '简体中文'],
        ['short' => 'zh-TW',    'long' => 'zh-TW',      'english' => 'Chinese (T)',         'native' => '繁體中文'],
    ],
];
