<?php

// lang/hy/validation.php-ի ձևաչափը — PHP Դաս 21
// php artisan lang:publish, ապա պատճենիր lang/en → lang/hy և թարգմանիր (AI-ով, ապա ստուգիր)
// ⭐ :attribute, :min, :max, :values տեղապահները՝ անփոփոխ

return [
    'required' => 'Խնդրում ենք լրացնել :attribute դաշտը։',
    'email'    => ':attribute պիտի լինի վավեր էլ. հասցե։',
    'exists'   => 'Ընտրված :attribute գոյություն չունի։',
    'between'  => [
        'numeric' => ':attribute պիտի լինի :min-ից :max։',
    ],
    'max' => [
        'string'  => ':attribute-ը չպիտի գերազանցի :max նիշը։',
        'numeric' => ':attribute-ը չպիտի գերազանցի :max-ը։',
        'file'    => ':attribute-ը չպիտի գերազանցի :max ԿԲ-ը։',
    ],
    // ... մնացածը՝ lang/en/validation.php-ի հերթականությամբ
];
