<?php /** @var string $title @var string $content @var string $active */
$current = fn(string $key): string => ($active ?? '') === $key ? ' aria-current="page"' : '';
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
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
            <a href="/admin/index.php" class="logo"><?= e(SITE_NAME) ?> · ադմին</a>
        </div>
        <div class="col">
            <nav aria-label="Ադմինի մենյու">
                <ul class="menu flex align-center flex-wrap gap-10">
                    <li><a href="/admin/index.php"<?= $current('products') ?>>Ապրանքներ</a></li>
                    <li><a href="/admin/orders.php"<?= $current('orders') ?>>Պատվերներ</a></li>
                    <li><a href="/admin/form.php" class="btn btn--primary btn--sm">+ Նոր ապրանք</a></li>
                    <li><a href="/">Կայք →</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main id="main">
    <div class="row"><div class="col w-100">
        <!-- ⚠️ PHP Դաս 16-ից առաջ ադմինը պաշտպանված չէ։ Այս տարբերակը մի՛ հրապարակիր -->
        <p class="alert alert--warning mt-20">Ուսումնական տարբերակ․ ադմին վահանակը դեռ առանց մուտքի է (մուտքը՝ PHP Դաս 16)։</p>

        <?php if ($msg = flash('success')): ?>
            <div class="alert alert--success" role="status"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="alert alert--danger" role="alert"><?= e($msg) ?></div>
        <?php endif; ?>
    </div></div>

    <?= $content ?>
</main>

</body>
</html>
