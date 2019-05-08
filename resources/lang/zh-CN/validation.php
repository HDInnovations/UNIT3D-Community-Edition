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

    'accepted'   => '该 :attribute 必须被接受。',
    'active_url' => '该 :attribute 不是有效的网址。',
    'after'      => '该 :attribute 必须在:date。之後',
    'alpha'      => '该 :attribute 只能包含字母。',
    'alpha_dash' => '该 :attribute 只能包含字母，数字和破折号。',
    'alpha_num'  => '该 :attribute 只能包含字母和数字',
    'array'      => '该 :attribute 必须是一个阵列。',
    'before'     => '该 :attribute 必须是 :date 之前的日期。',
    'between'    => [
        'array'   => '该 :attribute 必须有 :min 至 :max 项目。',
        'file'    => '该 :attribute 必须在 :min 和 :max KB之间。',
        'numeric' => '该 :attribute 必须在 :min 和 :max 之间。',
        'string'  => '该 :attribute 必须在 :min 和 :max 字元之间。',
    ],
    'boolean'        => '该 :attribute 值必须是true或false。',
    'confirmed'      => '该 :attribute 确认不符。',
    'date'           => '该 :attribute 不是有效的日期。',
    'date_format'    => '该 :attribute 与格式 :format不匹配。',
    'different'      => '该 :attribute 和 :other 必须有所不同。',
    'digits'         => '该 :attribute 必须是 :digits 数位。',
    'digits_between' => '该 :attribute 必须在 :min 和 :max 数位之间。',
    'dimensions'     => '该 :attribute 为无效的图像尺寸。',
    'distinct'       => '该 :attribute 值重复了。',
    'email'          => '该 :attribute 必须是一个有效的E-mail地址。',
    'exists'         => '所选的 :attribute 无效。',
    'file'           => '该 :attribute 必须是一个文件。',
    'filled'         => '该 :attribute 是必需的。',
    'image'          => '该 :attribute 必须是一个图像。',
    'in'             => '所选的 :attribute 无效。',
    'in_array'       => '该 :attribute 不存在于 :other。',
    'integer'        => '该 :attribute 必须是整数。',
    'ip'             => '该 :attribute 必须是有效的IP地址。',
    'json'           => '该 :attribute 必须是有效的JSON string。',
    'max'            => [
        'array'   => '该 :attribute 不可以超过 :max 项目。',
        'file'    => '该 :attribute 不可以大于 :max KB。',
        'numeric' => '该 :attribute 不可以大于 :max。',
        'string'  => '该 :attribute 不可以大于 :max 字元。',
    ],
    'mimes'     => '该 :attribute 必须是一个类型为 :values的文件。',
    'mimetypes' => '该 :attribute 必须是一个类型为 :values的文件。',
    'min'       => [
        'array'   => '该 :attribute 至少必须有 :min 项目。',
        'file'    => '该 :attribute 必须至少 :min KB。',
        'numeric' => '该 :attribute 必须至少 :min。',
        'string'  => '该 :attribute 必须至少 :min 字元。',
    ],
    'not_in'               => '所选 :attribute 无效。',
    'numeric'              => '该 :attribute 必须是一个数字。',
    'present'              => '该 :attribute 必须存在。',
    'regex'                => '该 :attribute 格式无效。',
    'required'             => '该 :attribute 是必需的。',
    'required_if'          => '当 :other 是 :value时，该 :attribute 是必需的。',
    'required_unless'      => '当 :other 不是 :value时，该 :attribute 是必需的。',
    'required_with'        => '当 :values 存在时，该 :attribute 字段是必需的。',
    'required_with_all'    => '当 :values 存在时，该 :attribute 字段是必需的。',
    'required_without'     => '当 :values 不存在时，该 :attribute 字段是必需的。',
    'required_without_all' => '当所有 :values 不存在时，该 :attribute 字段是必需的。',
    'same'                 => '该 :attribute 和 :other 必须匹配。',
    'size'                 => [
        'array'   => '该 :attribute 必须包含 :size 项目。',
        'file'    => '该 :attribute 必须是 :size KB。',
        'numeric' => '该 :attribute 必须是 :size。',
        'string'  => '该 :attribute 必须是 :size字元。',
    ],
    'string'   => '该 :attribute 必须是一个字符串。',
    'timezone' => '该 :attribute 必须是有效的时区。',
    'unique'   => '该 :attribute 已经被取用过了。',
    'uploaded' => '该 :attribute 未能上传。',
    'url'      => '该 :attribute 格式无效。',

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
    'email_list' => '抱歉，这电邮域名不接纳在本站使用，请详阅本站的电邮域名白名单。',
    'recaptcha' => '请填上验证码。',

    'custom' => [
        'attribute-name' => [
            'rule-name' => '自定义讯息',
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
