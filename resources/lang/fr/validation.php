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

    'accepted'   => 'L\'atribut :attribute doit être accépté.',
    'active_url' => 'L\'atribut :attribute n\'est pas une URL valide.',
    'after'      => 'L\'atribut :attribute doit être une date après :date.',
    'alpha'      => 'L\'atribut :attribute ne peut uniquement contenir que des lettres.',
    'alpha_dash' => 'L\'atribut :attribute ne peut contenir que des lettres, des chiffres et des tirets',
    'alpha_num'  => 'L\'atribut :attribute ne peut contenir que des lettres et des chiffres.',
    'array'      => 'L\'atribut :attribute doit être un tableau.',
    'before'     => 'L\'atribut :attribute doit être une date avant :date.',
    'between'    => [
        'numeric' => 'L\'atribut :attribute doit être entre :min et :max.',
        'file'    => 'L\'atribut :attribute doit être entre :min et :max kilobytes.',
        'string'  => 'L\'atribut :attribute doit faire entre :min et :max caractères.',
        'array'   => 'L\'atribut :attribute doit avoir entre :min et :max éléments.',
    ],
    'boolean'        => 'L\'atribut :attribute doit être vraie ou fausse.',
    'confirmed'      => 'L\'atribut :attribute confirmation ne correspond pas.',
    'date'           => 'L\'atribut :attribute n\'est pas une date valide.',
    'date_format'    => 'L\'atribut :attribute ne correspond pas au format :format.',
    'different'      => 'L\'atribut :attribute et :other doivent être différent.',
    'digits'         => 'L\'atribut :attribute doit être de :digits chiffres.',
    'digits_between' => 'L\'atribut :attribute doit être entre :min et :max chiffres.',
    'dimensions'     => 'L\'atribut :attribute possède une dimension d\'image invalide.',
    'distinct'       => 'L\'atribut :attribute possède une valeur dupliqué.',
    'email'          => 'L\'atribut :attribute doit être une adresse email valide.',
    'exists'         => 'L\'atribut selected :attribute n\'est pas valide.',
    'file'           => 'L\'atribut :attribute doit être un fichier.',
    'filled'         => 'L\'atribut :attribute est requis.',
    'image'          => 'L\'atribut :attribute doit être une image.',
    'in'             => 'L\'atribut sélectionné :attribute n\'est pas valide.',
    'in_array'       => 'L\'atribut :attribute n\'existe pas dans :other.',
    'integer'        => 'L\'atribut :attribute doit être une valeur numérique.',
    'ip'             => 'L\'atribut :attribute doit être une adresse IP valide.',
    'json'           => 'L\'atribut :attribute doit être au format JSON (valide).',
    'max'            => [
        'numeric' => 'L\'atribut :attribute ne doit pas être plus grand que :max.',
        'file'    => 'L\'atribut :attribute ne doit pas être plus grand que :max kilobytes.',
        'string'  => 'L\'atribut :attribute ne doit pas être plus grand que :max caractères.',
        'array'   => 'L\'atribut :attribute ne doit pas avoir plus que :max éléments.',
    ],
    'mimes'     => 'L\'atribut :attribute doit être un fichier de type: :values.',
    'mimetypes' => 'L\'atribut :attribute doit être un fichier de type: :values.',
    'min'       => [
        'numeric' => 'L\'atribut :attribute doit être au minimun :min.',
        'file'    => 'L\'atribut :attribute doit faire au minimun :min kilobytes.',
        'string'  => 'L\'atribut :attribute doit être au minimun :min caractères.',
        'array'   => 'L\'atribut :attribute doit avoir au minimun :min éléments.',
    ],
    'not_in'               => 'L\'atribut :attribute sélectionné n\'est pas valide.',
    'numeric'              => 'L\'atribut :attribute doit être un nombre.',
    'present'              => 'L\'atribut :attribute doit être présent.',
    'regex'                => 'Le format de l\'atribut :attribute n\'est pas valide.',
    'required'             => 'L\'atribut :attribute est requis.',
    'required_if'          => 'L\'atribut :attribute est requis quand :other est :value.',
    'required_unless'      => 'L\'atribut :attribute est requis à moins que :other se trouve dans :values.',
    'required_with'        => 'L\'atribut :attribute est requis quand :values est présent.',
    'required_with_all'    => 'L\'atribut :attribute est requis quand :values est présent.',
    'required_without'     => 'L\'atribut :attribute est requis quand :values n\'est pas présent.',
    'required_without_all' => 'L\'atribut :attribute est requis quand aucune de ces :values sont présentes.',
    'same'                 => 'L\'atribut :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'L\'atribut :attribute doit être une :size.',
        'file'    => 'L\'atribut :attribute doit être une :size kilobytes.',
        'string'  => 'L\'atribut :attribute doit être de :size caractères.',
        'array'   => 'L\'atribut :attribute doit contenir :size éléments.',
    ],
    'string'   => 'L\'atribut :attribute doit être une chaîne de caractères.',
    'timezone' => 'L\'atribut :attribute doit être une zone valide.',
    'unique'   => 'L\'atribut :attribute est déjà pris.',
    'uploaded' => 'L\'atribut :attribute a échoué lors de l\'envoi.',
    'url'      => 'Le format de l\'atribut :attribute n\'est pas valide.',

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
