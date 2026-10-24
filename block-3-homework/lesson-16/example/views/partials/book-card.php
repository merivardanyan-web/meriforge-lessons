<?php
// Գրքի քարտ — PHP Դաս 07, Դաս 12-ից՝ Book օբյեկտ, Դաս 13-ից՝ ցանկացած ժառանգ
// Պետք են՝ $book (Book), $back
$status = $book->status();
$cover  = $book->cover;
?>
<article class="book is-<?= e($status) ?>">
    <?php if ($cover !== null): ?>
        <div class="book__cover book__cover--img"><img src="/images/uploads/<?= e($cover) ?>" alt="«<?= e($book->title) ?>»-ի շապիկը" width="400" height="200" loading="lazy"></div>
    <?php else: ?>
        <div class="book__cover" style="--cover: <?= e($book->color) ?>"><span><?= e($book->title) ?></span></div>
    <?php endif; ?>

    <div class="book__body">
        <h3 class="book__title"><a href="<?= e($book->url()) ?>" title="<?= e($book->title) ?>"><?= e($book->shortTitle(30)) ?></a></h3>
        <p class="meta"><?= e($book->author) ?> · <?= e($book->yearLabel()) ?></p>
        <!-- Պոլիմորֆիզմ․ նույն կանչը, ամեն դաս՝ իր պատասխանը -->
        <p class="meta"><?= e($book->formatLabel()) ?><?php if ($book->status() !== 'done'): ?> · մնաց <?= e($book->timeLeftLabel()) ?><?php endif; ?></p>
        <div class="progress"><span class="progress__bar" style="--progress: <?= $book->progress() ?>%"></span></div>
        <p class="meta"><?= $book->read ?> / <?= e(formatPages($book->pages)) ?> · <?= $book->progress() ?>%</p>
        <p class="flex gap-10 align-center flex-wrap">
            <span class="pill"><?= e($book->statusLabel()) ?></span>
            <?php if ($book->isNew()): ?><span class="pill pill--new">Նոր</span><?php endif; ?>
            <?php if ($book instanceof App\Models\Lendable && $book->isLent()): ?><span class="pill pill--lent">Տրված է՝ <?= e($book->lentTo()) ?></span><?php endif; ?>
            <?php if ($book->stars() !== ''): ?><span class="stars-text" aria-label="<?= $book->rating ?> աստղ 5-ից"><?= $book->stars() ?></span><?php endif; ?>
        </p>

        <div class="book__actions">
            <!-- Վիճակ է փոխում → POST ֆորմա, ոչ հղում (Դաս 07) -->
            <?php if (readingList()->has($book->id)): ?>
                <form method="post" action="/list/remove">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $book->id ?>">
                    <input type="hidden" name="back" value="<?= e($back) ?>">
                    <button type="submit" class="btn btn--ghost btn--sm">✓ Ցուցակում է</button>
                </form>
            <?php elseif ($status !== 'done'): ?>
                <form method="post" action="/list/add">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $book->id ?>">
                    <input type="hidden" name="back" value="<?= e($back) ?>">
                    <button type="submit" class="btn btn--outline btn--sm">+ Կարդալու ցուցակ</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</article>
