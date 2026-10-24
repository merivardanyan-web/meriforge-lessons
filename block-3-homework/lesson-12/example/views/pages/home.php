<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Իմ գրադարանը</h1>
                <p class="lead">
                    <?= $stats['books'] ?> գիրք · <?= $stats['done'] ?> կարդացած ·
                    ընդամենը <?= e(formatPages($stats['read_pages'])) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section id="shelf">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title">Դարակ</h2>

            <!-- GET ֆորմա․ ֆիլտրերը URL-ում են, էջը կարելի է պահել էջանիշում -->
            <form method="get" action="index.php" class="filter-bar" role="search">
                <div class="field">
                    <label for="q">Որոնում</label>
                    <input type="search" id="q" name="q" value="<?= e($filters['q']) ?>" placeholder="Վերնագիր կամ հեղինակ">
                </div>
                <div class="field">
                    <label for="genre">Ժանր</label>
                    <select id="genre" name="genre">
                        <option value="">Բոլորը</option>
                        <?php foreach ($genres as $g): ?>
                            <option value="<?= e($g->slug) ?>"<?= $filters['genre'] === $g->slug ? ' selected' : '' ?>><?= e($g->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="status">Կարգավիճակ</label>
                    <select id="status" name="status">
                        <option value="">Բոլորը</option>
                        <?php foreach (Book::STATUS_LABELS as $key => $label): ?>
                            <option value="<?= $key ?>"<?= $filters['status'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field field--short">
                    <label for="min">Էջ՝ սկսած</label>
                    <input type="number" id="min" name="min" min="0" value="<?= e((string) ($filters['min'] ?? '')) ?>">
                </div>
                <div class="field field--short">
                    <label for="max">մինչև</label>
                    <input type="number" id="max" name="max" min="0" value="<?= e((string) ($filters['max'] ?? '')) ?>">
                </div>
                <div class="field">
                    <label for="sort">Դասավորել</label>
                    <select id="sort" name="sort">
                        <?php foreach (SORT_LABELS as $key => $label): ?>
                            <option value="<?= $key ?>"<?= $sort === $key ? ' selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-bar__actions">
                    <button type="submit" class="btn btn--primary btn--sm">Ցույց տալ</button>
                    <a href="index.php" class="btn btn--ghost btn--sm">Մաքրել</a>
                </div>
            </form>

            <p class="meta" role="status">Գտնվեց <?= $total ?> գիրք<?= $pages > 1 ? " · էջ {$page} / {$pages}" : '' ?></p>

            <?php if ($books === []): ?>
                <p class="empty">Այս ֆիլտրերով գիրք չկա։ <a href="index.php">Մաքրել ֆիլտրերը</a></p>
            <?php else: ?>
            <div class="grid-auto gap-20">
                <?php foreach ($books as $book): ?>
                    <?= render('partials/book-card', ['book' => $book, 'back' => pageUrl($filters, $sort, $page)]) ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?= render('partials/pagination', ['filters' => $filters, 'sort' => $sort, 'page' => $page, 'pages' => $pages]) ?>
        </div>
    </div>
</section>
