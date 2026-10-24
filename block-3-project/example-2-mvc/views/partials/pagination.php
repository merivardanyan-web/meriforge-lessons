<?php
/** @var int $page  @var int $pages  @var array $query  @var string $path */
if ($pages < 2) {
    return;            // մեկ էջի համար էջավորում պետք չէ
}
?>
<nav class="pagination" aria-label="Էջավորում">
    <ul class="flex flex-wrap gap-10">
        <?php if ($page > 1): ?>
            <li><a href="<?= e(url($path, ['page' => $page - 1] + $query)) ?>">← Նախորդը</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li><a href="<?= e(url($path, ['page' => $i > 1 ? $i : null] + $query)) ?>"<?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a></li>
        <?php endfor; ?>

        <?php if ($page < $pages): ?>
            <li><a href="<?= e(url($path, ['page' => $page + 1] + $query)) ?>">Հաջորդը →</a></li>
        <?php endif; ?>
    </ul>
</nav>
