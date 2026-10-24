<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? SITE_NAME) ?></title>
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
                <a href="index.php" class="logo"><?= e(SITE_NAME) ?></a>
            </div>
            <div class="col">
                <label for="nav-toggle" class="burger">
                    <span></span><span></span><span></span>
                    <span class="visually-hidden">Մենյու</span>
                </label>
                <nav aria-label="Գլխավոր մենյու">
                    <ul class="menu flex align-center gap-10">
                        <li><a href="index.php"<?= ($active ?? '') === 'books' ? ' aria-current="page"' : '' ?>>Գրքեր</a></li>
                        <li><a href="about.php"<?= ($active ?? '') === 'about' ? ' aria-current="page"' : '' ?>>Ակումբի մասին</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main id="main">
        <?= $content ?>          <!-- ⭐ ամեն էջի բովանդակությունը -->
    </main>

    <footer class="footer">
        <div class="row">
            <div class="col w-100">
                <div class="wrapper footer__bottom flex justify-between align-center flex-wrap gap-10">
                    <p>&copy; <?= date('Y') ?>&nbsp;<?= e(SITE_NAME) ?></p>
                    <address><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a> · <?= e(SITE_CITY) ?>, Հայաստան</address>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
