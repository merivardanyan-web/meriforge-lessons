<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Իմ գրադարանը</h1>
                <p class="lead">
                    <?= count($books) ?> գիրք · <?= count(filterByStatus($books, 'done')) ?> կարդացած ·
                    ընդամենը <?= e(formatPages(totalRead($books))) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section id="shelf">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title">Դարակ</h2>

            <?php if (empty($books)): ?>
                <p class="empty">Դարակը դեռ դատարկ է։</p>
            <?php else: ?>
            <div class="grid-auto gap-20">
                <?php foreach ($books as $book): ?>
                    <?php $status = bookStatus($book); ?>
                    <article class="book is-<?= e($status) ?>">
                        <!-- style ատրիբուտ՝ e() և չակերտներ (Դաս 05, ելքի համատեքստեր) -->
                        <div class="book__cover" style="--cover: <?= e($book['color']) ?>"><span><?= e($book['title']) ?></span></div>
                        <div class="book__body">
                            <h3 class="book__title"><a href="book.php?id=<?= (int) $book['id'] ?>"><?= e($book['title']) ?></a></h3>
                            <p class="meta"><?= e($book['author']) ?> · <?= e((string) ($book['year'] ?? 'տարին անհայտ')) ?></p>
                            <div class="progress"><span class="progress__bar" style="--progress: <?= progress($book) ?>%"></span></div>
                            <p class="meta"><?= (int) $book['read'] ?> / <?= e(formatPages($book['pages'])) ?> · <?= progress($book) ?>%</p>
                            <span class="pill"><?= e(statusLabel($status)) ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
