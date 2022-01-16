<?php

namespace Database\Seeders;

use App\Models\MediaLanguage;
use Illuminate\Database\Seeder;

class MediaLanguagesSeeder extends Seeder
{
    private $languages;

    public function __construct()
    {
        $this->languages = $this->getLanguages();
    }

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        foreach ($this->languages as $code => $language) {
            MediaLanguage::updateOrCreate([
                'code'  => $code,
                'name'  => $language,
            ]);
        }
    }

    private function getLanguages(): array
    {
        return [
            'aa' => 'Afar',
            'ab' => 'Abkhazian',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',

            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan',
            'br' => 'Breton',
            'bs' => 'Bosnian',

            'ca' => 'Catalan',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'cn' => 'Cantonese',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cu' => 'Slavic',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',

            'da' => 'Danish',
            'de' => 'German',
            'dv' => 'Divehi',
            'dz' => 'Dzongkha',

            'ee' => 'Ewe',
            'el' => 'Greek',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish',
            'et' => 'Estonian',
            'eu' => 'Basque',

            'fa' => 'Persian',
            'ff' => 'Fulah',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Frisian',

            'ga' => 'Irish',
            'gd' => 'Gaelic',
            'gl' => 'Galician',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'gv' => 'Manx',

            'ha' => 'Hausa',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian; Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',

            'ia' => 'Interlingua',
            'id' => 'Indonesian',
            'ie' => 'Interlingue',
            'ig' => 'Igbo',
            'ii' => 'Yi',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',

            'ja' => 'Japanese',
            'jv' => 'Javanese',

            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu',
            'kj' => 'Kuanyama',
            'kk' => 'Kazakh',
            'kl' => 'Kalaallisut',
            'km' => 'Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz',

            'la' => 'Latin',
            'lb' => 'Letzeburgesch',
            'lg' => 'Ganda',
            'li' => 'Limburgish',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',

            'mg' => 'Malagasy',
            'mh' => 'Marshall',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mo' => 'Moldavian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',

            'na' => 'Nauru',
            'nb' => 'Norwegian Bokmål',
            'nd' => 'Ndebele',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch',
            'nn' => 'Norwegian Nynorsk',
            'no' => 'Norwegian',
            'nr' => 'Ndebele',
            'nv' => 'Navajo',
            'ny' => 'Chichewa; Nyanja',

            'oc' => 'Occitan',
            'oj' => 'Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian; Ossetic',

            'pa' => 'Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pushto',
            'pt' => 'Portuguese',

            'qu' => 'Quechua',

            'rm' => 'Raeto-Romance',
            'rn' => 'Rundi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',

            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'sh' => 'Serbo-Croatian',
            'si' => 'Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'st' => 'Sotho',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',

            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',

            'ug' => 'Uighur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',

            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'Volapük',

            'wa' => 'Walloon',
            'wo' => 'Wolof',

            'xh' => 'Xhosa',
            'xx' => 'No Language',

            'yi' => 'Yiddish',
            'yo' => 'Yoruba',

            'za' => 'Zhuang',
            'zh' => 'Mandarin',
            'zu' => 'Zulu',
        ];
    }
}
