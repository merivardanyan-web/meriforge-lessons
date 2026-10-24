<?php $status = bookStatus($book); $cover = coverOf($book); ?>
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
                <?php if ($cover !== null): ?>
                    <div class="book__cover book__cover--img"><img src="images/uploads/<?= e($cover) ?>" alt="«<?= e($book['title']) ?>»-ի շապիկը" width="400" height="200"></div>
                <?php else: ?>
                    <div class="book__cover" style="--cover: <?= e($book['color']) ?>"><span><?= e($book['title']) ?></span></div>
                <?php endif; ?>
            </div>

            <!-- enctype՝ առանց սրա $_FILES-ը դատարկ է (Դաս 07) -->
            <form method="post" action="cover.php" enctype="multipart/form-data" class="mt-20">
                <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                <label for="cover">Շապիկի նկար (JPG, PNG, WebP, մինչև 2 ՄԲ)</label>
                <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn btn--outline btn--sm mt-10">Վերբեռնել</button>
            </form>
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

                <?php if (!inList((int) $book['id']) && $status !== 'done'): ?>
                    <form method="post" action="list-add.php">
                        <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                        <input type="hidden" name="back" value="book.php?id=<?= (int) $book['id'] ?>">
                        <button type="submit" class="btn btn--primary">+ Կարդալու ցուցակ</button>
                    </form>
                <?php endif; ?>

                <p class="mt-20"><a href="index.php" class="btn btn--ghost">← Ամբողջ դարակը</a></p>
            </div>
        </div>
    </div>
</section>
