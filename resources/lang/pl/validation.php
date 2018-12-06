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

    'accepted'   => 'Musisz zaakceptować :attribute.',
    'active_url' => ':attribute nie jest poprawnym adresem URL.',
    'after'      => ':attribute musi być datą późniejszą niż :date.',
    'alpha'      => ':attribute może zawierać tylko litery.',
    'alpha_dash' => ':attribute może zawierać tylko litery, cyfry i myślniki.',
    'alpha_num'  => ':attribute może zawierać tylko litery i cyfry.',
    'array'      => ':attribute musi być tablicą.',
    'before'     => ':attribute musi byc datą wcześniejszą niż :date.',
    'between'    => [
        'numeric' => ':attribute musi być pomiędzy :min ,a :max.',
        'file'    => ':attribute musi mieć pomiędzy :min ,a :max kB.',
        'string'  => ':attribute musi być pomiędzy :min ,a :max znakami.',
        'array'   => ':attribute musi być pomiędzy :min ,a :max przedmiotów.',
    ],
    'boolean'        => ':attribute pole musi być prawdą lub fałszem.',
    'confirmed'      => 'Brak potwierdzenia :attribute.',
    'date'           => ':attribute nie jest datą.',
    'date_format'    => ':attribute nie trzyma się formatu :format.',
    'different'      => ':attribute i :other muszą być różne.',
    'digits'         => ':attribute musi być :digits znakowy.',
    'digits_between' => ':attribute musi być pomiędzy :min ,a :max znakami.',
    'dimensions'     => ':attribute ma niezgodne wymiary.',
    'distinct'       => ':attribute ma podwójną wartość.',
    'email'          => ':attribute musi być prawidłowym adresem email.',
    'exists'         => ':attribute jest niewłaściwy.',
    'file'           => ':attribute musi być plikiem.',
    'filled'         => 'To pole jest wymagane :attribute.',
    'image'          => ':attribute musi byc obrazkiem.',
    'in'             => ':attribute jest niewłaściwy.',
    'in_array'       => ':attribute nie istnieje w :other.',
    'integer'        => ':attribute musi być liczbą całkowitą.',
    'ip'             => ':attribute musi być prawidłowym adresem IP.',
    'json'           => ':attribute musi być prawidłową linią JSON.',
    'max'            => [
        'numeric' => ':attribute nie może być większy niż :max.',
        'file'    => ':attribute nie może mieć więcej  niż :max kB.',
        'string'  => ':attribute nie może mieć więcej :max znaków.',
        'array'   => ':attribute nie może mieć więcej niż :max przedmiotów.',
    ],
    'mimes'     => ':attribute musi byc plikiem: :values.',
    'mimetypes' => ':attribute musi być plikiem: :values.',
    'min'       => [
        'numeric' => ':attribute musi wynosić co najmniej :min.',
        'file'    => ':attribute musi mieć co najmniej :min kB.',
        'string'  => ':attribute musi być co najmniej :min znakowy.',
        'array'   => ':attribute musi mieć c najmniej :min przedmiotów.',
    ],
    'not_in'               => ':attribute jest nieprawidłowy.',
    'numeric'              => ':attribute musi być liczbą.',
    'present'              => ':attribute musi być obecny.',
    'regex'                => 'Format :attribute jest nieprawidłowy.',
    'required'             => 'To pole jest wymagane: :attribute.',
    'required_if'          => 'To pole (:attribute) jest wymagane kiedy :other jest :value.',
    'required_unless'      => 'To pole (:attribute) jest wymagane, chyba że :other mieści się w :values.',
    'required_with'        => 'To pole (:attribute) jest wymagane kiedy :values są obecne.',
    'required_with_all'    => 'To pole (:attribute) jest wymagane kiedy :values są obecne.',
    'required_without'     => 'To pole (:attribute) jest wymagane kiedy :values nie są obecne.',
    'required_without_all' => 'To pole (:attribute) jest wymagane kiedy żadna z :values nie jest obecna.',
    'same'                 => ':attribute i :other musza być równe.',
    'size'                 => [
        'numeric' => ':attribute musi być :size.',
        'file'    => ':attribute musi mieć :size kB.',
        'string'  => ':attribute musi mieć :size znaków.',
        'array'   => ':attribute musi zawierać :size przedmiotów.',
    ],
    'string'   => ':attribute musi być ciągiem znaków.',
    'timezone' => ':attribute musi być prawidłową strefa czasową.',
    'unique'   => ':attribute został już zajęty.',
    'uploaded' => 'Nie udało się wysłać :attribute.',
    'url'      => 'Format :attribute jest nieprawidłowy.',

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
