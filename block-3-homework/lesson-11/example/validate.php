<?php
declare(strict_types=1);

// «Առաջարկիր գիրք» ֆորմայի ստուգումը — PHP Դաս 06
// Վերադարձնում է սխալների զանգված։ Դատարկ զանգված = ամեն ինչ կարգին է

const GENRES = ['վեպ', 'պատմավեպ', 'պատմվածք', 'հեքիաթ', 'պոեզիա', 'ֆանտաստիկա'];

function validateSuggestion(array $data): array
{
    $errors = [];

    // --- Անուն ---
    $name = trim($data['name'] ?? '');
    if ($name === '') {
        $errors['name'] = 'Նշիր անունդ';
    } elseif (mb_strlen($name) < 2 || mb_strlen($name) > 60) {
        $errors['name'] = 'Անունը՝ 2-ից 60 նիշ';
    }

    // --- Էլ. հասցե ---
    $email = trim($data['email'] ?? '');
    if ($email === '') {
        $errors['email'] = 'Նշիր էլ. հասցեն';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Էլ. հասցեն վավեր չէ';
    }

    // --- Հեռախոս՝ ոչ պարտադիր, բայց եթե կա՝ ճիշտ ձևաչափով (տնային, կետ 5) ---
    $phone = trim($data['phone'] ?? '');
    if ($phone !== '' && !preg_match('/^\+374\s?\d{2}\s?\d{6}$/', $phone)) {
        $errors['phone'] = 'Հեռախոսը՝ +374 XX XXXXXX ձևաչափով';
    }

    // --- Գիրք ---
    $title = trim($data['title'] ?? '');
    if ($title === '') {
        $errors['title'] = 'Նշիր գրքի վերնագիրը';
    } elseif (mb_strlen($title) > 120) {
        $errors['title'] = 'Վերնագիրը՝ առավելագույնը 120 նիշ';
    }

    $author = trim($data['author'] ?? '');
    if (mb_strlen($author) > 80) {
        $errors['author'] = 'Հեղինակը՝ առավելագույնը 80 նիշ';     // ⭐ վերին սահման ամեն դաշտում (կետ 9)
    }

    // --- Ժանր՝ ցուցակից, խիստ համեմատությամբ (կետ 7) ---
    if (!in_array($data['genre'] ?? '', GENRES, true)) {
        $errors['genre'] = 'Ընտրիր ժանրը ցուցակից';
    }

    // --- Պատճառ ---
    $reason = trim($data['reason'] ?? '');
    $len = mb_strlen($reason);
    if ($len < 10) {
        $errors['reason'] = 'Գրիր առնվազն 10 նիշ՝ ինչու է արժե կարդալ';
    } elseif ($len > 1000) {
        $errors['reason'] = 'Առավելագույնը 1000 նիշ';
    }

    // --- Checkbox՝ նշված չլինելու դեպքում չի ուղարկվում ընդհանրապես (կետ 6) ---
    if (!isset($data['agree'])) {
        $errors['agree'] = 'Պետք է համաձայնել, որ Անին կապ հաստատի քեզ հետ';
    }

    return $errors;
}

// ---------- Ադմին՝ գրքի ֆորմա — PHP Դաս 11 ----------

/** $genreIds՝ բազայում եղած ժանրերի id-ները։ Ժանրը՝ ոչ պարտադիր */
function validateBook(array $data, array $genreIds): array
{
    $errors = [];

    $title = trim($data['title'] ?? '');
    if ($title === '') {
        $errors['title'] = 'Նշիր վերնագիրը';
    } elseif (mb_strlen($title) > 200) {
        $errors['title'] = 'Առավելագույնը 200 նիշ';
    }

    $author = trim($data['author'] ?? '');
    if ($author === '') {
        $errors['author'] = 'Նշիր հեղինակին';
    } elseif (mb_strlen($author) > 100) {
        $errors['author'] = 'Առավելագույնը 100 նիշ';
    }

    $genre = $data['genre_id'] ?? '';
    if ($genre !== '' && !in_array((int) $genre, $genreIds, true)) {
        $errors['genre_id'] = 'Ընտրիր ժանրը ցուցակից';
    }

    $year = trim($data['year'] ?? '');
    if ($year !== '' && filter_var($year, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => (int) date('Y')]]) === false) {
        $errors['year'] = 'Տարին՝ 1-ից ' . date('Y');
    }

    $pages = filter_var($data['pages'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5000]]);
    if ($pages === false) {
        $errors['pages'] = 'Էջերը՝ 1-ից 5000';
    }

    $read = filter_var($data['read_pages'] ?? '0', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($read === false) {
        $errors['read_pages'] = 'Կարդացածը՝ 0 կամ ավելի';
    } elseif ($pages !== false && $read > $pages) {
        $errors['read_pages'] = 'Չի կարող լինել ավելի, քան էջերը (' . $pages . ')';   // Դաս 04-ի «120%»-ը՝ այստեղ
    }

    $rating = filter_var($data['rating'] ?? '0', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 5]]);
    if ($rating === false) {
        $errors['rating'] = 'Գնահատականը՝ 0-ից 5';
    }

    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $data['color'] ?? '')) {
        $errors['color'] = 'Գույնը՝ #RRGGBB ձևաչափով';
    }

    $link = trim($data['link'] ?? '');
    if ($link !== '' && (filter_var($link, FILTER_VALIDATE_URL) === false || safeUrl($link, '') === '')) {
        $errors['link'] = 'Միայն http:// կամ https:// հղում';     // javascript:-ը չի պահվում բազայում
    }

    return $errors;
}

// ---------- «Կարդացի այսօր» — PHP Դաս 11 ----------

function validateReading(array $data, array $book): array
{
    $errors = [];
    $left = $book['pages'] - $book['read'];

    $pages = filter_var($data['pages'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($pages === false) {
        $errors['pages'] = 'Նշիր էջերի քանակը՝ 1 կամ ավելի';
    } elseif ($pages > $left) {
        $errors['pages'] = "Մնացել է ընդամենը {$left} էջ";
    }

    $date = $data['read_on'] ?? '';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date > date('Y-m-d')) {
        $errors['read_on'] = 'Ամսաթիվը՝ այսօր կամ ավելի վաղ';
    }

    if (mb_strlen(trim($data['note'] ?? '')) > 255) {
        $errors['note'] = 'Նշումը՝ առավելագույնը 255 նիշ';
    }

    return $errors;
}
