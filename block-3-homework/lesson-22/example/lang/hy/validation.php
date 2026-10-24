<?php

// php artisan lang:publish → lang/en/validation.php-ի թարգմանությունը — PHP Դաս 21
// :attribute, :min, :max, :values... տեղապահները՝ անփոփոխ ⭐
// attributes()-ում անունները հոդով են («վերնագիրը»), այդ պատճառով այստեղ՝ առանց «-ը» և «դաշտը»
// :Attribute (մեծատառով) = նույն անունը, առաջին տառը մեծատառ՝ «Վերնագիրը պարտադիր է»
// Այստեղ՝ միայն այն կանոնները, որ օգտագործում ենք։ Մնացածը կվերցվի lang/en-ից (APP_FALLBACK_LOCALE=en)

return [
    'accepted'  => ':Attribute պետք է ընդունել։',
    'between'   => [
        'numeric' => ':Attribute պիտի լինի :min-ից :max։',
        'string'  => ':Attribute պիտի լինի :min-ից :max նիշ։',
    ],
    'boolean'   => ':Attribute պիտի լինի այո կամ ոչ։',
    'confirmed' => ':Attribute և դրա կրկնությունը չեն համընկնում։',
    'date'      => ':Attribute պիտի լինի ամսաթիվ։',
    'before_or_equal' => ':Attribute պիտի լինի :date կամ ավելի վաղ։',
    'email'     => ':Attribute պիտի լինի վավեր էլ. հասցե։',
    'exists'    => 'Ընտրված :attribute գոյություն չունի։',
    'image'     => ':Attribute պիտի լինի նկար։',
    'in'        => 'Ընտրված :attribute վավեր չէ։',
    'integer'   => ':Attribute պիտի լինի ամբողջ թիվ։',
    'max'       => [
        'file'    => ':Attribute չպիտի գերազանցի :max ԿԲ-ը։',
        'numeric' => ':Attribute չպիտի գերազանցի :max-ը։',
        'string'  => ':Attribute չպիտի գերազանցի :max նիշը։',
    ],
    'mimes'     => ':Attribute պիտի լինի հետևյալ տիպերից՝ :values։',
    'min'       => [
        'numeric' => ':Attribute պիտի լինի առնվազն :min։',
        'string'  => ':Attribute պիտի լինի առնվազն :min նիշ։',
    ],
    'regex'     => ':Attribute ունի սխալ ձևաչափ։',
    'required'  => ':Attribute պարտադիր է։',
    'required_if' => ':Attribute պարտադիր է, երբ :other «:value» է։',
    'url'       => ':Attribute պիտի լինի վավեր հղում։',
    'lte'       => [
        'numeric' => ':Attribute չպիտի գերազանցի :value-ը։',
    ],
    'string'    => ':Attribute պիտի լինի տեքստ։',
    'unique'    => ':Attribute արդեն զբաղված է։',
    'uploaded'  => ':Attribute չհաջողվեց վերբեռնել։',

    'password' => [
        'letters' => ':Attribute պիտի պարունակի առնվազն մեկ տառ։',
        'mixed'   => ':Attribute պիտի պարունակի մեծատառ և փոքրատառ։',
        'numbers' => ':Attribute պիտի պարունակի առնվազն մեկ թիվ։',
        'symbols' => ':Attribute պիտի պարունակի առնվազն մեկ նշան։',
    ],

    // :value-ի հայերեն տեսքը՝ «Տևողությունը պարտադիր է, երբ տեսակը «աուդիո» է»
    'values' => [
        'format' => ['paper' => 'թղթային', 'audio' => 'աուդիո', 'ebook' => 'էլեկտրոնային'],
    ],

    // Fortify-ի դաշտերը, որ Form Request չունեն
    'attributes' => [
        'name'     => 'անունը',
        'email'    => 'էլ. հասցեն',
        'password' => 'գաղտնաբառը',
    ],
];
