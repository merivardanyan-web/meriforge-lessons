<?php $status = bookStatus($book); ?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <ol class="crumbs">
                    <li><a href="index.php">Գրքեր</a></li>
                    <li aria-current="page"><?= e($book['title']) ?></li>
                </ol>
                <h1><?= e($book['title']) ?></h1>
                <p class="lead"><?= e($book['author']) ?> · <?= e((string) ($book['year'] ?? 'տարին անհայտ')) ?> · <?= e($book['genre']) ?></p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row flex-wrap">
        <div class="col w-40 w-md-100">
            <div class="book book--detail is-<?= e($status) ?>">
                <div class="book__cover" style="--cover: <?= e($book['color']) ?>"><span><?= e($book['title']) ?></span></div>
            </div>
        </div>
        <div class="col w-60 w-md-100">
            <div class="wrapper">
                <span class="pill"><?= e(statusLabel($status)) ?></span>
                <div class="progress mt-20"><span class="progress__bar" style="--progress: <?= progress($book) ?>%"></span></div>
                <p class="meta"><?= (int) $book['read'] ?> / <?= e(formatPages($book['pages'])) ?> · <?= progress($book) ?>%</p>

                <?php if (isset($book['link'])): ?>
                    <!-- ⭐ e(safeUrl(...))՝ երկուսը միասին։ Միայն e()-ն javascript:-ը չի կանգնեցնի -->
                    <p><a href="<?= e(safeUrl($book['link'])) ?>" class="btn btn--outline" rel="noopener">Կարդալ գրքի մասին</a></p>
                <?php endif; ?>

                <p><a href="index.php" class="btn btn--ghost">← Ամբողջ դարակը</a></p>
            </div>
        </div>
    </div>
</section>
