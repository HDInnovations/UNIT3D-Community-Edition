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

return [

    /*
    |--------------------------------------------------------------------------
    | Replace list
    |--------------------------------------------------------------------------
    | An associative array with a list of words that you want to replace.
    | keys of the array will be the words that you want to replace and the
    | values will be the words with which the key words will be replaced e.g.
    |
    |     'replace' => [
    |         'seventh'  => '7th',
    |         'monthly'  => 'every month',
    |         'yearly'   => 'every year',
    |         'weekly'   => 'every week',
    |     ],
    |
    | In this case "seventh" will be replaced with "7th" and so on.
    |
    */
    'replace' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Redact List
    |--------------------------------------------------------------------------
    | Specify the words that you want to completely redact. The words
    | specified in here will have a blur effect placed over them.
    |
    |    'redact' => [
    |       'bitch',
    |    ],
    |
    |  In this case "bitch" will have a censor class wrapped around it
    |
    */
    'redact' => [
        'asshole',
        'bitch',
        'btch',
        'blowjob',
        'cock',
        'cawk',
        'clt',
        'clit',
        'clitoris',
        'cock',
        'cocksucker',
        'cum',
        'cunt',
        'cnt',
        'dildo',
        'dick',
        'douche',
        'fag',
        'faggot',
        'fcuk',
        'fuck',
        'fuk',
        'motherfucker',
        'nigga',
        'nigger',
        'nig',
        'penis',
        'pussy',
        'rimjaw',
        'scrotum',
        'shit',
        'sht',
        'slut',
        'twat',
        'whore',
        'whre',
        'vagina',
        'vag',
        'rape',
    ],

];
