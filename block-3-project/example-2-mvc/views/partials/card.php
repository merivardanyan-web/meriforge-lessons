<?php
/** @var App\Models\Product $p  @var string $back */
use App\Models\DigitalProduct;
use App\Models\PhysicalProduct;
?>
<article class="card<?= $p->inStock() ? '' : ' is-out' ?>">
    <?php if ($p->hasDiscount()): ?>
        <span class="card__badge">-<?= $p->discount ?>%</span>
    <?php elseif ($p->isNew()): ?>
        <span class="card__badge card__badge--new">Նոր</span>
    <?php endif; ?>

    <picture class="card__media ratio-3-2">
        <img class="img-cover" src="<?= e($p->imageUrl()) ?>" alt="<?= e($p->name) ?>" width="600" height="400" loading="lazy">
    </picture>

    <div class="card__body">
        <h3 class="card__title"><a href="<?= e($p->url()) ?>"><?= e($p->name) ?></a></h3>
        <p class="card__meta"><?= e($p->specs ?? '') ?></p>

        <?php /* Պոլիմորֆիզմ շաբլոնում — PHP Դաս 13 */ ?>
        <?php if ($p instanceof DigitalProduct): ?>
            <p class="card__note">Ակնթարթային առաքում էլ. փոստով</p>
        <?php elseif ($p instanceof PhysicalProduct && $p->isLowStock()): ?>
            <p class="card__warning">Մնացել է <?= $p->stock ?> հատ</p>
        <?php endif; ?>

        <div class="card__footer">
            <p class="card__price">
                <?= $p->formattedPrice() ?>
                <?php if ($p->hasDiscount()): ?><del><?= $p->formattedOldPrice() ?></del><?php endif; ?>
            </p>

            <?php if ($p->inStock()): ?>
                <form method="post" action="/cart/add" data-cart-add>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $p->id ?>">
                    <input type="hidden" name="back" value="<?= e($back ?? '/') ?>">
                    <button type="submit" class="btn btn--primary btn--sm">Գնել<span class="visually-hidden"> <?= e($p->name) ?></span></button>
                </form>
            <?php else: ?>
                <button type="button" class="btn btn--primary btn--sm" disabled>Սպառված է</button>
            <?php endif; ?>
        </div>
    </div>
</article>
