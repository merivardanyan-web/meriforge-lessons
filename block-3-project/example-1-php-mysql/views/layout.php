<?php
/** @var string $title  @var string $content  @var string $active */
$theme = theme();
$count = cartCount();
$here  = $_SERVER['REQUEST_URI'] ?? '/';
$current = fn(string $key): string => ($active ?? '') === $key ? ' aria-current="page"' : '';
?>
<!DOCTYPE html>
<html lang="hy"<?= $theme ? ' data-theme="' . e($theme) . '"' : '' ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? SITE_NAME) ?></title>
    <meta name="description" content="5000+ ապրանք, անվճար առաքում Երևանում, 2 տարվա երաշխիք">

    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>

<a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

<header class="header">
    <input type="checkbox" id="nav" class="nav-toggle">

    <div class="row align-center justify-between">
        <div class="col">
            <a href="/" class="logo"><?= e(SITE_NAME) ?></a>
        </div>

        <div class="col">
            <label for="nav" class="burger">
                <span></span><span></span><span></span>
                <span class="visually-hidden">Մենյու</span>
            </label>

            <nav aria-label="Գլխավոր մենյու">
                <ul class="menu flex align-center gap-10">
                    <li><a href="/"<?= $current('home') ?>>Գլխավոր</a></li>
                    <li class="has-sub">
                        <a href="/catalog.php"<?= $current('catalog') ?>>Կատալոգ <span aria-hidden="true">▾</span></a>
                        <ul class="submenu">
                            <?php /* ⚠️ Շաբլոնը կանչում է ֆունկցիա, որ գնում է բազա։ Laravel-ում (Դաս 19) սա կանի View::composer-ը */ ?>
                            <?php foreach (getCategories() as $c): ?>
                                <li><a href="<?= e(urlWith('/catalog.php', ['category' => $c['slug']])) ?>"><?= e($c['name']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="/contact.php"<?= $current('contact') ?>>Կապ</a></li>
                    <li>
                        <!-- Թեման՝ cookie-ով, առանց JS-ի — PHP Դաս 7 -->
                        <form method="post" action="/theme.php">
                            <input type="hidden" name="theme" value="<?= $theme === 'dark' ? 'light' : 'dark' ?>">
                            <input type="hidden" name="back" value="<?= e($here) ?>">
                            <button type="submit" class="theme-toggle" aria-label="<?= $theme === 'dark' ? 'Լուսավոր թեմա' : 'Մուգ թեմա' ?>"><?= $theme === 'dark' ? '☀' : '☾' ?></button>
                        </form>
                    </li>
                    <li>
                        <a href="/cart.php" class="btn btn--primary btn--sm"<?= $current('cart') ?>>
                            Զամբյուղ <span class="count<?= $count === 0 ? ' is-empty' : '' ?>"><?= $count ?></span><span class="visually-hidden"> ապրանք</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main id="main">
    <?php if ($msg = flash('success')): ?>
        <div class="row"><div class="col w-100">
            <div class="alert alert--success mt-20" role="status"><?= e($msg) ?></div>
        </div></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="row"><div class="col w-100">
            <div class="alert alert--danger mt-20" role="alert"><?= e($msg) ?></div>
        </div></div>
    <?php endif; ?>

    <?= $content ?>
</main>

<footer class="footer">
    <div class="row flex-wrap">
        <div class="col w-40 w-md-100">
            <p class="logo mb-10"><a href="/"><?= e(SITE_NAME) ?></a></p>
            <p>Տեխնիկայի խանութ Երևանում 2018 թվականից։ Ուսումնական նախագիծ։</p>
        </div>

        <div class="col w-25 w-md-50 w-sm-100">
            <h2 class="footer__title">Բաժիններ</h2>
            <nav aria-label="Ստորին մենյու">
                <ul>
                    <li><a href="/catalog.php">Կատալոգ</a></li>
                    <li><a href="/cart.php">Զամբյուղ</a></li>
                    <li><a href="/checkout.php">Պատվերի ձևակերպում</a></li>
                    <li><a href="/contact.php">Կապ</a></li>
                </ul>
            </nav>
        </div>

        <div class="col w-25 w-md-50 w-sm-100" id="contact">
            <h2 class="footer__title">Կապ</h2>
            <ul>
                <li>Երևան, Աբովյան 1</li>
                <li><a href="tel:+37410000000">+374 10 00 00 00</a></li>
                <li><a href="mailto:info@technomarket.example">info@technomarket.example</a></li>
            </ul>
        </div>
    </div>

    <div class="row flex-wrap justify-between footer__bottom">
        <div class="col">© <?= date('Y') ?> <?= e(SITE_NAME) ?></div>
        <div class="col">PHP + MySQL · Բլոկ 3</div>
    </div>
</footer>

</body>
</html>
