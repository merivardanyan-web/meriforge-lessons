<?php
// Անիի գրքերը · PHP Դաս 02 — քարտերը զանգվածից
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'data.php';

$year = date("Y");
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Գրքեր — <?= $siteName ?></title>
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

    <a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

    <header class="header">
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <div class="row align-center justify-between">
            <div class="col">
                <a href="index.php" class="logo"><?= $siteName ?></a>
            </div>
            <div class="col">
                <label for="nav-toggle" class="burger">
                    <span></span><span></span><span></span>
                    <span class="visually-hidden">Մենյու</span>
                </label>
                <nav aria-label="Գլխավոր մենյու">
                    <ul class="menu flex align-center gap-10">
                        <li><a href="index.php" aria-current="page">Գրքեր</a></li>
                        <li><a href="mailto:<?= $email ?>">Գրել Անիին</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main id="main">
        <section class="page-head">
            <div class="row">
                <div class="col w-100">
                    <div class="wrapper">
                        <h1>Իմ գրադարանը</h1>
                        <p class="lead">Ինչ եմ կարդում <?= $city ?>-ում և ինչ եմ արդեն կարդացել</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="shelf">
            <div class="row">
                <div class="col w-100">
                    <h2 class="section-title">Դարակ</h2>

                    <?php if (empty($books)): ?>

                        <p class="empty">Դարակը դեռ դատարկ է։</p>

                    <?php else: ?>

                    <div class="grid-auto gap-20">
                    <?php foreach ($books as $book): ?>
                        <?php
                        // Կարգավիճակը՝ կարդացած էջերից
                        if ($book['read'] === 0) {
                            $status = 'planned';
                        } elseif ($book['read'] >= $book['pages']) {
                            $status = 'done';
                        } else {
                            $status = 'reading';
                        }

                        // match՝ կարճ, վերադարձնում է արժեք, համեմատում է === -ով
                        $label = match ($status) {
                            'done'    => 'Կարդացել եմ',
                            'reading' => 'Կարդում եմ',
                            'planned' => 'Կարդալու եմ',
                        };

                        // (int)՝ առանց ստորակետի․ 140 / 560 → 25
                        $percent = (int) ($book['read'] * 100 / $book['pages']);
                        ?>

                        <article class="book is-<?= $status ?>">
                            <div class="book__cover" style="--cover: <?= $book['color'] ?>"><span><?= $book['title'] ?></span></div>
                            <div class="book__body">
                                <h3 class="book__title"><?= $book['title'] ?></h3>
                                <p class="meta"><?= $book['author'] ?> · <?= $book['year'] ?? 'տարին անհայտ' ?></p>
                                <div class="progress"><span class="progress__bar" style="--progress: <?= $percent ?>%"></span></div>
                                <p class="meta"><?= $book['read'] ?> / <?= number_format($book['pages'], 0, '.', ' ') ?> էջ · <?= $percent ?>%</p>
                                <span class="pill"><?= $label ?></span>

                                <?php if ($book['rating'] > 0): ?>
                                    <p class="meta" aria-label="Գնահատական՝ <?= $book['rating'] ?> 5-ից">
                                        <?php for ($i = 1; $i <= $book['rating']; $i++): ?>★<?php endfor; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </article>

                    <?php endforeach; ?>
                    </div>

                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="row">
            <div class="col w-100">
                <div class="wrapper footer__bottom flex justify-between align-center flex-wrap gap-10">
                    <p>&copy; <?= $year ?>&nbsp;<?= $siteName ?></p>
                    <address><a href="mailto:<?= $email ?>"><?= $email ?></a> · <a href="tel:<?= str_replace(' ', '', $phone) ?>"><?= $phone ?></a> · <?= $city ?></address>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
