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

    'accepted'   => 'Il campo :attribute deve essere accettato.',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after'      => 'Il campo :attribute deve avere una data dopo il :date.',
    'alpha'      => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri, e trattini.',
    'alpha_num'  => 'Il campo :attribute può contenere solo lettere e numeri.',
    'array'      => 'Il campo :attribute deve essere un array.',
    'before'     => 'Il campo :attribute deve avere una data prima del :date.',
    'between'    => [
        'numeric' => 'Il campo :attribute deve essere tra :min e :max.',
        'file'    => 'Il campo :attribute deve essere tra :min e :max kilobytes.',
        'string'  => 'Il campo :attribute deve essere tra :min e :max caratteri.',
        'array'   => 'Il campo :attribute deve essere tra :min e :max items.',
    ],
    'boolean'        => 'Il campo :attribute deve essere Vero o Falso.',
    'confirmed'      => 'La conferma del campo :attribute non è uguale.',
    'date'           => 'Il campo :attribute non ha una data valida.',
    'date_format'    => 'Il campo :attribute non è uguale a :format.',
    'different'      => 'I campi :attribute e :other devono essere differenti.',
    'digits'         => 'Il campo :attribute deve avere :digits cifre.',
    'digits_between' => 'Il campo :attribute deve essere tra :min e :max cifre.',
    'dimensions'     => 'Il campo :attribute ha una dimensione della immagine non valida.',
    'distinct'       => 'Il campo :attribute ha un valore duplicato.',
    'email'          => 'Il campo :attribute deve essere un indirizzo email valido.',
    'exists'         => 'Il campo :attribute non è valido.',
    'file'           => 'Il campo :attribute deve essere un file.',
    'filled'         => 'Il campo :attribute è necessario.',
    'image'          => 'Il campo :attribute deve essere una immagine.',
    'in'             => 'Il campo :attribute non è valido.',
    'in_array'       => 'Il campo :attribute non esiste in :other.',
    'integer'        => 'Il campo :attribute deve essere un numero.',
    'ip'             => 'Il campo :attribute deve essere un IP valido.',
    'json'           => 'Il campo :attribute deve essere una stringa JSON valida.',
    'max'            => [
        'numeric' => 'Il campo :attribute non deve superare :max.',
        'file'    => 'Il campo :attribute non deve superare :max kilobytes.',
        'string'  => 'Il campo :attribute non deve superare :max caratteri.',
        'array'   => 'Il campo :attribute non deve superare :max items.',
    ],
    'mimes'     => 'Il campo :attribute deve essere un file di tipo: :values.',
    'mimetypes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'min'       => [
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'file'    => 'Il campo :attribute deve essere almeno :min kilobytes.',
        'string'  => 'Il campo :attribute deve essere almeno :min caratteri.',
        'array'   => 'Il campo :attribute deve essere almeno :min items.',
    ],
    'not_in'               => 'Il campo :attribute non è valido.',
    'numeric'              => 'Il campo :attribute deve essere un numero.',
    'present'              => 'Il campo :attribute deve essere presente.',
    'regex'                => 'Il campo :attribute ha un formato non valido.',
    'required'             => 'Il campo :attribute è richiesto.',
    'required_if'          => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless'      => 'Il campo :attribute è richiesto se :other è in :values.',
    'required_with'        => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_with_all'    => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_without'     => 'Il campo :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è richiesto quando nessuno dei valori :values sono presenti.',
    'same'                 => 'Il campo :attribute è :other devono essere uguali.',
    'size'                 => [
        'numeric' => 'Il campo :attribute deve essere di :size.',
        'file'    => 'Il campo :attribute deve essere di :size kilobytes.',
        'string'  => 'Il campo :attribute deve essere di :size caratteri.',
        'array'   => 'Il campo :attribute deve contenere :size items.',
    ],
    'string'   => 'Il campo :attribute deve essere una stringa.',
    'timezone' => 'Il campo :attribute must be a valid zone.',
    'unique'   => 'Il campo :attribute è già stato selezionato.',
    'uploaded' => 'Il campo :attribute non è stato possibile caricarlo.',
    'url'      => 'Il campo :attribute ha un formato non valido.',

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
