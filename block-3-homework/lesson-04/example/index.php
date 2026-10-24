<?php
declare(strict_types=1);

// Անիի գրքերը · PHP Դաս 03 — ֆունկցիաներ և ընդհանուր header/footer
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

$pageTitle = 'Գրքեր';
$year      = date('Y');

$shelf   = sortBooks($books, 'progress');
$reading = filterByStatus($books, 'reading');
$genres  = groupByGenre($books);
$longest = longestBook($books);
?>
<?php require __DIR__ . '/partials/header.php'; ?>

    <main id="main">
        <section class="page-head">
            <div class="row">
                <div class="col w-100">
                    <div class="wrapper">
                        <h1>Իմ գրադարանը</h1>
                        <p class="lead">
                            <?= count($books) ?> գիրք · <?= count(filterByStatus($books, 'done')) ?> կարդացած ·
                            ընդամենը <?= formatPages(totalRead($books)) ?> ·
                            միջինը <?= formatPages(averagePages($books)) ?> գրքում
                        </p>
                        <?php if ($longest !== null): ?>
                            <p class="meta">Ամենաերկարը՝ «<?= $longest['title'] ?>», <?= formatPages($longest['pages']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="reading" class="bg-surface">
            <div class="row">
                <div class="col w-100">
                    <h2 class="section-title">Կարդում եմ հիմա (<?= count($reading) ?>)</h2>
                    <ul class="check-list">
                        <?php foreach ($reading as $book): ?>
                            <li><strong><?= $book['title'] ?></strong> — <?= progress($book) ?>%, մնացել է <?= formatPages($book['pages'] - $book['read']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <section id="shelf">
            <div class="row">
                <div class="col w-100">
                    <h2 class="section-title">Դարակ՝ ըստ առաջընթացի</h2>

                    <div class="grid-auto gap-20">
                    <?php foreach ($shelf as $book): ?>
                        <?php $status = bookStatus($book); ?>
                        <article class="book is-<?= $status ?>">
                            <div class="book__cover" style="--cover: <?= $book['color'] ?>"><span><?= $book['title'] ?></span></div>
                            <div class="book__body">
                                <h3 class="book__title"><?= $book['title'] ?></h3>
                                <p class="meta"><?= $book['author'] ?> · <?= $book['year'] ?? 'տարին անհայտ' ?></p>
                                <div class="progress"><span class="progress__bar" style="--progress: <?= progress($book) ?>%"></span></div>
                                <p class="meta"><?= $book['read'] ?> / <?= formatPages($book['pages']) ?> · <?= progress($book) ?>%</p>
                                <span class="pill"><?= statusLabel($status) ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="genres" class="bg-surface">
            <div class="row">
                <div class="col w-100">
                    <h2 class="section-title">Ժանրերով</h2>
                    <?php foreach ($genres as $genre => $list): ?>
                        <h3><?= $genre ?> (<?= count($list) ?>)</h3>
                        <p><?= implode(', ', array_map(fn(array $b): string => $b['title'], $list)) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

<?php require __DIR__ . '/partials/footer.php'; ?>
