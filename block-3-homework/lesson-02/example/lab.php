<?php
// Դաս 02-ի փորձերը մեկ էջում — տնային կետ 2–5, 8
// Արդյունքները՝ PHP-ից, ոչ ձեռքով գրված։ Փոխիր արժեքները և տես, ինչ է փոխվում
ini_set('display_errors', 1);
error_reporting(E_ALL);

// [ինչպես ցույց տալ, ձախ արժեք, աջ արժեք]
$pairs = [
    ['"25" և 25',     '25',  25],
    ['"1" և "01"',    '1',   '01'],
    ['"10" և "1e1"',  '10',  '1e1'],
    ['0 և ""',        0,     ''],
    ['null և false',  null,  false],
    ['"abc" և 0',     'abc', 0],
];

// [անուն, արժեք]
$values = [
    ['""', ''],
    ['"0"', '0'],
    ['0', 0],
    ['[]', []],
    ['null', null],
    ['"Սամվել"', 'Սամվել'],
];

$word = 'գրադարան';
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <title>Դաս 02 · փորձեր</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<main id="main">
<section>
<div class="row"><div class="col w-100">

    <h1>Դաս 02 · փորձեր</h1>

    <h2>1. == և ===</h2>
    <table class="table">
        <tr><th>Համեմատում ենք</th><th>==</th><th>===</th></tr>
        <?php foreach ($pairs as $pair): ?>
            <tr>
                <td><code><?= $pair[0] ?></code></td>
                <td><?= $pair[1] == $pair[2] ? 'true' : 'false' ?></td>
                <td><?= $pair[1] === $pair[2] ? 'true' : 'false' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>2. «0e» տողերը</h2>
    <p><code>"0e123" == "0e456"</code> → <strong><?= "0e123" == "0e456" ? 'true' : 'false' ?></strong>,
       իսկ <code>===</code>-ով՝ <strong><?= "0e123" === "0e456" ? 'true' : 'false' ?></strong>։
       Երկուսն էլ նման են թվի (0 × 10<sup>123</sup>), ուստի <code>==</code>-ն համեմատում է դրանք որպես 0 և 0։</p>

    <h2>3. strlen և mb_strlen</h2>
    <p><code>strlen("<?= $word ?>")</code> = <?= strlen($word) ?> (բայթեր),
       <code>mb_strlen("<?= $word ?>")</code> = <?= mb_strlen($word) ?> (տառեր)</p>

    <h2>4. isset, empty, is_null</h2>
    <table class="table">
        <tr><th>Արժեք</th><th>isset</th><th>empty</th><th>is_null</th></tr>
        <?php foreach ($values as $row): ?>
            <tr>
                <td><code><?= $row[0] ?></code></td>
                <td><?= isset($row[1]) ? 'true' : 'false' ?></td>
                <td><?= empty($row[1]) ? 'true' : 'false' ?></td>
                <td><?= is_null($row[1]) ? 'true' : 'false' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>5. foreach-ի հղումային թակարդը</h2>
    <?php
    $pages = [96, 560, 40];

    foreach ($pages as &$p) {
        $p = $p + 0;           // «ոչինչ չենք փոխում»
    }
    // unset($p);              ← առանց սրա $p-ն դեռ հղում է վերջին տարրին

    foreach ($pages as $p) {
        // ամեն քայլում $p = ... գրում է ՎԵՐՋԻՆ տարրի մեջ
    }
    ?>
    <p>Սպասում էինք <code>96, 560, 40</code>, ստացանք <code><?= implode(', ', $pages) ?></code>։</p>

</div></div>
</section>
</main>
</body>
</html>
