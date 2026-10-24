<?php
// Գրքի քարտ — PHP Դաս 07․ նույն markup-ը գլխավոր էջում և ցուցակում
// Պետք են՝ $book, $back
$status = bookStatus($book);
$cover  = coverOf($book);
?>
<article class="book is-<?= e($status) ?>">
    <?php if ($cover !== null): ?>
        <div class="book__cover book__cover--img"><img src="images/uploads/<?= e($cover) ?>" alt="«<?= e($book['title']) ?>»-ի շապիկը" width="400" height="200" loading="lazy"></div>
    <?php else: ?>
        <div class="book__cover" style="--cover: <?= e($book['color']) ?>"><span><?= e($book['title']) ?></span></div>
    <?php endif; ?>

    <div class="book__body">
        <h3 class="book__title"><a href="book.php?id=<?= (int) $book['id'] ?>"><?= e($book['title']) ?></a></h3>
        <p class="meta"><?= e($book['author']) ?> · <?= e((string) ($book['year'] ?? 'տարին անհայտ')) ?></p>
        <div class="progress"><span class="progress__bar" style="--progress: <?= progress($book) ?>%"></span></div>
        <p class="meta"><?= (int) $book['read'] ?> / <?= e(formatPages($book['pages'])) ?> · <?= progress($book) ?>%</p>
        <span class="pill"><?= e(statusLabel($status)) ?></span>

        <div class="book__actions">
            <!-- Վիճակ է փոխում → POST ֆորմա, ոչ հղում (Դաս 07) -->
            <?php if (inList((int) $book['id'])): ?>
                <form method="post" action="list-remove.php">
                    <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                    <input type="hidden" name="back" value="<?= e($back) ?>">
                    <button type="submit" class="btn btn--ghost btn--sm">✓ Ցուցակում է</button>
                </form>
            <?php elseif ($status !== 'done'): ?>
                <form method="post" action="list-add.php">
                    <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                    <input type="hidden" name="back" value="<?= e($back) ?>">
                    <button type="submit" class="btn btn--outline btn--sm">+ Կարդալու ցուցակ</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</article>
