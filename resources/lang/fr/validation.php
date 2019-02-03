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

    'accepted'   => 'La :attribute doit être accépté.',
    'active_url' => 'La :attribute est pas une URL valide.',
    'after'      => 'La :attribute doit être une date après :date.',
    'alpha'      => 'La :attribute ne peut uniquement contenir que des lettres.',
    'alpha_dash' => 'La :attribute ne peut contenir que des lettres, des chiffres et des tirets',
    'alpha_num'  => 'La :attribute ne peut contenir que des lettres et des chiffres.',
    'array'      => 'La :attribute doit être un tableau.',
    'before'     => 'La :attribute doit être une date avant :date.',
    'between'    => [
        'numeric' => 'La :attribute doit être entre :min et :max.',
        'file'    => 'La :attribute doit être entre :min et :max kilobytes.',
        'string'  => 'La :attribute doit faire entre :min et :max caractères.',
        'array'   => 'La :attribute doit avoir entre :min et :max éléments.',
    ],
    'boolean'        => 'La :attribute doit être vraie ou fausse.',
    'confirmed'      => 'La :attribute confirmation ne correspond pas.',
    'date'           => 'La :attribute n est pas une date valide.',
    'date_format'    => 'La :attribute ne correspond pas au format :format.',
    'different'      => 'La :attribute et :other doivent être différent.',
    'digits'         => 'La :attribute doit être de :digits chiffres.',
    'digits_between' => 'La :attribute doit être entre :min et :max chiffres.',
    'dimensions'     => 'La :attribute possède une dimension d image invalide.',
    'distinct'       => 'La :attribute possède une valeur dupliqué.',
    'email'          => 'La :attribute doit être une adresse email valide.',
    'exists'         => 'La selected :attribute n est pas valide.',
    'file'           => 'La :attribute doit être un fichier.',
    'filled'         => 'La :attribute est requis.',
    'image'          => 'La :attribute doit être une image.',
    'in'             => 'La sélectionné :attribute n est pas valide.',
    'in_array'       => 'La :attribute n existe pas dans :other.',
    'integer'        => 'La :attribute doit être une valeur numérique.',
    'ip'             => 'La :attribute doit être une adresse IP valide.',
    'json'           => 'La :attribute doit être au format JSON (valide).',
    'max'            => [
        'numeric' => 'La :attribute ne doit pas être plus grand que :max.',
        'file'    => 'La :attribute ne doit pas être plus grand que :max kilobytes.',
        'string'  => 'La :attribute ne doit pas être plus grand que :max caractères.',
        'array'   => 'La :attribute ne doit pas avoir plus que :max éléments.',
    ],
    'mimes'     => 'La :attribute doit être un fichier de type: :values.',
    'mimetypes' => 'La :attribute doit être un fichier de type: :values.',
    'min'       => [
        'numeric' => 'La :attribute doit être au minimun :min.',
        'file'    => 'La :attribute doit faire au minimun :min kilobytes.',
        'string'  => 'La :attribute doit être au minimun :min caractères.',
        'array'   => 'La :attribute doit avoir au minimun :min éléments.',
    ],
    'not_in'               => 'La :attribute sélectionné n est pas valide.',
    'numeric'              => 'La :attribute doit être un nombre.',
    'present'              => 'La :attribute doit être présent.',
    'regex'                => 'Le format de l atribut :attribute n est pas valide.',
    'required'             => 'La :attribute est requis.',
    'required_if'          => 'La :attribute est requis quand :other est :value.',
    'required_unless'      => 'La :attribute est requis à moins que :other se trouve dans :values.',
    'required_with'        => 'La :attribute est requis quand :values est présent.',
    'required_with_all'    => 'La :attribute est requis quand :values est présent.',
    'required_without'     => 'La :attribute est requis quand :values n est pas présent.',
    'required_without_all' => 'La :attribute est requis quand aucune de ces :values sont présentes.',
    'same'                 => 'La :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'La :attribute doit être une :size.',
        'file'    => 'La :attribute doit être une :size kilobytes.',
        'string'  => 'La :attribute doit être de :size caractères.',
        'array'   => 'La :attribute doit contenir :size éléments.',
    ],
    'string'   => 'La :attribute doit être une chaîne de caractères.',
    'timezone' => 'La :attribute doit être une zone valide.',
    'unique'   => 'La :attribute est déjà pris.',
    'uploaded' => 'La :attribute a échoué lors de l envoi.',
    'url'      => 'Le format de l atribut :attribute n est pas valide.',

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
            'rule-name' => 'custom-message',
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
