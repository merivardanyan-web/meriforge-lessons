<?php // Ադմինի layout — PHP Դաս 11, Դաս 15-ից՝ բացարձակ ուղիներ ?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title><?= e($title) ?> · Ադմին · <?= e(SITE_NAME) ?></title>
    <link rel="icon" href="/images/favicon.svg">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="admin<?= isDark() ? ' dark' : '' ?>">

    <a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

    <header class="header">
        <div class="row align-center justify-between flex-wrap">
            <div class="col">
                <a href="/admin/books" class="logo"><?= e(SITE_NAME) ?> · ադմին</a>
            </div>
            <div class="col">
                <nav aria-label="Ադմինի մենյու">
                    <ul class="menu flex align-center flex-wrap gap-10">
                        <li><a href="/admin/books"<?= ariaCurrent('/admin/books') ?>>Գրքեր</a></li>
                        <li><a href="/admin/books/create" class="btn btn--primary btn--sm">+ Նոր գիրք</a></li>
                        <li><a href="/">Կայք →</a></li>
                        <li class="hello"><?= e(auth()->user()->name) ?> · <?= e(auth()->user()->role) ?></li>
                        <li>
                            <form method="post" action="/logout">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn--ghost btn--sm">Ելք</button>
                            </form>
                        </li>
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

</body>
</html>
