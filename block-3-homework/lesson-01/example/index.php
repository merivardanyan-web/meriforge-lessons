<?php
// Անիի գրքերը · PHP Դաս 01
// Բլոկ 2-ի pages/books.html-ը՝ վերանվանված index.php․ HTML-ը նույնն է, PHP-ն՝ միայն մի քանի տեղում

// Սխալների ցուցադրում — միայն զարգացման ժամանակ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$siteName = "Անի Սարգսյան";
$email    = "ani@example.com";
$city     = "Սևան";
$year     = date("Y");
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Գրքեր — <?= $siteName ?></title>
    <meta name="description" content="<?= $siteName ?>-ի ընթերցանության օրագիրը">

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
                        <p class="lead">Ինչ եմ կարդում <?= $city ?>-ում <?= $year ?> թվականին։ Հարցեր ունե՞ս՝ գրիր <a href="mailto:<?= $email ?>"><?= $email ?></a>։</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="shelf">
            <div class="row">
                <div class="col w-100">
                    <h2 class="section-title">Դարակ</h2>

                    <!-- Քարտերը դեռ ձեռքով են։ Դաս 02-ում դրանք կգան զանգվածից -->
                    <div class="grid-auto gap-20">
                        <article class="book is-done">
                            <div class="book__cover" style="--cover: #2f5d8a"><span>Փոքրիկ իշխանը</span></div>
                            <div class="book__body">
                                <h3 class="book__title">Փոքրիկ իշխանը</h3>
                                <p class="meta">Անտուան դը Սենտ-Էքզյուպերի · 1943</p>
                                <div class="progress"><span class="progress__bar" style="--progress: 100%"></span></div>
                                <p class="meta">96 / 96 էջ · 100%</p>
                                <span class="pill">Կարդացել եմ</span>
                            </div>
                        </article>

                        <article class="book is-reading">
                            <div class="book__cover" style="--cover: #8a3b2f"><span>Սամվել</span></div>
                            <div class="book__body">
                                <h3 class="book__title">Սամվել</h3>
                                <p class="meta">Րաֆֆի · 1886</p>
                                <div class="progress"><span class="progress__bar" style="--progress: 25%"></span></div>
                                <p class="meta">140 / 560 էջ · 25%</p>
                                <span class="pill">Կարդում եմ</span>
                            </div>
                        </article>

                        <article class="book is-planned">
                            <div class="book__cover" style="--cover: #3d3d55"><span>1984</span></div>
                            <div class="book__body">
                                <h3 class="book__title">1984</h3>
                                <p class="meta">Ջորջ Օրուել · 1949</p>
                                <div class="progress"><span class="progress__bar" style="--progress: 0%"></span></div>
                                <p class="meta">0 / 328 էջ · 0%</p>
                                <span class="pill">Կարդալու եմ</span>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="row">
            <div class="col w-100">
                <div class="wrapper footer__bottom flex justify-between align-center flex-wrap gap-10">
                    <p>&copy; <?= $year ?>&nbsp;<?= $siteName ?></p>
                    <address><a href="mailto:<?= $email ?>"><?= $email ?></a> · <?= $city ?>, Հայաստան</address>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
