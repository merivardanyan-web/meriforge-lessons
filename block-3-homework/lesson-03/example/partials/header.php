<?php /* Header-ը՝ մեկ տեղում — PHP Դաս 03։ Պետք են $siteName, $email, $pageTitle */ ?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> — <?= $siteName ?></title>
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

