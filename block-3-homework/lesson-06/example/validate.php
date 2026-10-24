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
