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

    'accepted'   => '該 :attribute 必須被接受。',
    'active_url' => '該 :attribute 不是有效的網址。',
    'after'      => '該 :attribute 必須在:date。之後',
    'alpha'      => '該 :attribute 只能包含字母。',
    'alpha_dash' => '該 :attribute 只能包含字母，數字和破折號。',
    'alpha_num'  => '該 :attribute 只能包含字母和數字',
    'array'      => '該 :attribute 必須是一個陣列。',
    'before'     => '該 :attribute 必須是 :date 之前的日期。',
    'between'    => [
        'array'   => '該 :attribute 必須有 :min 至 :max 項目。',
        'file'    => '該 :attribute 必須在 :min 和 :max KB之間。',
        'numeric' => '該 :attribute 必須在 :min 和 :max 之間。',
        'string'  => '該 :attribute 必須在 :min 和 :max 字元之間。',
    ],
    'boolean'        => '該 :attribute 值必須是true或false。',
    'confirmed'      => '該 :attribute 確認不符。',
    'date'           => '該 :attribute 不是有效的日期。',
    'date_format'    => '該 :attribute 與格式 :format不匹配。',
    'different'      => '該 :attribute 和 :other 必須有所不同。',
    'digits'         => '該 :attribute 必須是 :digits 數位。',
    'digits_between' => '該 :attribute 必須在 :min 和 :max 數位之間。',
    'dimensions'     => '該 :attribute 為無效的圖像尺寸。',
    'distinct'       => '該 :attribute 值重復了。',
    'email'          => '該 :attribute 必須是一個有效的E-mail地址。',
    'exists'         => '所選的 :attribute 無效。',
    'file'           => '該 :attribute 必須是一個文件。',
    'filled'         => '該 :attribute 是必需的。',
    'image'          => '該 :attribute 必須是一個圖像。',
    'in'             => '所選的 :attribute 無效。',
    'in_array'       => '該 :attribute 不存在於 :other。',
    'integer'        => '該 :attribute 必須是整數。',
    'ip'             => '該 :attribute 必須是有效的IP地址。',
    'json'           => '該 :attribute 必須是有效的JSON string。',
    'max'            => [
        'array'   => '該 :attribute 不可以超過 :max 項目。',
        'file'    => '該 :attribute 不可以大於 :max KB。',
        'numeric' => '該 :attribute 不可以大於 :max。',
        'string'  => '該 :attribute 不可以大於 :max 字元。',
    ],
    'mimes'     => '該 :attribute 必須是一個類型為 :values的文件。',
    'mimetypes' => '該 :attribute 必須是一個類型為 :values的文件。',
    'min'       => [
        'array'   => '該 :attribute 至少必須有 :min 項目。',
        'file'    => '該 :attribute 必須至少 :min KB。',
        'numeric' => '該 :attribute 必須至少 :min。',
        'string'  => '該 :attribute 必須至少 :min 字元。',
    ],
    'not_in'               => '所選 :attribute 無效。',
    'numeric'              => '該 :attribute 必須是一個數字。',
    'present'              => '該 :attribute 必須存在。',
    'regex'                => '該 :attribute 格式無效。',
    'required'             => '該 :attribute 是必需的。',
    'required_if'          => '當 :other 是 :value時，該 :attribute 是必需的。',
    'required_unless'      => '當 :other 不是 :value時，該 :attribute 是必需的。',
    'required_with'        => '當 :values 存在時，該 :attribute 字段是必需的。',
    'required_with_all'    => '當 :values 存在時，該 :attribute 字段是必需的。',
    'required_without'     => '當 :values 不存在時，該 :attribute 字段是必需的。',
    'required_without_all' => '當所有 :values 不存在時，該 :attribute 字段是必需的。',
    'same'                 => '該 :attribute 和 :other 必須匹配。',
    'size'                 => [
        'array'   => '該 :attribute 必須包含 :size 項目。',
        'file'    => '該 :attribute 必須是 :size KB。',
        'numeric' => '該 :attribute 必須是 :size。',
        'string'  => '該 :attribute 必須是 :size字元。',
    ],
    'string'   => '該 :attribute 必須是一個字符串。',
    'timezone' => '該 :attribute 必須是有效的時區。',
    'unique'   => '該 :attribute 已經被取用過了。',
    'uploaded' => '該 :attribute 未能上傳。',
    'url'      => '該 :attribute 格式無效。',

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
    'email_list' => '抱歉，這電郵域名不接納在本站使用，請詳閱本站的電郵域名白名單。',
	'recaptcha' => '請填上驗證碼。',

    'custom' => [
        'attribute-name' => [
            'rule-name' => '自定義訊息',
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
