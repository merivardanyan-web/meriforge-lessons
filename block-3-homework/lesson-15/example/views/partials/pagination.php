<?php
// Էջավորում — PHP Դաս 10․ պետք են՝ $path, $filters, $sort, $page, $pages
if ($pages <= 1) {
    return;                       // մեկ էջ՝ ոչինչ չենք ցուցադրում
}
?>
<nav class="pager mt-20" aria-label="Էջեր">
    <?php if ($page > 1): ?>
        <a href="<?= e(pageUrl($path, $filters, $sort, $page - 1)) ?>" class="btn btn--ghost btn--sm" rel="prev">← Նախորդ</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="<?= e(pageUrl($path, $filters, $sort, $i)) ?>" class="btn btn--outline btn--sm"<?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $pages): ?>
        <a href="<?= e(pageUrl($path, $filters, $sort, $page + 1)) ?>" class="btn btn--ghost btn--sm" rel="next">Հաջորդ →</a>
    <?php endif; ?>
</nav>
