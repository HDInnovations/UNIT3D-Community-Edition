<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

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

    'accepted'             => '該 :attribute 必須被接受。',
    'active_url'           => '該 :attribute 不是有效的網址。',
    'after'                => '該 :attribute 必須在:date。之後',
    'after_or_equal'       => '該 :attribute 必須大於或等如 :date。',
    'alpha'                => '該 :attribute 只能包含字母。',
    'alpha_dash'           => '該 :attribute 只能包含字母，數字和破折號。',
    'alpha_num'            => '該 :attribute 只能包含字母和數字',
    'array'                => '該 :attribute 必須是一個陣列。',
    'before'               => '該 :attribute 必須是 :date 之前的日期。',
    'before_or_equal'      => '該 :attribute 必須小於或等如 :date。',
    'between'              => [
        'numeric' => '該 :attribute 必須在 :min 和 :max 之間。',
        'file'    => '該 :attribute 必須在 :min 和 :max KB之間。',
        'string'  => '該 :attribute 必須在 :min 和 :max 字元之間。',
        'array'   => '該 :attribute 必須有 :min 至 :max 項目。',
    ],
    'boolean'              => '該 :attribute 值必須是true或false。',
    'confirmed'            => '該 :attribute 確認不符。',
    'date'                 => '該 :attribute 不是有效的日期。',
    'date_equals'          => '該 :attribute 必須等如 :date。',
    'date_format'          => '該 :attribute 與格式 :format不匹配。',
    'different'            => '該 :attribute 和 :other 必須有所不同。',
    'digits'               => '該 :attribute 必須是 :digits 數位。',
    'digits_between'       => '該 :attribute 必須在 :min 和 :max 數位之間。',
    'dimensions'           => '該 :attribute 為無效的圖像尺寸。',
    'distinct'             => '該 :attribute 值重復了。',
    'email'                => '該 :attribute 必須是一個有效的E-mail地址。',
    'exists'               => '所選的 :attribute 無效。',
    'file'                 => '該 :attribute 必須是一個文件。',
    'filled'               => '該 :attribute 是必需的。',
    'gt'                   => [
        'numeric' => '該 :attribute 必須大於 :value。',
        'file'    => '該 :attribute 必須大於 :value kB。',
        'string'  => '該 :attribute 必須大於 :value 字元。',
        'array'   => '該 :attribute 必須多於 :value 物件。',
    ],
    'gte'                  => [
        'numeric' => '該 :attribute 必須大過或等如 :value。',
        'file'    => '該 :attribute 必須大過或等如 :value kB。',
        'string'  => '該 :attribute 必須大過或等如 :value 字元。',
        'array'   => '該 :attribute 必須多於或等如 :value 物件。',
    ],
    'image'                => '該 :attribute 必須是一個圖像。',
    'in'                   => '所選的 :attribute 無效。',
    'in_array'             => '該 :attribute 不存在於 :other。',
    'integer'              => '該 :attribute 必須是整數。',
    'ip'                   => '該 :attribute 必須是有效的IP地址。',
    'ipv4'                 => '該 :attribute 必須是有效的IPv4地址。',
    'ipv6'                 => '該 :attribute 必須是有效的IPv6地址。',
    'json'                 => '該 :attribute 必須是有效的JSON string。',
    'lt'                   => [
        'numeric' => '該 :attribute 必須小過 :value。',
        'file'    => '該 :attribute 必須小於 :value kB。',
        'string'  => '該 :attribute 必須小於 :value 字元。',
        'array'   => '該 :attribute 必須少於 :value 物件。',
    ],
    'lte'                  => [
        'numeric' => '該 :attribute 必須小過或等如 :value。',
        'file'    => '該 :attribute 必須小過或等如 :value kB。',
        'string'  => '該 :attribute 必須小過或等如 :value 字元。',
        'array'   => '該 :attribute 必須少於或等如 :value 物件。',
    ],
    'max'                  => [
        'numeric' => '該 :attribute 不可以大於 :max。',
        'file'    => '該 :attribute 不可以大於 :max KB。',
        'string'  => '該 :attribute 不可以大於 :max 字元。',
        'array'   => '該 :attribute 不可以超過 :max 項目。',
    ],
    'mimes'                => '該 :attribute 必須是一個類型為 :values的文件。',
    'mimetypes'            => '該 :attribute 必須是一個類型為 :values的文件。',
    'min'                  => [
        'numeric' => '該 :attribute 必須至少 :min。',
        'file'    => '該 :attribute 必須至少 :min KB。',
        'string'  => '該 :attribute 必須至少 :min 字元。',
        'array'   => '該 :attribute 至少必須有 :min 項目。',
    ],
    'not_in'               => '所選 :attribute 無效。',
    'not_regex'            => ' :attribute 的格式無效。',
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
        'numeric' => '該 :attribute 必須是 :size。',
        'file'    => '該 :attribute 必須是 :size KB。',
        'string'  => '該 :attribute 必須是 :size字元。',
        'array'   => '該 :attribute 必須包含 :size 項目。',
    ],
    'starts_with'          => '該 :attribute 必須由這些起始: :values',
    'string'               => '該 :attribute 必須是一個字符串。',
    'timezone'             => '該 :attribute 必須是有效的時區。',
    'unique'               => '該 :attribute 已經被取用過了。',
    'uploaded'             => '該 :attribute 未能上傳。',
    'url'                  => '該 :attribute 格式無效。',
    'uuid'                 => '該 :attribute 必須為有效的UUID.',

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
            'rule-name' => '自定義訊息',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
