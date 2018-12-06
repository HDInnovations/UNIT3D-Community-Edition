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

    'accepted'   => 'Το :attribute πρέπει να γίνει αποδεκτό',
    'active_url' => 'Το :attribute δεν είναι έγκυρο URL',
    'after'      => 'Το :attribute πρέπει να είναι πριν τις :date.',
    'alpha'      => 'Το :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash' => 'Το :attribute μπορεί να περιέχει μόνο αριθμούς, γράμματα και τελείες.',
    'alpha_num'  => 'Το :attribute μπορεί να περιέχει μόνο αριθμούς και γράμματα.',
    'array'      => 'Το :attributeμπορεί να περιέχει μόνο ένα array.',
    'before'     => 'Το :attribute  πρέπει να είναι πριν τις :date.',
    'between'    => [
        'numeric' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max.',
        'file'    => 'Το :attribute πρέπει να είναι μεταξύ :min και :max kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι μεταξύ :min και :max χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να είναι μεταξύ :min και :max αντικείμενα.',
    ],
    'boolean'        => 'Το πεδίο :attribute πρέπει να είναι σωστό ή λάθος.',
    'confirmed'      => 'H :attribute επιβεβαίωση δεν ταιριάζει.',
    'date'           => 'Το :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_format'    => 'Το :attribute δεν ταιριάζει με τη μορφή :format.',
    'different'      => 'Το :attribute και :otherπρέπει να είναι διαφορετικά.',
    'digits'         => 'Το :attribute πρέπει να είναι  :digits ψηφία.',
    'digits_between' => 'Το :attribute πρέπει να είναι μεταξύ :min και  :max ψηφία.',
    'dimensions'     => 'Το :attribute έχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct'       => 'Το :attribute πεδίο έχει διπλή τιμή.',
    'email'          => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση e-mail.',
    'exists'         => 'Το επιλεγμένο :attribute είναι άκυρο',
    'file'           => 'Το :attribute πρέπει να είναι αρχείο.',
    'filled'         => 'Το :attribute πεδίο είναι υποχρεωτικό',
    'image'          => 'Το :attribute πρέπει να είναι εικόνα.',
    'in'             => 'Το πεδίο :attribute δεν είναι έγκυρο',
    'in_array'       => 'Το :attribute πεδίο δεν υπάρχει στο :other.',
    'integer'        => 'Το :attribute πρέπει να είναι ένας ακέραιος αριθμός.',
    'ip'             => 'Το :attributeπρέπει να είναι μια έγκυρη διεύθυνση IP.',
    'json'           => 'Το :attribute πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'max'            => [
        'numeric' => 'Το :attribute δεν πρέπει να είναι μεγαλύτερο από :max.',
        'file'    => 'Το :attribute δεν πρέπει να είναι μεγαλύτερο από :max kilobytes.',
        'string'  => 'Το :attribute δεν πρέπει να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array'   => 'Το :attribute δεν μπορεί να έχει περισσότερα απο :max αντικείμενα.',
    ],
    'mimes'     => 'Το :attribute πρέπει να είναι ένα αρχείο type: :values.',
    'mimetypes' => 'Το :attribute πρέπει να είναι ένα αρχείο  type: :values.',
    'min'       => [
        'numeric' => 'Το :attribute πρέπει να είναι τουλάχιστον :min.',
        'file'    => 'Το :attribute ρέπει να είναι τουλάχιστον :min kilobytes.',
        'string'  => 'Το :attribute ρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array'   => 'Το :attribute ρέπει να έχει τουλάχιστον :min αντικείμενα.',
    ],
    'not_in'               => 'Το επιλεγμένο :attribute is δεν είναι έγκυρο.',
    'numeric'              => 'Το :attribute πρέπει να είναι αριθμός.',
    'present'              => 'Το :attribute πρέπει να υπάρχει.',
    'regex'                => 'Το :attribute δεν είναι έγκυρο.',
    'required'             => 'Το :attribute είναι υποχρεωτικό.',
    'required_if'          => 'Το :attribute δεν είναι υποχρεωτικό όταν :other είναι :value.',
    'required_unless'      => 'Το :attribute δεν είναι υποχρεωτικό εκτός και άν :other είναι :values.',
    'required_with'        => 'Το :attribute είναι υποχρεωτικό όταν :values είναι παρών.',
    'required_with_all'    => 'Το :attribute είναι υποχρεωτικό όταν :values είναι παρών.',
    'required_without'     => 'Το :attribute είναι υποχρεωτικό όταν :values δεν είναι παρών.',
    'required_without_all' => 'Το :attribute είναι απαραίτητο όταν κανένα από τα :values είναι παρών.',
    'same'                 => 'Το :attribute και :other πρέπει να ταιριάζουν.',
    'size'                 => [
        'numeric' => 'Το :attribute πρέπει να είναι μέγεθος :size.',
        'file'    => 'Το :attribute πρέπει να έχει μέγεθος :size kilobytes.',
        'string'  => 'Το :attribute πρέπει να έχει μέγεθος :size χαρακτήρες.',
        'array'   => 'Το :attribute mπρέπει να περιέχει :size αντικείμενα.',
    ],
    'string'   => 'Το :attributeπρέπει να έχει μια συμβολοσειρά.',
    'timezone' => 'Το :attribute πρέπει να έχει μια έγκυρη ζώνη.',
    'unique'   => 'Το :attribute έχει ήδη ληφθεί.',
    'uploaded' => 'Δεν έγινε αποστολή του :attribute .',
    'url'      => 'Το :attribute δεν έχει έγκυρο format.',

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
