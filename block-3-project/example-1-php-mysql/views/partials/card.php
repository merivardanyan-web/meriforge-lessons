<?php
/** Ապրանքի քարտ — նույն markup-ը, ինչ Բլոկ 1-ում, որ CSS-ը չփոխենք
 *  @var array $p  @var string $back */
$url = urlWith('/product.php', ['slug' => $p['slug']]);
?>
<article class="card<?= $p['stock'] === 0 ? ' is-out' : '' ?>">
    <?php if ($p['discount'] > 0): ?>
        <span class="card__badge">-<?= $p['discount'] ?>%</span>
    <?php elseif (strtotime($p['created_at']) > strtotime('-30 days')): ?>
        <span class="card__badge card__badge--new">Նոր</span>
    <?php endif; ?>

    <picture class="card__media ratio-3-2">
        <img class="img-cover" src="/images/<?= e($p['image'] ?? 'placeholder.jpg') ?>"
             alt="<?= e($p['name']) ?>" width="600" height="400" loading="lazy">
    </picture>

    <div class="card__body">
        <h3 class="card__title"><a href="<?= e($url) ?>"><?= e($p['name']) ?></a></h3>
        <p class="card__meta"><?= e($p['specs']) ?></p>

        <?php if ($p['stock'] > 0 && $p['stock'] <= 3): ?>
            <p class="card__warning">Մնացել է <?= $p['stock'] ?> հատ</p>
        <?php endif; ?>

        <div class="card__footer">
            <p class="card__price">
                <?= formatPrice($p['final_price']) ?>
                <?php if ($p['discount'] > 0): ?><del><?= formatPrice($p['price']) ?></del><?php endif; ?>
            </p>

            <?php if ($p['stock'] > 0): ?>
                <!-- Վիճակ է փոխում → POST, ոչ հղում — PHP Դաս 7 -->
                <form method="post" action="/cart-add.php">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="back" value="<?= e($back ?? '/') ?>">
                    <button type="submit" class="btn btn--primary btn--sm">Գնել<span class="visually-hidden"> <?= e($p['name']) ?></span></button>
                </form>
            <?php else: ?>
                <button type="button" class="btn btn--primary btn--sm" disabled>Սպառված է</button>
            <?php endif; ?>
        </div>
    </div>
</article>
