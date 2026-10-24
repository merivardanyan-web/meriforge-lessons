<?php
/** @var App\Models\Product $product @var App\Models\Product[] $related @var App\Services\Cart $cart */
use App\Models\DigitalProduct;
use App\Models\PhysicalProduct;
use App\Services\CartSummary;

$p = $product;
$inCart = $cart->qtyOf($p->id);
?>
<section id="product">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <li><a href="/catalog">Կատալոգ</a></li>
                    <?php if ($p->category): ?>
                        <li><a href="<?= e($p->category->url()) ?>"><?= e($p->category->name) ?></a></li>
                    <?php endif; ?>
                    <li aria-current="page"><?= e($p->name) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row flex-wrap align-start">
        <div class="col w-50 w-md-100">
            <div class="gallery">
                <picture class="gallery__main ratio-3-2">
                    <img class="img-cover" src="<?= e($p->imageUrl()) ?>" alt="<?= e($p->name) ?>" width="600" height="400">
                </picture>
            </div>
        </div>

        <div class="col w-50 w-md-100">
            <div class="wrapper">
                <?php if ($p->hasDiscount()): ?>
                    <span class="badge mb-10">-<?= $p->discount ?>%</span>
                <?php endif; ?>
                <h1 class="mb-10"><?= e($p->name) ?></h1>
                <p class="text-muted mb-20">
                    Կոդ՝ TM-<?= 1000 + $p->id ?> · <?= e($p->typeLabel()) ?> ·
                    <span class="stock"><?= $p->inStock() ? 'Առկա է' : 'Ժամանակավորապես չկա' ?></span>
                </p>

                <p class="product__price mb-20">
                    <?= $p->formattedPrice() ?>
                    <?php if ($p->hasDiscount()): ?><del><?= $p->formattedOldPrice() ?></del><?php endif; ?>
                </p>

                <?php if ($p->description): ?>
                    <p class="mb-20"><?= nl2br(e($p->description)) ?></p>
                <?php endif; ?>

                <?php if ($p->inStock()): ?>
                    <form class="flex align-end gap-10 flex-wrap mb-30" method="post" action="/cart/add" data-cart-add>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $p->id ?>">
                        <input type="hidden" name="back" value="<?= e($p->url()) ?>">
                        <div class="field">
                            <label for="qty" class="field__label">Քանակ</label>
                            <input id="qty" name="qty" type="number" value="1" min="1" max="<?= $p->maxQty() ?>" class="input input--qty">
                        </div>
                        <button type="submit" class="btn btn--primary">Ավելացնել զամբյուղ</button>
                    </form>
                    <?php if ($inCart > 0): ?>
                        <p class="text-muted mb-20">Զամբյուղում արդեն <?= $inCart ?> հատ է․ <a href="/cart" class="link">բացել զամբյուղը</a></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="mb-30"><button type="button" class="btn btn--primary" disabled>Սպառված է</button></p>
                <?php endif; ?>

                <ul class="perks">
                    <?php if ($p instanceof DigitalProduct): ?>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg> Բանալին՝ էլ. փոստով, պատվերից անմիջապես հետո</li>
                    <?php else: ?>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 4h14v12H1zM15 9h4l4 4v3h-8z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/></svg> Անվճար առաքում Երևանում՝ <?= formatPrice(CartSummary::FREE_FROM) ?>-ից</li>
                    <?php endif; ?>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5z"/><path d="m9 12 2 2 4-4"/></svg> 2 տարվա պաշտոնական երաշխիք</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="details">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">
            <h2 class="section-title mb-30">Ապրանքի մասին</h2>
            <table class="table specs">
                <caption class="visually-hidden"><?= e($p->name) ?>-ի տվյալները</caption>
                <tbody>
                    <tr><th scope="row">Համառոտ</th><td><?= e($p->specs ?? '—') ?></td></tr>
                    <tr><th scope="row">Կատեգորիա</th><td><?= e($p->category?->name ?? 'Առանց կատեգորիայի') ?></td></tr>
                    <?php if ($p instanceof PhysicalProduct): ?>
                        <tr><th scope="row">Պահեստում</th><td><?= $p->stock ?> հատ</td></tr>
                        <tr><th scope="row">Քաշ</th><td><?= number_format($p->weight() / 1000, 1, '.', '') ?> կգ</td></tr>
                        <tr><th scope="row">Առաքում</th><td><?= formatPrice($p->shippingCost()) ?> · Երևանում անվճար <?= formatPrice(CartSummary::FREE_FROM) ?>-ից</td></tr>
                    <?php endif; ?>
                    <tr><th scope="row">Խանութում է</th><td><?= e(date('d.m.Y', strtotime($p->createdAt))) ?>-ից</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php if ($related !== []): ?>
<section id="related">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Նման ապրանքներ</h2>
            <div class="grid-auto gap-20">
                <?php foreach ($related as $r): ?>
                    <?= render('partials/card', ['p' => $r, 'back' => $p->url()]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
