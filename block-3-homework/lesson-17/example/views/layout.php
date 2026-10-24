<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? SITE_NAME) ?></title>
    <link rel="icon" href="/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/main.css">
    <!-- token-ը՝ JS-ի համար (fetch → X-CSRF-Token) — Դաս 17 -->
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <script type="module" src="/js/main.js"></script>
</head>
<body<?= isDark() ? ' class="dark"' : '' ?>>

    <a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

    <header class="header">
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <div class="row align-center justify-between">
            <div class="col">
                <a href="/" class="logo"><?= e(SITE_NAME) ?></a>
            </div>
            <div class="col">
                <label for="nav-toggle" class="burger">
                    <span></span><span></span><span></span>
                    <span class="visually-hidden">Մենյու</span>
                </label>
                <nav aria-label="Գլխավոր մենյու">
                    <ul class="menu flex align-center gap-10">
                        <li><a href="/"<?= ariaCurrent('/') ?>>Գրքեր</a></li>
                        <li><a href="/about"<?= ariaCurrent('/about') ?>>Ակումբի մասին</a></li>
                        <li>
                            <a href="/list"<?= ariaCurrent('/list') ?>>Կարդալու ցուցակ<span class="pill<?= readingList()->count() === 0 ? ' hidden' : '' ?>" data-list-count><?= readingList()->count() ?></span></a>
                        </li>
                        <li>
                            <!-- Թեման՝ cookie-ով, առանց JS-ի (Դաս 07-ի տնային, կետ 7) -->
                            <form method="post" action="/theme">
                                <?= csrf_field() ?>
                                <input type="hidden" name="back" value="<?= e($_SERVER['REQUEST_URI'] ?? '/') ?>">
                                <button type="submit" class="theme-toggle" aria-label="<?= isDark() ? 'Լուսավոր թեմա' : 'Մուգ թեմա' ?>"><?= isDark() ? '☀' : '☾' ?></button>
                            </form>
                        </li>
                        <li><a href="/suggest" class="btn btn--primary btn--sm">Առաջարկիր գիրք</a></li>
                        <?php if (auth()->check()): ?>
                            <!-- Դաս 16, տնային 9 -->
                            <li class="hello">Բարև, <?= e(auth()->user()->name) ?></li>
                            <li>
                                <!-- Ելքը՝ POST + CSRF․ GET հղումը <img src="/logout">-ով կհաներ ցանկացածին -->
                                <form method="post" action="/logout">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn--ghost btn--sm">Ելք</button>
                                </form>
                            </li>
                        <?php else: ?>
                            <li><a href="/login"<?= ariaCurrent('/login') ?>>Մուտք</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main id="main">
        <?php if ($msg = flash('success')): ?>
            <div class="row"><div class="col w-100"><div class="alert alert--success mt-20" role="status"><?= e($msg) ?></div></div></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="row"><div class="col w-100"><div class="alert alert--danger mt-20" role="alert"><?= e($msg) ?></div></div></div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="row">
            <div class="col w-100">
                <div class="wrapper footer__bottom flex justify-between align-center flex-wrap gap-10">
                    <p>&copy; <?= date('Y') ?>&nbsp;<?= e(SITE_NAME) ?></p>
                    <address><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a> · <?= e(SITE_CITY) ?>, Հայաստան</address>
                    <?php if (auth()->isStaff()): ?><a href="/admin/books">Ադմին</a><?php else: ?><a href="/register">Միանալ ակումբին</a><?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <div class="toast" role="status" aria-live="polite" data-toast></div>

</body>
</html>
