<?php
declare(strict_types=1);

// Վալիդացիա՝ միայն սերվերում վստահելի — PHP Դաս 6, 11
// Ամեն ֆունկցիա վերադարձնում է սխալների զանգված։ [] = ամեն ինչ կարգին է

const CONTACT_TOPICS = [
    'order'   => 'Պատվեր',
    'support' => 'Տեխնիկական աջակցություն',
    'other'   => 'Այլ',
];

const DELIVERY = [
    'courier' => ['Առաքում Երևանում', 'Վաղը · անվճար 50 000 ֏-ից'],
    'pickup'  => ['Վերցնել խանութից', 'Այսօր՝ 18:00-ից · Աբովյան 1'],
    'region'  => ['Առաքում մարզեր', '2–3 օր · 2 000 ֏'],
];

const PAYMENT = [
    'cash' => ['Կանխիկ', 'Առաքիչին կամ խանութում'],
    'card' => ['Քարտով', 'Առաքիչի տերմինալով'],
];

const REGIONS = ['Երևան', 'Արագածոտն', 'Արարատ', 'Արմավիր', 'Գեղարքունիք', 'Կոտայք',
                 'Լոռի', 'Շիրակ', 'Սյունիք', 'Տավուշ', 'Վայոց ձոր'];

/** Տեքստային դաշտի սխալը կամ null — կրկնվող ստուգումը մեկ տեղում */
function textError(string $value, int $min, int $max, string $label): ?string
{
    $len = mb_strlen($value);

    if ($min > 0 && $value === '') {
        return "Խնդրում ենք լրացնել {$label} դաշտը";
    }
    if ($len < $min) {
        return "{$label} դաշտը՝ առնվազն {$min} նիշ";
    }
    if ($len > $max) {
        return "{$label} դաշտը՝ առավելագույնը {$max} նիշ";    // ⭐ վերին սահման ամեն դաշտում — PHP Դաս 6
    }

    return null;
}

/** +374 XX XXXXXX կամ 0XX XXXXXX, բացատներով կամ գծիկներով — PHP Դաս 6-ի տնային */
function validatePhone(string $phone): bool
{
    $digits = preg_replace('/[\s\-()]/', '', $phone);

    return preg_match('/^(\+374|0)\d{8}$/', $digits) === 1;
}

// ---------- Կապ — PHP Դաս 6 ----------

function validateContact(array $data): array
{
    $errors = [];

    if ($error = textError(trim($data['name'] ?? ''), 2, 60, '«Անուն»')) {
        $errors['name'] = $error;
    }

    $email = trim($data['email'] ?? '');
    if ($email === '') {
        $errors['email'] = 'Խնդրում ենք նշել էլ. հասցեն';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 190) {
        $errors['email'] = 'Էլ. հասցեն վավեր չէ';
    }

    // ⭐ Երրորդ արգումենտը՝ true, խիստ համեմատություն — PHP Դաս 6
    if (!in_array($data['topic'] ?? '', array_keys(CONTACT_TOPICS), true)) {
        $errors['topic'] = 'Ընտրեք թեման';
    }

    if ($error = textError(trim($data['message'] ?? ''), 10, 2000, '«Հաղորդագրություն»')) {
        $errors['message'] = $error;
    }

    return $errors;
}

// ---------- Պատվեր ----------

function validateCheckout(array $data): array
{
    $errors = [];

    if ($error = textError(trim($data['name'] ?? ''), 2, 100, '«Անուն, ազգանուն»')) {
        $errors['name'] = $error;
    }

    $phone = trim($data['phone'] ?? '');
    if ($phone === '') {
        $errors['phone'] = 'Խնդրում ենք նշել հեռախոսը';
    } elseif (!validatePhone($phone)) {
        $errors['phone'] = 'Հեռախոսը՝ +374 XX XX XX XX ձևաչափով';
    }

    $email = trim($data['email'] ?? '');       // ոչ պարտադիր, բայց եթե կա՝ վավեր
    if ($email !== '' && (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 190)) {
        $errors['email'] = 'Էլ. հասցեն վավեր չէ';
    }

    $delivery = $data['delivery'] ?? '';
    if (!array_key_exists($delivery, DELIVERY)) {
        $errors['delivery'] = 'Ընտրեք առաքման եղանակը';
    }

    if ($delivery !== 'pickup') {
        if (!in_array($data['region'] ?? '', REGIONS, true)) {
            $errors['region'] = 'Ընտրեք մարզը';
        }
        if ($error = textError(trim($data['address'] ?? ''), 5, 255, '«Հասցե»')) {
            $errors['address'] = $error;
        }
    }

    if (!array_key_exists($data['payment'] ?? '', PAYMENT)) {
        $errors['payment'] = 'Ընտրեք վճարման եղանակը';
    }

    if ($error = textError(trim($data['comment'] ?? ''), 0, 500, '«Մեկնաբանություն»')) {
        $errors['comment'] = $error;
    }

    // Checkbox-ը չի ուղարկվում, երբ նշված չէ — PHP Դաս 6-ի տնային
    if (!isset($data['agree'])) {
        $errors['agree'] = 'Պետք է համաձայնել պայմաններին';
    }

    return $errors;
}

// ---------- Ապրանք ադմինում — PHP Դաս 11 ----------

function validateProduct(array $data): array
{
    $errors = [];

    if ($error = textError(trim($data['name'] ?? ''), 1, 200, '«Անվանում»')) {
        $errors['name'] = $error;
    }
    if ($error = textError(trim($data['specs'] ?? ''), 0, 255, '«Համառոտ»')) {
        $errors['specs'] = $error;
    }
    if ($error = textError(trim($data['description'] ?? ''), 0, 5000, '«Նկարագրություն»')) {
        $errors['description'] = $error;
    }

    $price = filter_var($data['price'] ?? '', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 99999999],
    ]);
    if ($price === false) {
        $errors['price'] = 'Գինը՝ դրական ամբողջ թիվ';
    }

    $discount = filter_var($data['discount'] ?? '0', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => 90],
    ]);
    if ($discount === false) {           // ⭐ === false, ոչ !, որովհետև 0-ն վավեր է
        $errors['discount'] = 'Զեղչը՝ 0-ից 90';
    }

    $stock = filter_var($data['stock'] ?? '0', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => 100000],
    ]);
    if ($stock === false) {
        $errors['stock'] = 'Քանակը՝ 0-ից 100 000';
    }

    // Կատեգորիան՝ իրապես գոյություն ունեցողներից ⭐
    $catId = $data['category_id'] ?? '';
    if ($catId !== '') {
        $ids = array_map('intval', array_column(getCategories(), 'id'));
        if (!in_array((int) $catId, $ids, true)) {
            $errors['category_id'] = 'Անհայտ կատեգորիա';
        }
    }

    return $errors;
}
