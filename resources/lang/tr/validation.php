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

    'accepted'   => ' :attribute kabul edilmek zorundadır.',
    'active_url' => ':attribute geçerli bir link değil.',
    'after'      => ' :attribute dan sonraki bir tarih olmak zorunda :date ',
    'alpha'      => ':attribute sadece harf içermelidir.',
    'alpha_dash' => ':attribute sadece harf, sayı , ve çizgiler içermelidir.',
    'alpha_num'  => ':attribute sadece harf ve sayı içermelidir.',
    'array'      => 'The :attribute bir sıra olmalıdır.',
    'before'     => 'The :attribute dan önceki bir tarih olmak zorunda :date ',
    'between'    => [
        'numeric' => ':attribute :min ve :max aralığında olmak zorundadır.',
        'file'    => ':attribute  :min and :max kilobayt aralığında olmak zorundadır.',
        'string'  => ':attribute :min and :max karakter aralığında olmak zorundadır.',
        'array'   => ':attribute :min and :max eşya aralığında olmak zorundadır.',
    ],
    'boolean'        => ':attribute boşluğu doğru ya da yanlış olmak zorundadır.',
    'confirmed'      => ':attribute doğrulaması uyuşmuyor.',
    'date'           => ':attribute geçerli bir tarih değil.',
    'date_format'    => ':attribute formatıyla eşleşmemektedir. :format ',
    'different'      => ':attribute ve :other farklı olmak zorundadır.',
    'digits'         => ':attribute :digits digits olmak zorundadır.',
    'digits_between' => ':attribute :min and :max aralığında hane içermelidir.',
    'dimensions'     => ':attribute yanlış fotoğraf ölçülerine sahip.',
    'distinct'       => ':attribute aynı iki değere sahip.',
    'email'          => ':attribute geçerli bir e-mail adresi olmak zorundadır.',
    'exists'         => 'Seçilen :attribute zaten var.',
    'file'           => ':attribute dosya olmak zorunda.',
    'filled'         => ':attribute alanı gereklidir.',
    'image'          => ':attribute bir fotoğraf olmak zorundadır.',
    'in'             => 'Seçilen :attributevalid geçerli değil.',
    'in_array'       => ' :attribute alanı :other içinde değil.',
    'integer'        => ':attribute bir tamsayı olmak zorundadır.',
    'ip'             => ':attribute geçerli bir IP adresi olmak zorundadır.',
    'json'           => ':attribute geçerli bir JSON metni olmak zorundadır.',
    'max'            => [
        'numeric' => ':attribute :max dan büyük olmamalıdır.',
        'file'    => ':attribute :max kilobayttan büyük olmamalıdır.',
        'string'  => ':attribute :max karakterden büyük olmamalıdır.',
        'array'   => ':attribute :max eşyadan büyük olmamalıdır.',
    ],
    'mimes'     => ':attribute dosyanın şu türü olmalıdır. :values .',
    'mimetypes' => ':attribute dosyanın şu türü olmalıdır. :values .',
    'min'       => [
        'numeric' => ':attribute en az :min kadar içermelidir.',
        'file'    => ':attribute en az :min kadar kilobayt içermelidir.',
        'string'  => ':attribute en az :min kadar karakter içermelidir.',
        'array'   => ':attribute en az :min kadar eşya içermelidir.',
    ],
    'not_in'               => 'Seçilmiş :attribute geçerli değil.',
    'numeric'              => ':attribute numara olmak zorundadır.',
    'present'              => ':attribute alanı bir hediye olmak zorundadır.',
    'regex'                => 'Seçilmiş :attribute geçerli değil.',
    'required'             => ':attribute alanı gereklidir.',
    'required_if'          => ':attribute alanı :other :value olduğu zaman gereklidir.',
    'required_unless'      => ':attribute  :other :values in içerisinde olduğu zaman gereklidir.',
    'required_with'        => ':attribute alanı :values hediye olduğunda gereklidir.',
    'required_with_all'    => ':attribute alanı :values hediye olduğunda gereklidir.',
    'required_without'     => ':attribute alanı :values hediye olmadığında gereklidir.',
    'required_without_all' => ':attribute alanı :values ların hediye olmadığı zaman geçerlidir.',
    'same'                 => ':attribute ve :other eşleşmek zorundadır.',
    'size'                 => [
        'numeric' => ':attribute :size olmak zorundadır.',
        'file'    => ':attribute :size kilobayt olmak zorundadır.',
        'string'  => ':attribute :size karakter olmak zorundadır.',
        'array'   => ':attribute :size eşyalarından içermelidir.',
    ],
    'string'   => ':attribute bir metin olmak zorundadır.',
    'timezone' => ':attribute geçerli bir alan olmak zorundadır.',
    'unique'   => ':attribute zaten alındı.',
    'uploaded' => ':attribute yüklenirken hata oluştu.',
    'url'      => ':attribute formatı geçerli değil.',
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
