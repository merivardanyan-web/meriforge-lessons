<?php /** @var string $title @var string $content @var App\Services\Auth $auth */
$user = $auth->user();
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($title) ?> · Ադմին · <?= e(SITE_NAME) ?></title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="admin">

<a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

<header class="header">
    <div class="row align-center justify-between flex-wrap">
        <div class="col">
            <a href="/admin" class="logo"><?= e(SITE_NAME) ?> · ադմին</a>
        </div>
        <div class="col">
            <nav aria-label="Ադմինի մենյու">
                <ul class="menu flex align-center flex-wrap gap-10">
                    <li><a href="/admin"<?= ariaCurrent('/admin') ?>>Ապրանքներ</a></li>
                    <li><a href="/admin/orders"<?= ariaCurrent('/admin/orders') ?>>Պատվերներ</a></li>
                    <li><a href="/admin/products/create" class="btn btn--primary btn--sm">+ Նոր ապրանք</a></li>
                    <li><a href="/">Կայք →</a></li>
                    <li>
                        <form method="post" action="/logout">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn--ghost btn--sm">Ելք (<?= e($user?->name) ?>, <?= e($user?->role) ?>)</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main id="main">
    <div class="row"><div class="col w-100">
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert--success mt-20" role="status"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="alert alert--danger mt-20" role="alert"><?= e($msg) ?></div>
        <?php endif; ?>
    </div></div>

    <?= $content ?>
</main>

</body>
</html>
