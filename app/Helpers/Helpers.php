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
if (! function_exists('appurl')) {
    function appurl()
    {
        return config('app.url');
    }
}

if (! function_exists('href_profile')) {
    function href_profile($user)
    {
        $appurl = appurl();

        return sprintf('%s/users/%s', $appurl, $user->username);
    }
}

if (! function_exists('href_article')) {
    function href_article($article)
    {
        $appurl = appurl();

        return sprintf('%s/articles/%s', $appurl, $article->id);
    }
}

if (! function_exists('href_torrent')) {
    function href_torrent($torrent)
    {
        $appurl = appurl();

        return sprintf('%s/torrents/%s', $appurl, $torrent->id);
    }
}

if (! function_exists('href_request')) {
    function href_request($torrentRequest)
    {
        $appurl = appurl();

        return sprintf('%s/requests/%s', $appurl, $torrentRequest->id);
    }
}

if (! function_exists('href_poll')) {
    function href_poll($poll)
    {
        $appurl = appurl();

        return sprintf('%s/polls/%s', $appurl, $poll->id);
    }
}

if (! function_exists('href_playlist')) {
    function href_playlist($playlist)
    {
        $appurl = appurl();

        return sprintf('%s/playlists/%s', $appurl, $playlist->id);
    }
}

if (! function_exists('href_collection')) {
    function href_collection($collection)
    {
        $appurl = appurl();

        return sprintf('%s/mediahub/collections/%s', $appurl, $collection->id);
    }
}

if (! function_exists('tmdb_image')) {
    function tmdb_image($type, $original)
    {
        $new = match ($type) {
            'back_big'     => 'w1280',
            'back_small'   => 'w780',
            'poster_big'   => 'w500',
            'poster_mid'   => 'w342',
            'poster_small' => 'w92',
            'cast_face'    => 'w138_and_h175_face',
            'cast_mid'     => 'w185',
            'cast_big'     => 'w300',
            'still_mid'    => 'w400',
            'logo_small'   => 'h60',
            'logo_mid'     => 'w300',
            default        => 'original',
        };

        return \str_replace('/original/', '/'.$new.'/', $original);
    }
}

if (! function_exists('modal_style')) {
    function modal_style()
    {
        return (auth()->user()->style == 0) ? '' : ' modal-dark';
    }
}

if (! function_exists('rating_color')) {
    function rating_color($number)
    {
        if ($number > 0 && $number <= 3.9) {
            return 'text-danger';
        }

        if ($number >= 4 && $number <= 6.9) {
            return 'text-warning';
        }

        if ($number >= 7 && $number <= 10) {
            return 'text-success';
        }
    }
}

if (! function_exists('language_flag')) {
    function language_flag($language)
    {
        $flag = match ($language) {
            'English', 'English (US)'                                                  						      => 'us',
            'English (GB)'                                                                                                            => 'gb',
            'Arabic', 'Arabic (001)'                                                                                                  => 'ae',
            'Belarusian'                                                                                                              => 'by',
            'Bengali'                                                                                                                 => 'bd',
            'Bulgarian', 'Bulgarian (BG)'                                                                                             => 'bg',
            'Catalan'                                                                                                                 => 'ca',
            'Chinese', 'Mandarin (Hans)', 'Mandarin (Hant)', 'Cantonese (Hant)', 'Chinese (Simplied)', 'Chinese (Traditional)'        => 'cn',
            'Chinese (HK)'                                                                                                            => 'hk',
            'Croatian', 'Croatian (HR)'                                                                                               => 'hr',
            'Czech', 'Czech (CZ)'                                                                                                     => 'cz',
            'Danish', 'Danish (DK)'                                                                                                   => 'dk',
            'Dutch', 'Dutch (NL)'                                                                                                     => 'nl',
            'Estonian', 'Estonian (EE)'                                                                                               => 'ee',
            'Finnish', 'Finnish (FI)'                                                                                                 => 'fi',
            'French', 'French (FR)'                                                                                                   => 'fr',
            'French (CA)'                                                                                                             => 'can',
            'Georgian'                                                                                                                => 'ge',
            'German', 'German (DE)'                                                                                                   => 'de',
            'Greek', 'Greek (GR)'                                                                                                     => 'gr',
            'Hebrew', 'Hebrew (IL)'                                                                                                   => 'il',
            'Hindi', 'Tamil', 'Telugu', 'Hindi (IN)', 'Tamil (IN)', 'Telugu (IN)'                                                     => 'in',
            'Hungarian', 'Hungarian (HU)'					                                                      => 'hu',
            'Icelandic', 'Icelandic (IS)'                                                                                             => 'is',
            'Indonesian', 'Indonesian (ID)'					                                                      => 'id',
            'Italian', 'Italian (IT)'                                                                                                 => 'it',
            'Japanese', 'Japanese (JP)'                                                                                               => 'jp',
            'Korean', 'Korean (KR)'                                                                                                   => 'kr',
            'Latvian', 'Latvian (LV)'                                                                                                 => 'lv',
            'Lithuanian', 'Lithuanian (LT)'                                                                                           => 'lt',
            'Malay', 'Malay (MY)'                                                                                                     => 'my',
            'Macedonian', 'Macedonian (MK)'										              => 'mk',
            'Norwegian', 'Norwegian Bokmal', 'Norwegian (NO)', 'Norwegian Bokmal (NO)'                                                => 'no',
            'Persian'                                                                                                                 => 'ir',
            'Polish', 'Polish (PL)'                                                                                                   => 'pl',
            'Portuguese', 'Portuguese (PT)'                                                                                           => 'pt',
            'Portuguese (BR)'                                                                                                         => 'br',
            'Romanian', 'Romanian (RO)'                                                                                               => 'ro',
            'Russian', 'Russian (RU)'                                                                                                 => 'ru',
            'Serbian', 'Serbian-Latn-RS'                                                                                              => 'rs',
            'Slovak', 'Slovak (SK)'                                                                                                   => 'sk',
            'Slovenian', 'Slovenian (SI)'                                                                                             => 'si',
            'Spanish', 'Spanish (ES)'                                                                                                 => 'es',
            'Spanish (Latin America)'                                                                                                 => 'mx',
            'Swedish', 'Swedish (SE)'                                                                                                 => 'se',
            'Tagalog', 'fil'                                                                                                          => 'ph',
            'Thai', 'Thai (TH)'                                                                                                       => 'th',
            'Turkish', 'Turkish (TR)'                                                                                                 => 'tr',
            'Ukrainian', 'Ukrainian (UA)'                                                                                             => 'ua',
            'Vietnamese', 'Vietnamese (VN)'                                                                                           => 'vn',
            default                                                                                                                   => null,
        };

        return $flag !== null ? '/img/flags/'.$flag.'.png' : null;
    }
}
