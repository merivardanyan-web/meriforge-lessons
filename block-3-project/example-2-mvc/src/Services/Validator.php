<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryRepository;

/**
 * Առաջադրանք 1-ի validate.php-ն՝ դասով — PHP Դաս 14-ի տնային
 * Ամեն մեթոդ վերադարձնում է սխալների զանգված։ [] = ամեն ինչ կարգին է
 */
class Validator
{
    public const CONTACT_TOPICS = [
        'order'   => 'Պատվեր',
        'support' => 'Տեխնիկական աջակցություն',
        'other'   => 'Այլ',
    ];

    public const DELIVERY = [
        'courier' => ['Առաքում Երևանում', 'Վաղը · անվճար 50 000 ֏-ից'],
        'pickup'  => ['Վերցնել խանութից', 'Այսօր՝ 18:00-ից · Աբովյան 1'],
        'region'  => ['Առաքում մարզեր', '2–3 օր · 1 000–3 000 ֏՝ ըստ քաշի'],
    ];

    public const PAYMENT = [
        'cash' => ['Կանխիկ', 'Առաքիչին կամ խանութում'],
        'card' => ['Քարտով', 'Առաքիչի տերմինալով'],
    ];

    public const REGIONS = [
        'Երևան', 'Արագածոտն', 'Արարատ', 'Արմավիր', 'Գեղարքունիք', 'Կոտայք',
        'Լոռի', 'Շիրակ', 'Սյունիք', 'Տավուշ', 'Վայոց ձոր',
    ];

    /** Տեքստային դաշտի սխալը կամ null — կրկնվող ստուգումը մեկ տեղում */
    private static function textError(string $value, int $min, int $max, string $label): ?string
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
    public static function phone(string $phone): bool
    {
        $digits = preg_replace('/[\s\-()]/', '', $phone);

        return preg_match('/^(\+374|0)\d{8}$/', $digits) === 1;
    }

    // ---------- Կապ — PHP Դաս 6 ----------

    public static function contact(array $data): array
    {
        $errors = [];

        if ($error = self::textError(trim($data['name'] ?? ''), 2, 60, '«Անուն»')) {
            $errors['name'] = $error;
        }

        $email = trim($data['email'] ?? '');
        if ($email === '') {
            $errors['email'] = 'Խնդրում ենք նշել էլ. հասցեն';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 190) {
            $errors['email'] = 'Էլ. հասցեն վավեր չէ';
        }

        // ⭐ Երրորդ արգումենտը՝ true, խիստ համեմատություն — PHP Դաս 6
        if (!in_array($data['topic'] ?? '', array_keys(self::CONTACT_TOPICS), true)) {
            $errors['topic'] = 'Ընտրեք թեման';
        }

        if ($error = self::textError(trim($data['message'] ?? ''), 10, 2000, '«Հաղորդագրություն»')) {
            $errors['message'] = $error;
        }

        return $errors;
    }

    // ---------- Պատվեր ----------

    public static function checkout(array $data): array
    {
        $errors = [];

        if ($error = self::textError(trim($data['name'] ?? ''), 2, 100, '«Անուն, ազգանուն»')) {
            $errors['name'] = $error;
        }

        $phone = trim($data['phone'] ?? '');
        if ($phone === '') {
            $errors['phone'] = 'Խնդրում ենք նշել հեռախոսը';
        } elseif (!self::phone($phone)) {
            $errors['phone'] = 'Հեռախոսը՝ +374 XX XX XX XX ձևաչափով';
        }

        $email = trim($data['email'] ?? '');       // ոչ պարտադիր, բայց եթե կա՝ վավեր
        if ($email !== '' && (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 190)) {
            $errors['email'] = 'Էլ. հասցեն վավեր չէ';
        }

        $delivery = $data['delivery'] ?? '';
        if (!array_key_exists($delivery, self::DELIVERY)) {
            $errors['delivery'] = 'Ընտրեք առաքման եղանակը';
        }

        if ($delivery !== 'pickup') {
            if (!in_array($data['region'] ?? '', self::REGIONS, true)) {
                $errors['region'] = 'Ընտրեք մարզը';
            }
            if ($error = self::textError(trim($data['address'] ?? ''), 5, 255, '«Հասցե»')) {
                $errors['address'] = $error;
            }
        }

        if (!array_key_exists($data['payment'] ?? '', self::PAYMENT)) {
            $errors['payment'] = 'Ընտրեք վճարման եղանակը';
        }

        if ($error = self::textError(trim($data['comment'] ?? ''), 0, 500, '«Մեկնաբանություն»')) {
            $errors['comment'] = $error;
        }

        // Checkbox-ը չի ուղարկվում, երբ նշված չէ — PHP Դաս 6-ի տնային
        if (!isset($data['agree'])) {
            $errors['agree'] = 'Պետք է համաձայնել պայմաններին';
        }

        return $errors;
    }

    // ---------- Ապրանք ադմինում — PHP Դաս 11 ----------

    public static function product(array $data, CategoryRepository $categories): array
    {
        $errors = [];

        if ($error = self::textError(trim($data['name'] ?? ''), 1, 200, '«Անվանում»')) {
            $errors['name'] = $error;
        }
        if ($error = self::textError(trim($data['specs'] ?? ''), 0, 255, '«Համառոտ»')) {
            $errors['specs'] = $error;
        }
        if ($error = self::textError(trim($data['description'] ?? ''), 0, 5000, '«Նկարագրություն»')) {
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

        if (!in_array($data['type'] ?? '', ['physical', 'digital'], true)) {
            $errors['type'] = 'Ընտրեք տեսակը';
        }

        $weight = filter_var($data['weight'] ?? '0', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100000],
        ]);
        if ($weight === false) {
            $errors['weight'] = 'Քաշը՝ 0-ից 100 000 գրամ';
        }

        // Կատեգորիան՝ իրապես գոյություն ունեցողներից ⭐
        $catId = $data['category_id'] ?? '';
        if ($catId !== '') {
            if (!ctype_digit((string) $catId) || !$categories->exists((int) $catId)) {
                $errors['category_id'] = 'Անհայտ կատեգորիա';
            }
        }

        return $errors;
    }

    // ---------- Գրանցում — PHP Դաս 16 ----------

    public static function register(array $data, string $password, string $confirm): array
    {
        $errors = [];

        $len = mb_strlen($data['name']);
        if ($len < 2 || $len > 100) {
            $errors['name'] = 'Անունը՝ 2-ից 100 նիշ';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 190) {
            $errors['email'] = 'Ստուգիր էլ. հասցեն';
        }

        if (mb_strlen($password) < 8) {
            $errors['password'] = 'Գաղտնաբառը՝ առնվազն 8 նիշ';
        } elseif (mb_strlen($password) > 200) {
            $errors['password'] = 'Գաղտնաբառը՝ առավելագույնը 200 նիշ';
        } elseif ($password !== $confirm) {
            $errors['password_confirmation'] = 'Գաղտնաբառերը չեն համընկնում';
        }

        return $errors;
    }
}
