<?php

return [

    /*
     * |--------------------------------------------------------------------------
     * | Validation Language Lines
     * |--------------------------------------------------------------------------
     * |
     * | The following language lines contain the default error messages used by
     * | the validator class. Some of these rules have multiple versions such
     * | as the size rules. Feel free to tweak each of these messages here.
     * |
     */

    'accepted'   => 'Het :attribute moet worden geaccepteerd.',
    'active_url' => 'Het :attribute is geen geldige URL.',
    'after'      => 'Het :attribute moet een datum na :date zijn.',
    'alpha'      => 'Het :attribute mag alleen letters bevatten.',
    'alpha_dash' => 'Het :attribute mag alleen letters, cijfers, en spaties bevatten.',
    'alpha_num'  => 'Het :attribute mag alleen letters, cijfers bevatten.',
    'array'      => 'Het :attribute moet een array zijn.',
    'before'     => 'Het :attribute moet een datum voor :date zijn.',
    'between'    => [
        'numeric' => 'Het :attribute moet tussen :min en :max zijn.',
        'file'    => 'Het :attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => 'Het :attribute moet tussen :min en :max karakters zijn.',
        'array'   => 'Het :attribute moet tussen :min en :max items zijn.',
    ],
    'boolean'        => 'Het :attribute moet true of false zijn.',
    'confirmed'      => 'Het :attribute validatie klopt niet.',
    'date'           => 'Het :attribute is geen geldige datum.',
    'date_format'    => 'Het :attribute komt niet overeen met het formaat :format.',
    'different'      => 'Het :attribute en :other moeten verschillend zijn.',
    'digits'         => 'Het :attribute moet minimaal :digits cijfers zijn.',
    'digits_between' => 'Het :attribute moet tussen :min en :max cijfers zijn.',
    'dimensions'     => 'Het :attribute heeft een ongeldig afbeeldingsformaat.',
    'distinct'       => 'Het :attribute veld heeft een dubbele waarde.',
    'email'          => 'Het :attribute moet een geldig e-mail adres zijn.',
    'exists'         => 'Het selected :attribute is incorrect.',
    'file'           => 'Het :attribute moet een bestand zijn.',
    'filled'         => 'Het :attribute veld is verplicht.',
    'image'          => 'Het :attribute moet een afbeelding zijn.',
    'in'             => 'Het selected :attribute is incorrect.',
    'in_array'       => 'Het :attribute veld bestaat niet in :other.',
    'integer'        => 'Het :attribute moet een getal zijn.',
    'ip'             => 'Het :attribute moet een geldig IP address zijn.',
    'json'           => 'Het :attribute moet een geldig JSON string zijn.',
    'max'            => [
        'numeric' => 'Het :attribute mag niet groter zijn dan :max.',
        'file'    => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => 'Het :attribute mag niet groter zijn dan :max karakters.',
        'array'   => 'Het :attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes'     => 'Het :attribute moet het bestandsformaat type: :values zijn.',
    'mimetypes' => 'Het :attribute moet het bestandsformaat type: :values zijn.',
    'min'       => [
        'numeric' => 'Het :attribute moet minimaal :min zijn.',
        'file'    => 'Het :attribute moet minimaal :min kilobytes zijn.',
        'string'  => 'Het :attribute moet minimaal :min karakters zijn.',
        'array'   => 'Het :attribute moet minimaal :min items zijn.',
    ],
    'not_in'               => 'Het geselecteerde :attribute is incorrect.',
    'numeric'              => 'Het :attribute moet een getal zijn.',
    'present'              => 'Het :attribute veld moet aanwezig zijn.',
    'regex'                => 'Het :attribute formaat is incorrect.',
    'required'             => 'Het :attribute veld is verplicht.',
    'required_if'          => 'Het :attribute veld is verplicht als :other is :value.',
    'required_unless'      => 'Het :attribute veld is verplicht tenzij :other is in :values.',
    'required_with'        => 'Het :attribute veld is verplicht als :values niet aanwezig is.',
    'required_with_all'    => 'Het :attribute veld is verplicht als :values niet aanwezig is.',
    'required_without'     => 'Het :attribute veld is verplicht als :values niet aanwezig is.',
    'required_without_all' => 'Het :attribute veld is verplicht als geen van de :values aanwezig zijn.',
    'same'                 => 'Het :attribute en :other moeten overeenkomen.',
    'size'                 => [
        'numeric' => 'Het :attribute moet minimaal :size zijn.',
        'file'    => 'Het :attribute moet minimaal :size kilobytes zijn.',
        'string'  => 'Het :attribute moet minimaal :size karakters zijn.',
        'array'   => 'Het :attribute moet minimaal :size items bevatten.',
    ],
    'string'   => 'Het :attribute moet een string zijn.',
    'timezone' => 'Het :attribute moet een geldige zone zijn.',
    'unique'   => 'Het :attribute is al bezet.',
    'uploaded' => 'Het :attribute kan niet uploaden.',
    'url'      => 'Het :attribute formaat is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-bericht',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
