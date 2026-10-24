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
                    <?= render('partials/book-card', ['book' => $book, 'back' => 'index.php']) ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
