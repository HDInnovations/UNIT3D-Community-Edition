<?php

declare(strict_types=1);
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
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'        => ':özellik kabul edilmelidir.',
    'active_url'      => ':özellik geçerli bir kaynak bulucu değil.',
    'after'           => ':özellik daha sonra bir tarih olmalıdır :date.',
    'after_or_equal'  => ':özellik tarihi :tarih tarihinden sonra veya tarihine eşit olmalıdır.',
    'alpha'           => ':özellik sadece harflerden oluşmalıdır.',
    'alpha_dash'      => ':özellik sadece harfler, rakamlar ve tirelerden oluşmalıdır.',
    'alpha_num'       => ':özellik sadece harfler ve rakamlar içermelidir.',
    'array'           => ':özellik dizi olmalıdır.',
    'before'          => ':özellik şundan daha önceki bir tarih olmalıdır :tarih.',
    'before_or_equal' => ':özellik tarihi :tarih tarihinden önce veya tarihine eşit olmalıdır.',
    'between'         => [
        'numeric' => ':özellik :asgari-:azami arasında olmalıdır.',
        'file'    => ':özellik :asgari-:azami arasındaki kilobayt değeri olmalıdır.',
        'string'  => ':özellik :asgari-:azami arasında karakterden oluşmalıdır.',
        'array'   => ':özellik :asgari-:azami arasında nesneye sahip olmalıdır.',
    ],
    'boolean'        => ':özellik sadece doğru veya yanlış olmalıdır.',
    'confirmed'      => ':özellik doğrulama eşleşmiyor.',
	'current_password' => 'The Şifre geçerli değil.',
    'date'           => ':özellik geçerli bir tarih değil.',
    'date_equals'    => 'The :özellik tarihine eş olmalıdır :tarih.',
    'date_format'    => ':özellik :format biçimi ile eşleşmiyor.',
    'different'      => ':özellik ile :diğer birbirinden farklı olmalıdır.',
    'digits'         => ':özellik :rakamlar rakam olmalıdır.',
    'digits_between' => ':özellik :azami ile :asgari arasında rakam olmalıdır.',
    'dimensions'     => ':özellik görsel ölçüleri geçersiz.',
    'distinct'       => ':özellik alanı yinelenen bir değere sahip.',
    'email'          => ':özellik biçimi geçersiz.',
    'exists'         => 'Seçili :özellik geçersiz.',
    'file'           => ':özellik dosya olmalıdır.',
    'filled'         => ':özellik alanının değeri olmalıdır.',
    'gt'             => [
        'numeric' => 'The :özellik daha büyük olmalıdır :değer.',
        'file'    => 'The :özellik daha büyük olmalıdır :değer kilobaytlar.',
        'string'  => 'The :özellik daha büyük olmalıdır :değer karakterler.',
        'array'   => 'The :özellik daha fazla olmalıdır :değer nesneler.',
    ],
    'gte' => [
        'numeric' => 'The :özellik daha büyük veya eşit olmalıdır :değer.',
        'file'    => 'The :özellik olmalıdır :değer kilobaytlar.',
        'string'  => 'The :özellik olmalıdır :değer karakterler.',
        'array'   => 'The :özellik must have :değer nesneler veya fazlası.',
    ],
    'image'    => ':özellik alanı resim dosyası olmalıdır.',
    'in'       => ':özellik değeri geçersiz.',
    'in_array' => ':özellik alanı :diğer içinde mevcut değil.',
    'integer'  => ':özellik tamsayı olmalıdır.',
    'ip'       => ':özellik geçerli bir IP adresi olmalıdır.',
    'ipv4'     => ':özellik geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'     => ':özellik geçerli bir IPv6 adresi olmalıdır.',
    'json'     => ':özellik geçerli bir JSON değişkeni olmalıdır.',
    'lt'       => [
        'numeric' => 'The :özellik daha az olmalıdır :değer.',
        'file'    => 'The :özellik daha az olmalıdır :değer kilobaytlar.',
        'string'  => 'The :özellik daha az olmalıdır :değer karakterler.',
        'array'   => 'The :özellik daha az olmalıdır :değer nesneler.',
    ],
    'lte' => [
        'numeric' => 'The :özellik daha az veya eşit olmalıdır :değer.',
        'file'    => 'The :özellik daha az veya eşit olmalıdır :değer kilobaytlar.',
        'string'  => 'The :özellik daha az veya eşit olmalıdır :değer karakterler.',
        'array'   => 'The :özellik daha fazla olmamalıdır :değer nesneler.',
    ],
    'max' => [
        'numeric' => ':özellik değeri :azami değerinden küçük olmalıdır.',
        'file'    => ':özellik değeri :azami kilobayt değerinden küçük olmalıdır.',
        'string'  => ':özellik değeri :azami karakter değerinden küçük olmalıdır.',
        'array'   => ':özellik değeri :azami adedinden az nesneye sahip olmalıdır.',
    ],
    'mimes'     => ':özellik dosya biçimi :değerler olmalıdır.',
    'mimetypes' => ':özellik dosya biçimi :değerler olmalıdır.',
    'min'       => [
        'numeric' => ':özellik değeri :asgari değerinden büyük olmalıdır.',
        'file'    => ':özellik değeri :asgari kilobayt değerinden büyük olmalıdır.',
        'string'  => ':özellik değeri :asgari karakter değerinden büyük olmalıdır.',
        'array'   => ':özellik en az :asgari nesnelere sahip olmalıdır.',
    ],
    'not_in'               => 'Seçili :özellik geçersiz.',
    'not_regex'            => ':özellik biçimi geçersiz.',
    'numeric'              => ':özellik sayı olmalıdır.',
    'present'              => ':özellik alanı mevcut olmalıdır.',
    'regex'                => ':özellik biçimi geçersiz.',
    'required'             => ':özellik alanı gereklidir.',
    'required_if'          => ':özellik alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless'      => ':özellik alanı, :other alanı :değerler değerlerinden birine sahip olmadığında zorunludur.',
    'required_with'        => ':özellik alanı :değerler varken zorunludur.',
    'required_with_all'    => ':özellik alanı herhangi bir :değerler değeri varken zorunludur.',
    'required_without'     => ':özellik alanı :değerler yokken zorunludur.',
    'required_without_all' => ':özellik alanı :değerler değerlerinden herhangi biri yokken zorunludur.',
    'same'                 => ':özellik ile :other eşleşmelidir.',
    'size'                 => [
        'numeric' => ':özellik :büyüklük olmalıdır.',
        'file'    => ':özellik :büyüklük kilobayt olmalıdır.',
        'string'  => ':özellik :büyüklük karakter olmalıdır.',
        'array'   => ':özellik :büyüklük nesneleri içermelidir.',
    ],
    'starts_with' => 'The :özellik devamındakilerden biriyle başlaması gereklidir : :değerler',
    'string'      => ':özellik dizge olmalıdır.',
    'timezone'    => ':özellik geçerli bir saat dilimi olmalıdır.',
    'unique'      => ':özellik daha önceden kayıt edilmiş.',
    'uploaded'    => ':özellik yüklemesi başarısız.',
    'url'         => ':özellik kaynak bulucu geçerli olmalıdır.',
    'uuid'        => 'The :özellik evrensel benzersiz tanımlayıcı geçerli olmalıdır.',

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

    'attributes' => [
    ],
];
