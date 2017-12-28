<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Il :attribute deve essere accettato.',
    'active_url' => 'Il :attribute non è un URL valido.',
    'after' => 'Il :attribute deve avere una data dopo il :date.',
    'alpha' => 'Il :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il :attribute può contenere solo lettere, numeri, e trattini.',
    'alpha_num' => 'Il :attributepuò contenere solo lettere e numeri.',
    'array' => 'Il :attribute deve essere un array.',
    'before' => 'Il :attribute deve avere una data prima del :date.',
    'between' => [
        'numeric' => 'The :attribute deve essere tra :min e :max.',
        'file' => 'The :attribute deve essere tra :min e :max kilobytes.',
        'string' => 'The :attribute deve essere tra :min e :max caratteri.',
        'array' => 'The :attribute deve essere tra :min e :max items.',
    ],
    'boolean' => 'Il campo :attribute deve essere Vero o Falso.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute non è una data valida.',
    'date_format' => 'The :attribute non combacia con :format.',
    'different' => 'The :attribute e :other devono essere differenti.',
    'digits' => 'The :attribute deve avere :digits cifre.',
    'digits_between' => 'The :attribute deve essere tra :min e :max cifre.',
    'dimensions' => 'The :attribute ha una dimensione della immagine non valida.',
    'distinct' => 'Il campo :attribute ha un valore duplicato.',
    'email' => 'The :attribute deve essere un indirizzo email valido.',
    'exists' => 'The selected :attribute non è valido.',
    'file' => 'The :attribute deve essere un file.',
    'filled' => 'Il campo :attribute è necessario.',
    'image' => 'The :attribute deve essere una immagine.',
    'in' => 'Il selezionato :attribute non è valido.',
    'in_array' => 'Il campo :attribute non esiste in :other.',
    'integer' => 'The :attribute deve essere un numero.',
    'ip' => 'The :attribute deve essere un IP valido.',
    'json' => 'The :attribute deve essere una stringa JSON valida.',
    'max' => [
        'numeric' => 'The :attribute non deve superare :max.',
        'file' => 'The :attribute non deve superare :max kilobytes.',
        'string' => 'The :attribute non deve superare :max caratteri.',
        'array' => 'The :attribute non deve superare :max items.',
    ],
    'mimes' => 'The :attribute deve essere un file di tipo: :values.',
    'mimetypes' => 'The :attribute deve essere un file di tipo: :values.',
    'min' => [
        'numeric' => 'The :attribute deve essere almeno :min.',
        'file' => 'The :attribute deve essere almeno :min kilobytes.',
        'string' => 'The :attribute deve essere almeno :min caratteri.',
        'array' => 'The :attribute deve essere almeno :min items.',
    ],
    'not_in' => 'Il campo selezionato :attribute non è valido.',
    'numeric' => 'The :attribute deve essere un numero.',
    'present' => 'The :attribute deve essere presente.',
    'regex' => 'The :attribute il formato non è valido.',
    'required' => 'Il campo :attribute è richiesto.',
    'required_if' => 'The :attribute field è richiesto quando :other è :value.',
    'required_unless' => 'The :attribute è richiesto se :other è in :values.',
    'required_with' => 'The :attribute è richiesto quando :values è presente.',
    'required_with_all' => 'The :attribute è richiesto quando :values è presente.',
    'required_without' => 'The :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'The :attribute è richiesto quando nessuno dei valori :values sono presenti.',
    'same' => 'The :attribute è :other devono essere uguali.',
    'size' => [
        'numeric' => 'The :attribute deve essere di :size.',
        'file' => 'The :attribute deve essere di :size kilobytes.',
        'string' => 'The :attribute deve essere di :size caratteri.',
        'array' => 'The :attribute deve contenere :size items.',
    ],
    'string' => 'The :attribute deve essere una stringa.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute è già stato selezionato.',
    'uploaded' => 'The :attribute non è stato possibile caricarlo.',
    'url' => 'The :attribute il formato non è valido.',

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
