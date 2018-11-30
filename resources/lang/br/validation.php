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
    
    'accepted' => 'A :attribute deve ser aceita.',
    'active_url' => 'O :attribute não é uma URL válida.',
    'after' => 'O :attribute deve ser uma data depois de :date.',
    'alpha' => 'O :attribute pode conter apenas letras.',
    'alpha_dash' => 'O :attribute pode conter apenas letras, números e traços.',
    'alpha_num' => 'O :attribute pode conter apenas letras e números.',
    'array' => 'O :attribute deve ser uma matriz.',
    'before' => 'O :attribute deve ser uma data antes de :date.',
    'between' => [
        'array' => 'O :attribute deve ter entre :min e :max itens.',
        'file' => 'O :attribute deve estar entre :min e :max kilobytes.',
        'numeric' => 'O :attribute deve estar entre :min e :max.',
        'string' => 'O :attribute deve estar entre :min e :max caracteres.'
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do :attribute não corresponde.',
    'date' => 'A :attribute não é uma data válida.',
    'date_format' => 'A :attribute não corresponde ao formato :format.',
    'different' => 'O :attribute e :other deve ser diferente.',
    'digits' => 'A :attribute deve ser :digits dígitos.',
    'digits_between' => 'A :attribute deve estar entre :min e :max dígitos.',
    'dimensions' => 'O :attribute tem dimensões de imagem inválidas.',
    'distinct' => 'O campo :attribute tem um valor duplicado.',
    'email' => 'O :attribute deve ser um endereço de e-mail válido.',
    'exists' => 'O :attribute selecionado é inválido.',
    'file' => 'O :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute é obrigatório.',
    'image' => 'O :attribute deve ser uma imagem.',
    'in' => 'O :attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O :attribute deve ser um número inteiro.',
    'ip' => 'O :attribute deve ser um endereço IP válido.',
    'json' => 'O :attribute deve ser uma string JSON válida.',
    'max' => [
        'array' => 'O :attribute não pode ter mais de :max itens.',
        'file' => 'O :attribute não pode ser maior que :max kilobytes.',
        'numeric' => 'O :attribute não pode ser maior que :max.',
        'string' => 'O :attribute não pode ser maior que :max caracteres.'
    ],
    'mimes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'array' => 'O :attribute deve ter pelo menos :min itens.',
        'file' => 'O :attribute deve ter pelo menos :min kilobytes.',
        'numeric' => 'O :attribute deve ter pelo menos :min.',
        'string' => 'O :attribute deve ter pelo menos :min caracteres.'
    ],
    'not_in' => 'O :attribute selecionado é inválido.',
    'numeric' => 'O :attribute deve ser um número.',
    'present' => 'O campo :attribute deve estar presente.',
    'regex' => 'O formato :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O campo :attribute é obrigatório a menos que :other está em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O :attribute é obrigatório quando nenhum dos :values estão presentes.',
    'same' => 'O :attribute e :other devem ser iguais.',
    'size' => [
        'array' => 'O :attribute deve conter :size itens.',
        'file' => 'O :attribute deve ter :size kilobytes.',
        'numeric' => 'O :attribute deve ter :size.',
        'string' => 'O :attribute deve ter :size caracteres.'
    ],
    'string' => 'O :attribute deve ser uma string.',
    'timezone' => 'O :attribute deve ser uma zona válida.',
    'unique' => 'O :attribute já foi tomada.',
    'uploaded' => 'O :attribute falhou ao fazer o upload.',
    'url' => 'O formato :attribute é inválido.',

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
            'rule-name' => 'mensagem-personalizada'
        ]
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

    'attributes' => []

];
