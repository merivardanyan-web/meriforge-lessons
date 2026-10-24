<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Կարդալու ցուցակ</h1>
                <p class="lead"><?= count($items) ?> գիրք · մնացել է <?= e(formatPages($pagesLeft)) ?></p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <?php if ($items === []): ?>
                <p class="empty">Ցուցակը դատարկ է։ <a href="index.php">Ընտրիր գիրք դարակից</a></p>
            <?php else: ?>
                <div class="grid-auto gap-20">
                    <?php foreach ($items as $book): ?>
                        <?= render('partials/book-card', ['book' => $book, 'back' => 'list.php']) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
