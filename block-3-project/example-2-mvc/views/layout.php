<?php
/** @var string $title @var string $content @var App\Services\Auth $auth @var App\Services\Cart $cart
 *  @var App\Models\Category[] $categories @var ?App\Http\Request $request */
$theme = theme();
$count = $cart->count();
$user  = $auth->user();
?>
<!DOCTYPE html>
<html lang="hy"<?= $theme ? ' data-theme="' . e($theme) . '"' : '' ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <meta name="description" content="5000+ ապրանք, անվճար առաքում Երևանում, 2 տարվա երաշխիք">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">   <!-- JS-ի համար — PHP Դաս 17 -->

    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/main.css">

    <!-- JS-ը միայն լավացնում է էջը։ Առանց դրա ամեն ինչ աշխատում է ֆորմաներով — PHP Դաս 17 -->
    <script type="module" src="/js/app.js"></script>
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
                    <li><a href="/"<?= ariaCurrent('/') ?>>Գլխավոր</a></li>
                    <li class="has-sub">
                        <a href="/catalog"<?= ariaCurrent('/catalog') ?>>Կատալոգ <span aria-hidden="true">▾</span></a>
                        <ul class="submenu">
                            <?php foreach ($categories as $c): ?>
                                <li><a href="<?= e($c->url()) ?>"<?= ariaCurrent($c->url()) ?>><?= e($c->name) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="/contact"<?= ariaCurrent('/contact') ?>>Կապ</a></li>

                    <?php if ($user === null): ?>
                        <li><a href="/login"<?= ariaCurrent('/login') ?>>Մուտք</a></li>
                    <?php else: ?>
                        <?php if ($user->isStaff()): ?>
                            <li><a href="/admin">Ադմին</a></li>
                        <?php endif; ?>
                        <li><a href="/account"<?= ariaCurrent('/account') ?>>Բարև, <?= e($user->name) ?></a></li>
                        <li>
                            <!-- Ելքը՝ POST, ոչ հղում — PHP Դաս 16 -->
                            <form method="post" action="/logout">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn--ghost btn--sm">Ելք</button>
                            </form>
                        </li>
                    <?php endif; ?>

                    <li>
                        <form method="post" action="/theme">
                            <?= csrf_field() ?>
                            <input type="hidden" name="theme" value="<?= $theme === 'dark' ? 'light' : 'dark' ?>">
                            <input type="hidden" name="back" value="<?= e($request?->uri() ?? '/') ?>">
                            <button type="submit" class="theme-toggle" aria-label="<?= $theme === 'dark' ? 'Լուսավոր թեմա' : 'Մուգ թեմա' ?>"><?= $theme === 'dark' ? '☀' : '☾' ?></button>
                        </form>
                    </li>
                    <li>
                        <a href="/cart" class="btn btn--primary btn--sm"<?= ariaCurrent('/cart') ?>>
                            Զամբյուղ <span class="count<?= $count === 0 ? ' is-empty' : '' ?>" data-cart-count><?= $count ?></span><span class="visually-hidden"> ապրանք</span>
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
                    <li><a href="/catalog">Կատալոգ</a></li>
                    <li><a href="/cart">Զամբյուղ</a></li>
                    <li><a href="/account">Իմ հաշիվը</a></li>
                    <li><a href="/api/products">API</a></li>
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
        <div class="col">PHP · սեփական MVC · Բլոկ 3</div>
    </div>
</footer>

<!-- Էկրանընթերցիչը կկարդա JS-ի հաղորդագրությունները — PHP Դաս 17 -->
<p id="cart-status" class="visually-hidden" role="status"></p>

</body>
</html>
